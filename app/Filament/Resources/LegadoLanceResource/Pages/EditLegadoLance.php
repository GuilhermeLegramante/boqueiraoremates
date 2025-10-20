<?php

namespace App\Filament\Resources\LegadoLanceResource\Pages;

use App\Filament\Resources\LegadoLanceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLegadoLance extends EditRecord
{
    protected static string $resource = LegadoLanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
