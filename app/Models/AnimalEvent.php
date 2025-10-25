<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Model;

class AnimalEvent extends Model
{
    // Se a tabela se chamar diferente do padrão (animal_event)
    protected $table = 'animal_event';

    // Preenchíveis
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
    ];

    // Relacionamento com Animal
    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }

    // Se houver uma tabela de eventos
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
