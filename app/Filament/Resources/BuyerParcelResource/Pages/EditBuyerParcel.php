<?php

namespace App\Filament\Resources\BuyerParcelResource\Pages;

use App\Filament\Resources\BuyerParcelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBuyerParcel extends EditRecord
{
    protected static string $resource = BuyerParcelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
