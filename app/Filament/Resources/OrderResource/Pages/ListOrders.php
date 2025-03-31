<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Filament\Resources\OrderResource\Widgets\ActiveOrdersChart;
use App\Filament\Resources\OrderResource\Widgets\OrderStatsOverview;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = OrderResource::class;

    protected static ?string $navigationLabel = 'Listar Faturas de Venda / OS';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
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
