<?php

namespace App\Filament\Resources\ClientResource\Widgets;

use App\Models\Client;
use Filament\Widgets\ChartWidget;

class ClientSituationChart extends ChartWidget
{
    protected static ?string $heading = 'Clientes por Situação';

    protected function getData(): array
    {
        $able = Client::where('situation', 'able')->get()->count();
        $disabled = Client::where('situation', 'disabled')->get()->count();
        $inactive = Client::where('situation', 'inactive')->get()->count();

        return [
            'datasets' => [
                [
                    'label' => 'Clientes por situação',
                    'data' => [$able, $disabled, $inactive],
                ],
            ],
            'labels' => ['Habilitado', 'Inabilitado', 'Inativo'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
