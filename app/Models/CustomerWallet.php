<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomerWallet extends Model
{
    protected $fillable = ['customer_id', 'balance', 'total_credited', 'total_debited'];

    protected $casts = [
        'balance'        => 'decimal:2',
        'total_credited' => 'decimal:2',
        'total_debited'  => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class, 'customer_id', 'customer_id');
    }

    public function hasSufficientBalance(float $amount): bool
    {
        return $this->balance >= $amount;
    }
}
