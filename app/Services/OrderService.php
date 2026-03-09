<?php

namespace App\Services;

use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\Interfaces\CustomerRepositoryInterface;
use App\Repositories\Interfaces\ServiceRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderService
{
    protected $orderRepository;
    protected $customerRepository;
    protected $serviceRepository;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        CustomerRepositoryInterface $customerRepository,
        ServiceRepositoryInterface $serviceRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->customerRepository = $customerRepository;
        $this->serviceRepository = $serviceRepository;
    }

    public function createOrder(array $data)
    {
        DB::beginTransaction();
        try {
            $totalAmount = 0;
            
            // Calculate total and prepare items
            $items = [];
            foreach ($data['items'] as $itemData) {
                $service = $this->serviceRepository->find($itemData['service_id']);
                if (!$service) {
                    throw new Exception("Service not found.");
                }
                $itemTotal = $service->price * $itemData['quantity'];
                $totalAmount += $itemTotal;
                
                $items[] = [
                    'service_id' => $service->id,
                    'quantity' => $itemData['quantity'],
                    'price' => $service->price,
                    'total' => $itemTotal,
                ];
            }

            // Create Order
            $order = $this->orderRepository->create([
                'branch_id' => $data['branch_id'],
                'customer_id' => $data['customer_id'],
                'order_number' => $this->generateOrderNumber($data['branch_id']),
                'total_amount' => $totalAmount,
                'paid_amount' => $data['paid_amount'] ?? 0,
                'payment_status' => $this->determinePaymentStatus($totalAmount, $data['paid_amount'] ?? 0),
                'sync_status' => true,
            ]);

            // Create Order Items
            foreach ($items as $item) {
                $order->items()->create($item);
            }

            // Create Payment if any
            if (isset($data['paid_amount']) && $data['paid_amount'] > 0) {
                $order->payments()->create([
                    'amount' => $data['paid_amount'],
                    'method' => $data['payment_method'] ?? 'cash'
                ]);
            }

            DB::commit();
            return $order->load('items.service', 'customer', 'payments');

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function generateOrderNumber($branchId)
    {
        $prefix = "ORD-B{$branchId}-" . date('Ymd') . "-";
        $lastOrder = $this->orderRepository->all()->where('branch_id', $branchId)->last();
        
        $sequence = 1;
        if ($lastOrder && preg_match('/-(\d+)$/', $lastOrder->order_number, $matches)) {
            $sequence = intval($matches[1]) + 1;
        }
        
        return $prefix . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
    
    private function determinePaymentStatus($total, $paid)
    {
        if ($paid >= $total) return 'paid';
        if ($paid > 0) return 'partial';
        return 'pending';
    }
}
