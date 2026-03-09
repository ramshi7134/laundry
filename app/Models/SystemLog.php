<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemLog extends Model
{
    public $timestamps = false;

    protected $fillable = ['level', 'message', 'source', 'context', 'trace', 'user_id'];

    protected $casts = [
        'context'    => 'array',
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function error(string $message, array $context = [], string $source = 'system'): void
    {
        static::create(['level' => 'error', 'message' => $message, 'source' => $source, 'context' => $context]);
    }

    public static function info(string $message, array $context = [], string $source = 'system'): void
    {
        static::create(['level' => 'info', 'message' => $message, 'source' => $source, 'context' => $context]);
    }
}
