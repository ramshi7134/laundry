<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    protected $fillable = [
        'branch_id', 'amount', 'category', 'description',
        'reference', 'date', 'created_by', 'receipt_photo',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date'   => 'date',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function categories(): array
    {
        return ['rent', 'salary', 'supplies', 'utilities', 'maintenance', 'other'];
    }
}
