<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SyncQueue extends Model
{
    protected $table = 'sync_queue';

    protected $fillable = [
        'model_type', 'model_id', 'action', 'payload',
        'status', 'attempts', 'error_message', 'synced_at',
    ];

    protected $casts = [
        'payload'   => 'array',
        'synced_at' => 'datetime',
    ];

    public function scopePending($query)
    {
        return $query->where('status', 'pending')->where('attempts', '<', 5);
    }

    public function markSynced(): void
    {
        $this->update(['status' => 'synced', 'synced_at' => now()]);
    }

    public function markFailed(string $error): void
    {
        $this->increment('attempts');
        $this->update(['status' => 'failed', 'error_message' => $error]);
    }
}
