<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Filament\Traits\WithParcels;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewOrder extends ViewRecord
{
    use WithParcels;

    protected static string $resource = OrderResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // $data['net_value'] = floatval($data['gross_value']) - (floatval($data['gross_value']) * floatval($data['discount_percentage'])) / 100;

        // $data['buyer_comission_value'] = (floatval($data['gross_value']) * floatval($data['buyer_commission'])) / 100;

        // $data['seller_comission_value'] = (floatval($data['gross_value']) * floatval($data['seller_commission'])) / 100;

        // dd($data);

        return $data;
    }
}
