<?php

namespace App\Filament\Resources\BuyerParcelResource\Pages;

use App\Filament\Resources\BuyerParcelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBuyerParcels extends ListRecords
{
    protected static string $resource = BuyerParcelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
