<?php

namespace App\Filament\Resources\PendingBidResource\Pages;

use App\Filament\Resources\PendingBidResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPendingBids extends ListRecords
{
    protected static string $resource = PendingBidResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
