<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['branch_id', 'name', 'description', 'price', 'turnaround_hours', 'is_active'];

    protected $casts = [
        'price'            => 'decimal:2',
        'turnaround_hours' => 'integer',
        'is_active'        => 'boolean',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
