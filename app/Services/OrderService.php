<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderStatusLog;
use App\Models\CustomerWallet;
use App\Models\WalletTransaction;
use App\Models\LoyaltyPoint;
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
                    throw new Exception("Service ID {$itemData['service_id']} not found.");
                }
                $unitPrice  = isset($itemData['unit_price']) ? (float) $itemData['unit_price'] : (float) $service->price;
                $itemTotal  = $unitPrice * (int) $itemData['quantity'];
                $totalAmount += $itemTotal;

                $items[] = [
                    'service_id' => $service->id,
                    'quantity'   => $itemData['quantity'],
                    'price'      => $unitPrice,
                    'total'      => $itemTotal,
                ];
            }

            // Resolve discount
            $discountAmount    = 0.0;
            $discountType      = null;
            $discountReference = null;

            if (!empty($data['discount_type'])) {
                $discountType = $data['discount_type'];
                $discountReference = $data['discount_reference'] ?? null;

                if ($discountType === 'flat') {
                    $discountAmount = min((float) ($data['discount_value'] ?? 0), $totalAmount);
                } elseif ($discountType === 'percent') {
                    $pct            = min(100, max(0, (float) ($data['discount_value'] ?? 0)));
                    $discountAmount = round($totalAmount * $pct / 100, 2);
                } elseif ($discountType === 'loyalty') {
                    // Redeem loyalty points (1 point = 0.01 currency unit by default)
                    $pointsToRedeem   = (int) ($data['loyalty_points_redeem'] ?? 0);
                    $customer         = $this->customerRepository->find($data['customer_id']);
                    $availableBalance = $customer ? (int) $customer->loyaltyPoints()->sum('points') : 0;
                    $pointsToRedeem   = min($pointsToRedeem, $availableBalance);
                    $discountAmount   = round($pointsToRedeem * 0.01, 2);
                    $discountReference = "loyalty:{$pointsToRedeem}pts";
                } elseif ($discountType === 'wallet') {
                    // Deduct from wallet — handled below after order creation
                }
            }

            $netAmount  = max(0, $totalAmount - $discountAmount);
            $paidAmount = (float) ($data['paid_amount'] ?? 0);

            // Build payments from split-payment array or single amount
            $paymentsData = $this->resolvePayments($data, $paidAmount);
            $totalPaid    = array_sum(array_column($paymentsData, 'amount'));

            // Create Order
            $order = $this->orderRepository->create([
                'branch_id'          => $data['branch_id'],
                'customer_id'        => $data['customer_id'],
                'order_number'       => $this->generateOrderNumber($data['branch_id']),
                'total_amount'       => $totalAmount,
                'discount_amount'    => $discountAmount,
                'discount_type'      => $discountType,
                'discount_reference' => $discountReference,
                'paid_amount'        => $totalPaid,
                'payment_status'     => $this->determinePaymentStatus($netAmount, $totalPaid),
                'notes'              => $data['notes'] ?? null,
                'sync_status'        => true,
            ]);

            // Create Order Items
            foreach ($items as $item) {
                $order->items()->create($item);
            }

            // Create Payment records
            foreach ($paymentsData as $payment) {
                if ($payment['amount'] > 0) {
                    $order->payments()->create($payment);
                }
            }

            // Wallet deduction (discount_type = wallet)
            if ($discountType === 'wallet') {
                $walletDeduct = min((float) ($data['discount_value'] ?? 0), $netAmount);
                $this->deductFromWallet($data['customer_id'], $walletDeduct, $order->id, 'Order discount via wallet');
                $order->update([
                    'discount_amount' => $walletDeduct,
                    'discount_reference' => 'wallet',
                ]);
            }

            // Loyalty point deduction
            if ($discountType === 'loyalty' && isset($pointsToRedeem) && $pointsToRedeem > 0) {
                LoyaltyPoint::create([
                    'customer_id'   => $data['customer_id'],
                    'order_id'      => $order->id,
                    'points'        => -$pointsToRedeem,
                    'type'          => 'redeem',
                    'reference'     => $order->order_number,
                    'description'   => 'Redeemed on order',
                    'balance_after' => max(0, $availableBalance - $pointsToRedeem),
                ]);
            }

            // Earn loyalty points (1 point per 10 currency units spent)
            $earnedPoints = (int) floor($netAmount / 10);
            if ($earnedPoints > 0) {
                $currentBalance = $order->customer->loyaltyPoints()->sum('points');
                LoyaltyPoint::create([
                    'customer_id'   => $data['customer_id'],
                    'order_id'      => $order->id,
                    'points'        => $earnedPoints,
                    'type'          => 'earn',
                    'reference'     => $order->order_number,
                    'description'   => "Earned on order {$order->order_number}",
                    'balance_after' => $currentBalance + $earnedPoints,
                ]);
            }

            // Initial status log
            OrderStatusLog::create([
                'order_id'        => $order->id,
                'status'          => $order->status ?? 'pending',
                'previous_status' => null,
                'changed_by'      => $data['created_by'] ?? null,
                'notes'           => 'Order created',
            ]);

            DB::commit();
            return $order->fresh()->load('items.service', 'customer', 'payments', 'statusLogs');

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateOrderStatus(string $orderId, string $newStatus, ?int $changedBy = null, ?string $notes = null): ?Order
    {
        $order = $this->orderRepository->find($orderId);
        if (!$order) return null;

        $previousStatus = $order->status;

        DB::transaction(function () use ($order, $newStatus, $previousStatus, $changedBy, $notes) {
            $order->update(['status' => $newStatus]);

            OrderStatusLog::create([
                'order_id'        => $order->id,
                'status'          => $newStatus,
                'previous_status' => $previousStatus,
                'changed_by'      => $changedBy,
                'notes'           => $notes,
            ]);
        });

        return $order->fresh()->load('statusLogs');
    }

    public function addPayment(string $orderId, float $amount, string $method, ?string $reference = null): Order
    {
        $order = $this->orderRepository->find($orderId);
        if (!$order) throw new Exception('Order not found.');
        if ($amount <= 0)  throw new Exception('Payment amount must be greater than zero.');

        DB::transaction(function () use ($order, $amount, $method, $reference) {
            $order->payments()->create([
                'amount'    => $amount,
                'method'    => $method,
                'reference' => $reference,
            ]);

            $totalPaid = $order->payments()->sum('amount');
            $netAmount = $order->net_amount;

            $order->update([
                'paid_amount'    => $totalPaid,
                'payment_status' => $this->determinePaymentStatus($netAmount, $totalPaid),
            ]);
        });

        return $order->fresh()->load('payments');
    }

    // ──────────────────────────────────────────
    // Private helpers
    // ──────────────────────────────────────────

    private function resolvePayments(array $data, float $fallbackPaid): array
    {
        // Support split-payment: payments = [{amount, method, reference?}, ...]
        if (!empty($data['payments']) && is_array($data['payments'])) {
            return array_map(fn($p) => [
                'amount'    => (float) ($p['amount'] ?? 0),
                'method'    => $p['method'] ?? 'cash',
                'reference' => $p['reference'] ?? null,
            ], $data['payments']);
        }

        if ($fallbackPaid > 0) {
            return [[
                'amount'    => $fallbackPaid,
                'method'    => $data['payment_method'] ?? 'cash',
                'reference' => $data['payment_reference'] ?? null,
            ]];
        }

        return [];
    }

    private function deductFromWallet(int $customerId, float $amount, int $orderId, string $description): void
    {
        $wallet = CustomerWallet::firstOrCreate(
            ['customer_id' => $customerId],
            ['balance' => 0, 'total_credited' => 0, 'total_debited' => 0]
        );

        if (!$wallet->hasSufficientBalance($amount)) {
            throw new Exception('Insufficient wallet balance.');
        }

        $wallet->decrement('balance', $amount);
        $wallet->increment('total_debited', $amount);

        WalletTransaction::create([
            'customer_id'   => $customerId,
            'order_id'      => $orderId,
            'amount'        => $amount,
            'type'          => 'debit',
            'description'   => $description,
            'balance_after' => $wallet->fresh()->balance,
        ]);
    }

    private function generateOrderNumber(int $branchId): string
    {
        $prefix    = "ORD-B{$branchId}-" . date('Ymd') . "-";
        $lastOrder = Order::where('branch_id', $branchId)
                          ->whereDate('created_at', today())
                          ->orderByDesc('id')
                          ->first();

        $sequence = 1;
        if ($lastOrder && preg_match('/-(\d+)$/', $lastOrder->order_number, $matches)) {
            $sequence = (int) $matches[1] + 1;
        }

        return $prefix . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    private function determinePaymentStatus(float $total, float $paid): string
    {
        if ($paid >= $total) return 'paid';
        if ($paid > 0)       return 'partial';
        return 'unpaid';
    }
}

