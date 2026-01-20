<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Bid extends Model
{
    use HasFactory;

    protected $fillable = [
        'animal_event_id',
        'user_id',
        'event_id',
        'amount',
        'status',
        'approved_by',
    ];

    // Relação com Event
    public function event()
    {
        return $this->belongsTo(\App\Models\Event::class);
    }

    // Relação com User (cliente)
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    // Quem aprovou
    public function approvedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'approved_by');
    }

    // Relação com Animal via pivot
    public function animal()
    {
        return \App\Models\Animal::where('id', function ($query) {
            $query->select('animal_id')
                ->from('animal_event')
                ->whereColumn('id', 'bids.animal_event_id')
                ->limit(1);
        })->first();
    }

    public function animalToFilament(): \Illuminate\Database\Eloquent\Relations\HasOneThrough
    {
        return $this->hasOneThrough(
            \App\Models\Animal::class,    // Modelo final
            \App\Models\AnimalEvent::class, // Pivot intermediário
            'id',       // FK do pivot na Bid (animal_event_id)
            'id',       // FK do animal
            'animal_event_id', // Local Key do Bid
            'animal_id'      // Local Key do pivot
        );
    }

    public function getLotNumberAttribute()
    {
        return \App\Models\AnimalEvent::find($this->animal_event_id)?->lot_number;
    }

    public function getAnimalNameAttribute()
    {
        $animalId = DB::table('animal_event')
            ->where('id', $this->animal_event_id)
            ->value('animal_id');

        return \App\Models\Animal::where('id', $animalId)->value('name');
    }
}
