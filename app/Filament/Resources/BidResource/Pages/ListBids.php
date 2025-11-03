<?php

namespace App\Filament\Resources\BidResource\Pages;

use App\Filament\Resources\BidResource;
use App\Filament\Resources\PendingBidResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBids extends ListRecords
{
    protected static string $resource = BidResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    /**
     * Executa ao carregar a página
     */
    public function mount(): void
    {
        parent::mount();

        // Limpa os filtros da sessão
        session()->forget([
            'selected_event_id',
            'selected_lot_id',
            'selected_client_id',
            'selected_status_id',
        ]);
    }
}
