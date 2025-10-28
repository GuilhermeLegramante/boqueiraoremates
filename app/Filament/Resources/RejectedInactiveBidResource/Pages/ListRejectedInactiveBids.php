<?php

namespace App\Filament\Resources\RejectedInactiveBidResource\Pages;

use App\Filament\Resources\RejectedInactiveBidResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRejectedInactiveBids extends ListRecords
{
    protected static string $resource = RejectedInactiveBidResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
