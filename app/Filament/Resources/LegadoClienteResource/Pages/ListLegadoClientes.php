<?php

namespace App\Filament\Resources\LegadoClienteResource\Pages;

use App\Filament\Resources\LegadoClienteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLegadoClientes extends ListRecords
{
    protected static string $resource = LegadoClienteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
