<?php

namespace App\Filament\Resources\SendingDocsMethodResource\Pages;

use App\Filament\Resources\SendingDocsMethodResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageSendingDocsMethods extends ManageRecords
{
    protected static string $resource = SendingDocsMethodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
