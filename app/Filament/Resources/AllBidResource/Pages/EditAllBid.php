<?php

namespace App\Filament\Resources\AllBidResource\Pages;

use App\Filament\Resources\AllBidResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAllBid extends EditRecord
{
    protected static string $resource = AllBidResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
