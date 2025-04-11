<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EarningDiscount extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'client_id',
        'type',
        'amount',
        'description',
    ];

    protected $table = 'earnings_discounts';

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class); // vendedor
    }

    public function isEarning(): bool
    {
        return $this->type === 'earning';
    }

    public function isDiscount(): bool
    {
        return $this->type === 'discount';
    }

    public function getDescriptionAttribute($value)
    {
        return mb_strtoupper($value, 'UTF-8');
    }
}
