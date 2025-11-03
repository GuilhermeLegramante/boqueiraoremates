<?php

namespace App\Filament\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasBidFilters
{
    protected function getFilterKey(string $key): string
    {
        // Prefixa com o slug ou nome do resource
        $prefix = static::class; // ex: App\Filament\Resources\ApprovedActiveBidResource
        return "{$prefix}_{$key}";
    }

    public function getFilterValue(string $key)
    {
        return session($this->getFilterKey($key));
    }

    public function setFilterValue(string $key, $value): void
    {
        session([$this->getFilterKey($key) => $value]);
    }

    public function clearFilterValues(): void
    {
        foreach (['event_id', 'lot_id', 'client_id', 'status_id'] as $key) {
            session()->forget($this->getFilterKey($key));
        }
    }

    /**
     * Aplica filtros de sessão à query de lances.
     */
    public function applyBidFilters(Builder $query): Builder
    {
        $eventId = $this->getFilterValue('event_id');
        $lotId = $this->getFilterValue('lot_id');
        $clientId = $this->getFilterValue('client_id');
        $statusId = $this->getFilterValue('status_id');

        if (!$eventId) {
            return $query->whereRaw('1 = 0');
        }

        return $query
            ->where('event_id', $eventId)
            ->when($lotId, fn($q) => $q->where('animal_event_id', $lotId))
            ->when($clientId, fn($q) => $q->where('user_id', $clientId))
            ->when($statusId !== null, fn($q) => $q->where('status', $statusId));
    }
}
