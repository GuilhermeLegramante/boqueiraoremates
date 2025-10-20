<?php

namespace App\Filament\Resources\LegadoDadosAnimalResource\Pages;

use App\Filament\Resources\LegadoDadosAnimalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLegadoDadosAnimal extends EditRecord
{
    protected static string $resource = LegadoDadosAnimalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
