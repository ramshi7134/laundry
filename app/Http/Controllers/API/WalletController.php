<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CustomerWallet;
use App\Models\WalletTransaction;
use App\Models\LoyaltyPoint;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    // ── Wallet ────────────────────────────────────

    public function walletShow(string $customerId)
    {
        $wallet = CustomerWallet::firstOrCreate(
            ['customer_id' => $customerId],
            ['balance' => 0, 'total_credited' => 0, 'total_debited' => 0]
        );

        return response()->json($wallet->load(['transactions' => fn($q) => $q->latest()->limit(20)]));
    }

    public function walletCredit(Request $request, string $customerId)
    {
        $request->validate([
            'amount'      => 'required|numeric|min:0.01',
            'description' => 'nullable|string',
            'reference'   => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $customerId) {
            $wallet = CustomerWallet::firstOrCreate(
                ['customer_id' => $customerId],
                ['balance' => 0, 'total_credited' => 0, 'total_debited' => 0]
            );

            $amount = (float) $request->amount;
            $wallet->increment('balance', $amount);
            $wallet->increment('total_credited', $amount);

            WalletTransaction::create([
                'customer_id'   => $customerId,
                'order_id'      => null,
                'amount'        => $amount,
                'type'          => 'credit',
                'description'   => $request->description ?? 'Manual top-up',
                'reference'     => $request->reference,
                'balance_after' => $wallet->fresh()->balance,
                'created_by'    => $request->user()->id,
            ]);
        });

        return response()->json(CustomerWallet::where('customer_id', $customerId)->first());
    }

    public function walletTransactions(string $customerId)
    {
        $transactions = WalletTransaction::where('customer_id', $customerId)
            ->with('order:id,order_number')
            ->latest()
            ->paginate(30);

        return response()->json($transactions);
    }

    // ── Loyalty Points ────────────────────────────

    public function loyaltyBalance(string $customerId)
    {
        $balance = (int) LoyaltyPoint::where('customer_id', $customerId)->sum('points');

        return response()->json(['customer_id' => $customerId, 'balance' => $balance]);
    }

    public function loyaltyHistory(string $customerId)
    {
        $history = LoyaltyPoint::where('customer_id', $customerId)
            ->with('order:id,order_number')
            ->latest()
            ->paginate(30);

        return response()->json($history);
    }

    public function loyaltyAdjust(Request $request, string $customerId)
    {
        $request->validate([
            'points'      => 'required|integer|not_in:0',
            'description' => 'nullable|string',
            'reference'   => 'nullable|string',
        ]);

        $currentBalance = (int) LoyaltyPoint::where('customer_id', $customerId)->sum('points');
        $points         = (int) $request->points;
        $newBalance     = $currentBalance + $points;

        if ($newBalance < 0) {
            return response()->json(['message' => 'Insufficient loyalty points'], 400);
        }

        $entry = LoyaltyPoint::create([
            'customer_id'   => $customerId,
            'order_id'      => null,
            'points'        => $points,
            'type'          => $points > 0 ? 'manual_credit' : 'manual_debit',
            'reference'     => $request->reference,
            'description'   => $request->description ?? 'Manual adjustment',
            'balance_after' => $newBalance,
        ]);

        return response()->json(['entry' => $entry, 'new_balance' => $newBalance]);
    }
}
