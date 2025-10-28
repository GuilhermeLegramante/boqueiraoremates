<?php

namespace App\Filament\Resources\ApprovedInactiveBidResource\Pages;

use App\Filament\Resources\ApprovedInactiveBidResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListApprovedInactiveBids extends ListRecords
{
    protected static string $resource = ApprovedInactiveBidResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
