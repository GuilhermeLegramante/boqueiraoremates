<?php

namespace App\Filament\Resources\LegadoClienteResource\Pages;

use App\Filament\Resources\LegadoClienteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLegadoCliente extends EditRecord
{
    protected static string $resource = LegadoClienteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
