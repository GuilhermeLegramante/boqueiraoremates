<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AnimalEvent extends Pivot
{
    protected $table = 'animal_event';

    protected $fillable = [
        'animal_id',
        'event_id', // se tiver um relacionamento com outro evento
        'name',
        'situation',
        'lot_number',
        'min_value',
        'increment_value',
        'target_value',
        'final_value',
        'status',
        'photo',
        'photo_full',
        'note',
        'video_link',
        'visible',
    ];

    public $timestamps = true;

    // Relacionamento com Animal
    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }

    // Relacionamento com Event
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
