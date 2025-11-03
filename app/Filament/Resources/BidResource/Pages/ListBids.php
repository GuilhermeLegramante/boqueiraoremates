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

        // Limpa filtros apenas se ainda não foi feito nesta página
        if (!session()->has('bids_filters_initialized')) {
            session()->forget([
                'selected_event_id',
                'selected_lot_id',
                'selected_client_id',
                'selected_status_id',
            ]);

            // Marca que a inicialização já ocorreu
            session(['bids_filters_initialized' => true]);
        }
    }
}
