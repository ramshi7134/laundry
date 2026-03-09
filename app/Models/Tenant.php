<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'domain', 'email', 'phone',
        'logo', 'plan', 'status', 'settings', 'trial_ends_at',
    ];

    protected $casts = [
        'settings'      => 'array',
        'trial_ends_at' => 'datetime',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription()
    {
        return $this->subscriptions()->where('status', 'active')->latest()->first();
    }

    public function isOnTrial(): bool
    {
        return $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }
}
