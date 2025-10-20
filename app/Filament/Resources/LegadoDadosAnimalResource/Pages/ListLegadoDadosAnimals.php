<?php

namespace App\Filament\Resources\LegadoDadosAnimalResource\Pages;

use App\Filament\Resources\LegadoDadosAnimalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLegadoDadosAnimals extends ListRecords
{
    protected static string $resource = LegadoDadosAnimalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
