<?php

namespace App\Filament\Resources\PlantaoConfigResource\Pages;

use App\Filament\Resources\PlantaoConfigResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPlantaoConfig extends EditRecord
{
    protected static string $resource = PlantaoConfigResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
