<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryItem extends Model
{
    protected $fillable = [
        'branch_id', 'name', 'sku', 'quantity', 'unit',
        'minimum_level', 'cost_per_unit', 'alert_sent', 'is_active',
    ];

    protected $casts = [
        'quantity'      => 'decimal:3',
        'minimum_level' => 'decimal:3',
        'cost_per_unit' => 'decimal:2',
        'alert_sent'    => 'boolean',
        'is_active'     => 'boolean',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(InventoryTransaction::class, 'item_id');
    }

    public function isLowStock(): bool
    {
        return $this->quantity <= $this->minimum_level;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLowStock($query)
    {
        return $query->whereRaw('quantity <= minimum_level');
    }
}
