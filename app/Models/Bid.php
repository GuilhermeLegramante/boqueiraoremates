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
        // 1. Busca o lote atual que recebeu o lance
        $currentLot = AnimalEvent::find($bid->animal_event_id);
        if (!$currentLot) return;

        // 2. Descobre o ID do lote parceiro de forma inteligente:
        // Se houver vínculo direto, pega o 'linked_animal_event_id'
        // Senão, procura se algum lote aponta para o lote atual (vínculo inverso)
        $targetLotId = $currentLot->linked_animal_event_id
            ?? AnimalEvent::where('linked_animal_event_id', $currentLot->id)->value('id');

        // Se não houver nenhum parceiro em nenhum dos sentidos, interrompe
        if (!$targetLotId) return;

        // 3. Trava de segurança contra loop infinito
        $alreadyReplicated = static::where('animal_event_id', $targetLotId)
            ->where('amount', $bid->amount)
            ->where('user_id', $bid->user_id)
            ->where('event_id', $bid->event_id)
            ->exists();

        if (!$alreadyReplicated) {
            // 4. Cria o lance espelho no lote parceiro
            static::create([
                'animal_event_id' => $targetLotId,
                'event_id'        => $bid->event_id,
                'user_id'         => $bid->user_id,
                'amount'          => $bid->amount,
                'status'          => $bid->status, // Mantém o status original (site = false, admin = true)
            ]);
        }
    }

    protected static function syncStatusToLinkedLot($bid)
    {
        $currentLot = AnimalEvent::find($bid->animal_event_id);
        if (!$currentLot) return;

        // Descobre o ID do lote parceiro no mesmo esquema bidirecional
        $targetLotId = $currentLot->linked_animal_event_id
            ?? AnimalEvent::where('linked_animal_event_id', $currentLot->id)->value('id');

        if (!$targetLotId) return;

        // Atualiza o status do lance correspondente no lote parceiro
        static::where('animal_event_id', $targetLotId)
            ->where('amount', $bid->amount)
            ->where('user_id', $bid->user_id)
            ->where('event_id', $bid->event_id)
            ->update(['status' => $bid->status]);
    }
}
