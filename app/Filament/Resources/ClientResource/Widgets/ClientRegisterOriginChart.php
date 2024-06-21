<?php

namespace App\Filament\Resources\ClientResource\Widgets;

use App\Models\Client;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\ChartWidget;

class ClientRegisterOriginChart extends ChartWidget
{
    use HasWidgetShield;
    
    protected static ?string $heading = 'Canal de inclusão';

    protected function getData(): array
    {
        $marketing = Client::where('register_origin', 'marketing')->get()->count();
        $local = Client::where('register_origin', 'local')->get()->count();
        $site = Client::where('register_origin', 'site')->get()->count();

        return [
            'datasets' => [
                [
                    'label' => 'Canal de inclusão',
                    'data' => [$marketing, $local, $site],
                ],
            ],
            'labels' => ['Divulgação', 'Recinto', 'Site'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
