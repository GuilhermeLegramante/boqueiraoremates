<?php

namespace App\Filament\Resources\ClientResource\Widgets;

use App\Models\Client;
use App\Models\Document;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    use HasWidgetShield;
    
    protected static ?string $heading = 'Clientes Resumo';

    protected function getStats(): array
    {
        $total = Client::all()->count();

        $able = Client::where('situation', 'able')->get()->count();

        if($total > 0){
            $percentualAble = ($able * 100) / $total;
        } else {
            $percentualAble = 0;
        }

        $documents = Document::all()->count();

        return [
            Stat::make('Total de Clientes', $total),
            Stat::make('Clientes Habilitados', number_format($percentualAble, 2) . '%'),
            Stat::make('Total de Documentos', $documents),
        ];
    }
}
