<?php

namespace App\Filament\Resources\SalesMapResource\Pages;

use App\Filament\Resources\SalesMapResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSalesMaps extends ListRecords
{
    protected static string $resource = SalesMapResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
