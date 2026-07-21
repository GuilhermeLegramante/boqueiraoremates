<?php

namespace App\Filament\Resources\ApprovedActiveBidResource\Pages;

use App\Filament\Resources\ApprovedActiveBidResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListApprovedActiveBids extends ListRecords
{
    protected static string $resource = ApprovedActiveBidResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return session('history_mode')
            ? 'Histórico de Lances'
            : 'Lances Aprovados - Leilões ATIVOS';
    }
}
