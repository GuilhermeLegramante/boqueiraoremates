<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class CommissionPerMonthChart extends ChartWidget
{
    protected static ?string $heading = 'Faturamento (Comissões)';

    protected function getData(): array
    {
        $currentYear = date('Y');
        $months = [];

        // Array associativo com abreviações dos meses em português
        $abbreviations = [
            1 => 'Jan', 2 => 'Fev', 3 => 'Mar', 4 => 'Abr',
            5 => 'Mai', 6 => 'Jun', 7 => 'Jul', 8 => 'Ago',
            9 => 'Set', 10 => 'Out', 11 => 'Nov', 12 => 'Dez'
        ];

        for ($mes = 1; $mes <= 12; $mes++) {
            $value = Order::join('buyer_parcels', 'buyer_parcels.order_id', '=', 'orders.id')
                ->whereDate('base_date', '>=', "$currentYear-" . str_pad($mes, 2, '0', STR_PAD_LEFT) . '-01')
                ->whereDate('base_date', '<', "$currentYear-" . str_pad($mes + 1, 2, '0', STR_PAD_LEFT) . '-01')
                ->sum('buyer_parcels.value');

            // Obtém a abreviatura do mês em português
            $monthName = $abbreviations[$mes];

            // Armazena no array $months
            $months[$monthName] = $value;
        }

        return [
            'datasets' => [
                [
                    'label' => 'total por mês no ano em R$',
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
