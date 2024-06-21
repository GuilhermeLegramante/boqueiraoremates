<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Filament\Resources\OrderResource\Pages\ListOrders;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageTable;

class ActiveOrdersChart extends ChartWidget
{
    use InteractsWithPageTable;

    protected static ?string $heading = 'Status';

    protected function getTablePage(): string
    {
        return ListOrders::class;
    }

    protected function getData(): array
    {
        $opened = $this->getPageTableQuery()
            ->join('order_statuses', 'order_statuses.id', '=', 'orders.order_status_id')
            ->where('order_statuses.name', 'like', '%ABERTA%')
            ->count();

        $closed = $this->getPageTableQuery()
            ->join('order_statuses', 'order_statuses.id', '=', 'orders.order_status_id')
            ->where('order_statuses.name', 'like', '%ENCERRADA%')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Status da OS',
                    'data' => [$opened, $closed],
                ],
            ],
            'labels' => ['ABERTA', 'ENCERRADA'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
