<?php

namespace App\Filament\Resources\RejectedActiveBidResource\Pages;

use App\Filament\Resources\RejectedActiveBidResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRejectedActiveBids extends ListRecords
{
    protected static string $resource = RejectedActiveBidResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
