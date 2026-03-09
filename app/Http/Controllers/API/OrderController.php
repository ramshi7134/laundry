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
        $this->orderService = $orderService;
        $this->orderRepository = $orderRepository;
    }

    public function index(Request $request)
    {
        $branchId = $request->user()->branch_id;
        $orders = $this->orderRepository->getBranchOrders($branchId);
        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.service_id' => 'required|exists:services,id',
            'items.*.quantity' => 'required|integer|min:1',
            'paid_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|in:cash,card,upi',
        ]);

        $data = $request->all();
        $data['branch_id'] = $request->user()->branch_id;

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
        if (!$order) return response()->json(['message' => 'Not found'], 404);
        
        $order->load(['customer', 'items.service', 'payments']);
        return response()->json($order);
    }

    public function updateStatus(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:pending,washing,drying,ironing,ready,delivered',
        ]);

        $order = $this->orderRepository->updateStatus($id, $request->status);
        if (!$order) return response()->json(['message' => 'Not found'], 404);

        return response()->json($order);
    }
}
