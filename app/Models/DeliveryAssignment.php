<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryAssignment extends Model
{
    protected $fillable = [
        'order_id', 'delivery_staff_id', 'pickup_address', 'delivery_address',
        'scheduled_pickup_at', 'scheduled_delivery_at', 'actual_pickup_at',
        'actual_delivery_at', 'status', 'notes', 'proof_photo', 'delivery_fee',
    ];

    protected $casts = [
        'scheduled_pickup_at'   => 'datetime',
        'scheduled_delivery_at' => 'datetime',
        'actual_pickup_at'      => 'datetime',
        'actual_delivery_at'    => 'datetime',
        'delivery_fee'          => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delivery_staff_id');
    }

    public function markPickedUp(): void
    {
        $this->update(['status' => 'picked_up', 'actual_pickup_at' => now()]);
    }

    public function markDelivered(): void
    {
        $this->update(['status' => 'delivered', 'actual_delivery_at' => now()]);
    }
}
