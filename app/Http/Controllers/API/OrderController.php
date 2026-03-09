<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\OrderService;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use Illuminate\Validation\ValidationException;
use Exception;

class OrderController extends Controller
{
    protected $orderService;
    protected $orderRepository;

    public function __construct(OrderService $orderService, OrderRepositoryInterface $orderRepository)
    {
        $this->orderService    = $orderService;
        $this->orderRepository = $orderRepository;
    }

    public function index(Request $request)
    {
        $branchId = $request->user()->branch_id;
        $perPage  = (int) $request->query('per_page', 20);

        $filters = $request->only([
            'status', 'payment_status', 'customer_id', 'search', 'date_from', 'date_to',
        ]);

        $orders = $this->orderRepository->getBranchOrdersPaginated($branchId, $filters, $perPage);

        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id'              => 'required|exists:customers,id',
            'items'                    => 'required|array|min:1',
            'items.*.service_id'       => 'required|exists:services,id',
            'items.*.quantity'         => 'required|integer|min:1',
            'items.*.unit_price'       => 'nullable|numeric|min:0',
            'notes'                    => 'nullable|string',
            // Single payment (legacy)
            'paid_amount'              => 'nullable|numeric|min:0',
            'payment_method'           => 'nullable|in:cash,card,upi,wallet',
            'payment_reference'        => 'nullable|string',
            // Split payment
            'payments'                 => 'nullable|array',
            'payments.*.amount'        => 'required_with:payments|numeric|min:0.01',
            'payments.*.method'        => 'required_with:payments|in:cash,card,upi,wallet',
            'payments.*.reference'     => 'nullable|string',
            // Discount
            'discount_type'            => 'nullable|in:flat,percent,loyalty,wallet',
            'discount_value'           => 'required_if:discount_type,flat,percent,wallet|numeric|min:0',
            'discount_reference'       => 'nullable|string',
            'loyalty_points_redeem'    => 'required_if:discount_type,loyalty|integer|min:1',
        ]);

        $data              = $request->all();
        $data['branch_id'] = $request->user()->branch_id;
        $data['created_by'] = $request->user()->id;

        try {
            $order = $this->orderService->createOrder($data);
            return response()->json($order, 201);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function show(string $id)
    {
        $order = $this->orderRepository->find($id);
        if (!$order) return response()->json(['message' => 'Order not found'], 404);

        $order->load(['customer', 'items.service', 'payments', 'statusLogs.changedBy', 'delivery']);
        return response()->json($order);
    }

    public function updateStatus(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:pending,washing,drying,ironing,ready,out_for_delivery,delivered,cancelled',
            'notes'  => 'nullable|string',
        ]);

        try {
            $order = $this->orderService->updateOrderStatus(
                $id,
                $request->status,
                $request->user()->id,
                $request->notes
            );

            if (!$order) return response()->json(['message' => 'Order not found'], 404);

            return response()->json($order);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function addPayment(Request $request, string $id)
    {
        $request->validate([
            'amount'    => 'required|numeric|min:0.01',
            'method'    => 'required|in:cash,card,upi,wallet',
            'reference' => 'nullable|string',
        ]);

        try {
            $order = $this->orderService->addPayment(
                $id,
                (float) $request->amount,
                $request->method,
                $request->reference
            );

            return response()->json($order);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
