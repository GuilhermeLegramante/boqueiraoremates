<?php

namespace App\Filament\Resources\BidResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Bid;

class PendingBidsTotalWidget extends BaseWidget
{
    protected static ?int $sort = -2;
    protected int|string|array $columnSpan = 'full';

    // Atualiza a cada 5 segundos
    protected static ?string $pollingInterval = '5s';

    protected function getStats(): array
    {
        $total = Bid::where('status', 0)->count();

        return [
            Stat::make('Lances Pendentes', $total)
                ->description('Total de lances aguardando aprovação')
                ->color('danger') // vermelho
                ->icon('heroicon-o-exclamation-circle')
                ->extraAttributes([
                    'class' => 'text-3xl font-extrabold', // deixa o número grande e chamativo
                ])
                ->url(route('filament.admin.resources.lances.index')) // link para a listagem do resource
                ->openUrlInNewTab(false),
        ];
    }
}
