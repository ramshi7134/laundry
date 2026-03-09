<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\Expense;
use App\Models\OrderItem;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use Carbon\Carbon;

class ReportController extends Controller
{
    protected $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function daily(Request $request)
    {
        $branchId = $request->user()->branch_id;
        $date     = $request->query('date', Carbon::today()->toDateString());

        $orders = Order::where('branch_id', $branchId)
            ->whereDate('created_at', $date)
            ->with(['items.service', 'payments'])
            ->get();

        $expenses = Expense::where('branch_id', $branchId)
            ->whereDate('date', $date)
            ->get();

        $totalRevenue   = $orders->sum('total_amount');
        $totalDiscount  = $orders->sum('discount_amount');
        $totalNet       = $totalRevenue - $totalDiscount;
        $totalCollected = $orders->sum('paid_amount');
        $totalExpenses  = $expenses->sum('amount');

        return response()->json([
            'date'                => $date,
            'total_orders'        => $orders->count(),
            'total_revenue'       => round($totalRevenue, 2),
            'total_discount'      => round($totalDiscount, 2),
            'net_revenue'         => round($totalNet, 2),
            'total_collected'     => round($totalCollected, 2),
            'total_due'           => round($totalNet - $totalCollected, 2),
            'total_expenses'      => round($totalExpenses, 2),
            'net_profit'          => round($totalCollected - $totalExpenses, 2),
            'orders_by_status'    => $orders->groupBy('status')->map->count(),
            'payments_by_method'  => $orders->pluck('payments')->flatten()
                                             ->groupBy('method')
                                             ->map(fn($p) => round($p->sum('amount'), 2)),
            'orders'              => $orders,
        ]);
    }

    public function monthly(Request $request)
    {
        $branchId = $request->user()->branch_id;
        $month    = (int) $request->query('month', Carbon::now()->month);
        $year     = (int) $request->query('year', Carbon::now()->year);

        $orders = Order::where('branch_id', $branchId)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->with('payments')
            ->get();

        $expenses = Expense::where('branch_id', $branchId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get();

        $totalRevenue   = $orders->sum('total_amount');
        $totalDiscount  = $orders->sum('discount_amount');
        $totalNet       = $totalRevenue - $totalDiscount;
        $totalCollected = $orders->sum('paid_amount');
        $totalExpenses  = $expenses->sum('amount');

        // Daily breakdown
        $dailyBreakdown = $orders->groupBy(fn($o) => $o->created_at->toDateString())
            ->map(fn($dayOrders) => [
                'orders'    => $dayOrders->count(),
                'revenue'   => round($dayOrders->sum('total_amount'), 2),
                'collected' => round($dayOrders->sum('paid_amount'), 2),
            ]);

        return response()->json([
            'month'              => $month,
            'year'               => $year,
            'total_orders'       => $orders->count(),
            'total_revenue'      => round($totalRevenue, 2),
            'total_discount'     => round($totalDiscount, 2),
            'net_revenue'        => round($totalNet, 2),
            'total_collected'    => round($totalCollected, 2),
            'total_due'          => round($totalNet - $totalCollected, 2),
            'total_expenses'     => round($totalExpenses, 2),
            'net_profit'         => round($totalCollected - $totalExpenses, 2),
            'orders_by_status'   => $orders->groupBy('status')->map->count(),
            'daily_breakdown'    => $dailyBreakdown,
        ]);
    }

    public function revenueByService(Request $request)
    {
        $branchId  = $request->user()->branch_id;
        $dateFrom  = $request->query('date_from', Carbon::now()->startOfMonth()->toDateString());
        $dateTo    = $request->query('date_to', Carbon::today()->toDateString());

        $items = OrderItem::whereHas('order', fn($q) =>
                    $q->where('branch_id', $branchId)
                      ->whereDate('created_at', '>=', $dateFrom)
                      ->whereDate('created_at', '<=', $dateTo)
                 )
                 ->with('service:id,name')
                 ->selectRaw('service_id, SUM(quantity) as total_qty, SUM(total) as total_revenue')
                 ->groupBy('service_id')
                 ->get();

        return response()->json([
            'date_from' => $dateFrom,
            'date_to'   => $dateTo,
            'services'  => $items->map(fn($i) => [
                'service_id'    => $i->service_id,
                'service_name'  => optional($i->service)->name,
                'total_qty'     => (int) $i->total_qty,
                'total_revenue' => round($i->total_revenue, 2),
            ])->sortByDesc('total_revenue')->values(),
        ]);
    }

    public function branchSummary(Request $request)
    {
        $branchId = $request->user()->branch_id;

        $allTime = Order::where('branch_id', $branchId);

        return response()->json([
            'branch_id'           => $branchId,
            'total_orders'        => (clone $allTime)->count(),
            'total_revenue'       => round((clone $allTime)->sum('total_amount'), 2),
            'total_collected'     => round((clone $allTime)->sum('paid_amount'), 2),
            'total_due'           => round((clone $allTime)->sum(\DB::raw('(total_amount - discount_amount - paid_amount)')), 2),
            'orders_today'        => (clone $allTime)->whereDate('created_at', today())->count(),
            'orders_this_month'   => (clone $allTime)->whereMonth('created_at', now()->month)
                                                      ->whereYear('created_at', now()->year)->count(),
            'pending_orders'      => (clone $allTime)->where('status', 'pending')->count(),
            'ready_orders'        => (clone $allTime)->where('status', 'ready')->count(),
        ]);
    }
}
