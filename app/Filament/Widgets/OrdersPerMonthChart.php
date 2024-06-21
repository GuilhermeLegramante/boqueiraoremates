<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class OrdersPerMonthChart extends ChartWidget
{
    protected static ?string $heading = 'Ordens de Serviço';

    protected function getData(): array
    {
        $currentYear = date('Y');

        // Obtenha o valor total dos pedidos do ano atual agrupados por mês
        $ordersPerMonth = Order::selectRaw('MONTH(base_date) as month, COUNT(*) as total')
            ->whereYear('base_date', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');


        // Crie uma coleção de todos os meses do ano atual com total inicial de 0
        $ordersPerMonth = collect(range(1, 12))->map(function ($month) use ($ordersPerMonth) {
            return $ordersPerMonth->has($month) ? $ordersPerMonth->get($month)->total : 0;
        });

        $orders = $ordersPerMonth->toArray();

        $months = [
            'Jan' => $orders[0] ?? 0,
            'Fev' => $orders[1] ?? 0,
            'Mar' => $orders[2] ?? 0,
            'Abr' => $orders[3] ?? 0,
            'Mai' => $orders[4] ?? 0,
            'Jun' => $orders[5] ?? 0,
            'Jul' => $orders[6] ?? 0,
            'Ago' => $orders[7] ?? 0,
            'Set' => $orders[8] ?? 0,
            'Out' => $orders[9] ?? 0,
            'Nov' => $orders[10] ?? 0,
            'Dez' => $orders[11] ?? 0,
        ];

        return [
            'datasets' => [
                [
                    'label' => 'total por mês no ano',
                    'data' => $months,
                ],
            ],
            'labels' => array_keys($months),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
