<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
        $date = $request->query('date', Carbon::today()->toDateString());

        $orders = $this->orderRepository->getBranchOrders($branchId)
            ->whereBetween('created_at', [Carbon::parse($date)->startOfDay(), Carbon::parse($date)->endOfDay()]);

        return response()->json([
            'date' => $date,
            'total_orders' => $orders->count(),
            'total_revenue' => $orders->sum('total_amount'),
            'total_collected' => $orders->sum('paid_amount'),
            'orders' => $orders,
        ]);
    }

    public function monthly(Request $request)
    {
        $branchId = $request->user()->branch_id;
        $month = $request->query('month', Carbon::now()->month);
        $year = $request->query('year', Carbon::now()->year);

        $orders = $this->orderRepository->getBranchOrders($branchId)
            ->filter(function($order) use ($month, $year) {
                return $order->created_at->month == $month && $order->created_at->year == $year;
            });

        return response()->json([
            'month' => $month,
            'year' => $year,
            'total_orders' => $orders->count(),
            'total_revenue' => $orders->sum('total_amount'),
            'total_collected' => $orders->sum('paid_amount'),
        ]);
    }
}
