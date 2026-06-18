<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDetail extends Model
{
    protected $fillable = [
        'user_id',
        'transaction_pin',
        'phone_number',
        'identity_number',
        'address',
        'last_transaction_ip',
        'last_transaction_at',
        'device_fingerprint',
        'security_log',
    ];

    protected function casts(): array
    {
        return [
            'last_transaction_at' => 'datetime',
            'security_log' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
