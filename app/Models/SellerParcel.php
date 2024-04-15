<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SellerParcel extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'order_id',
        'date',
        'value',
        'paid',
    ];

    protected $casts = [
        'value' => 'double',
        'paid' => 'boolean',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
