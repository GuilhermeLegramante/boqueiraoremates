<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventClause extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'title',
        'content',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    /**
     * Relacionamento inverso com Event
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
