<?php

namespace App\Filament\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasBidFilters
{
    /**
     * Aplica filtros de sessão à query de lances.
     */
    public function applyBidFilters(Builder $query): Builder
    {
        $eventId = session('selected_event_id');
        $lotId = session('selected_lot_id');
        $clientId = session('selected_client_id');
        $statusId = session('selected_status_id');

        // Nenhum evento selecionado → nenhum lance
        if (!$eventId) {
            return $query->whereRaw('1 = 0');
        }

        // Aplica filtros dinâmicos
        $query->where('event_id', $eventId)
            ->when($lotId, fn($q) => $q->where('animal_event_id', $lotId))
            ->when($clientId, fn($q) => $q->where('user_id', $clientId))
            ->when($statusId !== null, fn($q) => $q->where('status', $statusId));

        return $query;
    }
}
