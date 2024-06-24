<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\CommissionResource;
use App\Filament\Resources\OrderResource;
use App\Filament\Resources\OrderResource\Widgets\ActiveOrdersChart;
use App\Filament\Resources\OrderResource\Widgets\OrderStatsOverview;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListCommissions extends ListRecords implements HasShieldPermissions
{
    // use ExposesTableToWidgets;

    protected static string $resource = CommissionResource::class;

    protected static ?string $navigationLabel = 'Listar Comissões por OS';

    protected static ?string $title = 'Comissões por OS';

    public static function getPermissionPrefixes(): array
    {
        return [
            'report'
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // OrderStatsOverview::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            // ActiveOrdersChart::class,
        ];
    }
}
