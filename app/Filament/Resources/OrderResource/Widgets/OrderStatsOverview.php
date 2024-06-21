<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Filament\Resources\OrderResource\Pages\ListOrders;
use App\Models\Order;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class OrderStatsOverview extends BaseWidget
{
    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListOrders::class;
    }

    protected function getStats(): array
    {
        $total = $this->getPageTableQuery()->count();

        $totalMonth = $this->getPageTableQuery()->whereDate('base_date', '>=', date('Y') . '-' . date('m') . '-01')->count();

        $comissionValue = $this->getPageTableQuery()
            ->whereDate('base_date', '>=', date('Y') . '-' . date('m') . '-01')
            ->sum(DB::raw('(gross_value * seller_commission) / 100'));

        $avgOS = 0;

        if ($totalMonth > 0) {
            $avgOS = $comissionValue / $totalMonth;
        }

        $avgOS = number_format($avgOS, 2, ',', '.');

        $comissionValue = number_format($comissionValue, 2, ',', '.');


        return [
            Stat::make('Total de Negociações', $totalMonth)
                ->description('No mês'),
            Stat::make('Comissão Vendedor', 'R$ ' . $comissionValue)
                ->description('No mês'),
            Stat::make('Valor Médio', 'R$ ' . $avgOS)
                ->description('Por negociação'),
        ];
    }
}
