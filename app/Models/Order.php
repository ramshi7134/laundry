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
        'paid_amount',
        'payment_status',
        'sync_status',
    ];

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
}
