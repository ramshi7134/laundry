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
}
