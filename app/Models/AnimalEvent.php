<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AnimalEvent extends Pivot
{
    protected $table = 'animal_event';

    protected $fillable = [
        'animal_id',
        'event_id',
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
        'linked_animal_event_id',
        'parcels_quantity',
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

    // Relacionamento com o lote vinculado (Múltipla escolha)
    public function linkedLot()
    {
        return $this->belongsTo(AnimalEvent::class, 'linked_animal_event_id');
    }

    // Relacionamento com o lote que foi vinculado a este (Pai / Inverso)
    public function parentLot()
    {
        return $this->hasOne(AnimalEvent::class, 'linked_animal_event_id', 'id');
    }
}
