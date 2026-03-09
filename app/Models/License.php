<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    protected $fillable = [
        'branch_id',
        'license_key',
        'valid_from',
        'valid_until',
        'status',
        'machine_id',
    ];

    protected $casts = [
        'valid_from' => 'datetime',
        'valid_until' => 'datetime',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function isValid()
    {
        return $this->status === 'active' &&
               now()->between($this->valid_from, $this->valid_until);
    }
}
