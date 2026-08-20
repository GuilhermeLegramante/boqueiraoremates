<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'generated_by',
        'status',
        'version',
        'snapshot',
        'generated_at',
        'cancelled_at',
    ];

    protected $casts = [
        'snapshot' => 'array',
        'generated_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function generatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function isGenerated(): bool
    {
        return $this->status === 'generated';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }
}