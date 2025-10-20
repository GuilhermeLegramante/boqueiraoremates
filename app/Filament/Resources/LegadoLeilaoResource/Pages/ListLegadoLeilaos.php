<?php

namespace App\Filament\Resources\LegadoLeilaoResource\Pages;

use App\Filament\Resources\LegadoLeilaoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLegadoLeilaos extends ListRecords
{
    protected static string $resource = LegadoLeilaoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
