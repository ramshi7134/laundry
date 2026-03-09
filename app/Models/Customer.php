<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['branch_id', 'name', 'phone', 'email', 'address'];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function wallet()
    {
        return $this->hasOne(CustomerWallet::class);
    }

    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function loyaltyPoints()
    {
        return $this->hasMany(LoyaltyPoint::class);
    }

    public function getLoyaltyBalanceAttribute(): int
    {
        return (int) $this->loyaltyPoints()->sum('points');
    }

    public function getWalletBalanceAttribute(): float
    {
        return (float) optional($this->wallet)->balance ?? 0.0;
    }
}
