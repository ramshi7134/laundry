<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'branch_id',
        'customer_id',
        'order_number',
        'status',
        'total_amount',
        'discount_amount',
        'discount_type',
        'discount_reference',
        'paid_amount',
        'payment_status',
        'notes',
        'sync_status',
    ];

    protected $casts = [
        'total_amount'    => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'paid_amount'     => 'decimal:2',
        'sync_status'     => 'boolean',
    ];

    public function getNetAmountAttribute(): float
    {
        return max(0, (float) $this->total_amount - (float) $this->discount_amount);
    }

    public function getDueAmountAttribute(): float
    {
        return max(0, $this->net_amount - (float) $this->paid_amount);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function statusLogs()
    {
        return $this->hasMany(OrderStatusLog::class);
    }

    public function delivery()
    {
        return $this->hasOne(DeliveryAssignment::class);
    }
}
