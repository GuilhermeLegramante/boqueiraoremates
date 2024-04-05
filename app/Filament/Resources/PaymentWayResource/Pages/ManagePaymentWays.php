<?php

namespace App\Filament\Resources\PaymentWayResource\Pages;

use App\Filament\Resources\PaymentWayResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePaymentWays extends ManageRecords
{
    protected static string $resource = PaymentWayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
