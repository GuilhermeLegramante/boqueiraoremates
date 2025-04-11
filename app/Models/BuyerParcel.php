<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BuyerParcel extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'order_id',
        'payment_method_id',
        'payment_date',
        'date',
        'value',
        'paid',
        'note',
        'map_note',
    ];

    protected $casts = [
        'value' => 'double',
        'paid' => 'boolean',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function getNoteAttribute($value)
    {
        return mb_strtoupper($value, 'UTF-8');
    }

    public function getMapNoteAttribute($value)
    {
        return mb_strtoupper($value, 'UTF-8');
    }
}
