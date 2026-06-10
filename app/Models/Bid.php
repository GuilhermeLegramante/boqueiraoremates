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

    protected static function booted()
    {
        static::created(function ($bid) {
            // Dispara para QUALQUER lance criado, mantendo o status original
            // Seja false (Controller) ou true/1 (Filament Admin)
            static::replicateBidToLinkedLot($bid);
        });

        static::updated(function ($bid) {
            // Se o status mudar (ex: Admin aprovou um lance que veio do site)
            // atualizamos o status do lote espelho também
            if ($bid->wasChanged('status')) {
                static::syncStatusToLinkedLot($bid);
            }
        });
    }

    protected static function replicateBidToLinkedLot($bid)
    {
        // 1. Busca o lote (AnimalEvent) que recebeu o lance original
        $currentLot = AnimalEvent::find($bid->animal_event_id);

        if (!$currentLot || !$currentLot->linked_animal_event_id) {
            return;
        }

        // 2. Trava de segurança para não duplicar e evitar loop infinito
        $alreadyReplicated = static::where('animal_event_id', $currentLot->linked_animal_event_id)
            ->where('amount', $bid->amount)
            ->where('user_id', $bid->user_id)
            ->where('event_id', $bid->event_id) // 🔹 Adicionado para consistência
            ->exists();

        if (!$alreadyReplicated) {
            // 3. Cria o lance espelhado no lote vinculado idêntico ao original
            static::create([
                'animal_event_id' => $currentLot->linked_animal_event_id,
                'event_id'        => $bid->event_id,  // 🔹 Preenche o id do evento vindo da request/admin
                'user_id'         => $bid->user_id,
                'amount'          => $bid->amount,
                'status'          => $bid->status,    // 🔹 Copia fielmente o status (false do site ou true do Admin)
            ]);
        }
    }

    protected static function syncStatusToLinkedLot($bid)
    {
        $currentLot = AnimalEvent::find($bid->animal_event_id);

        if (!$currentLot || !$currentLot->linked_animal_event_id) {
            return;
        }

        // Se o Admin aprovar ou rejeitar o lance pai no Filament,
        // o lance do lote vinculado segue o mesmo destino
        static::where('animal_event_id', $currentLot->linked_animal_event_id)
            ->where('amount', $bid->amount)
            ->where('user_id', $bid->user_id)
            ->where('event_id', $bid->event_id)
            ->update(['status' => $bid->status]);
    }
}
