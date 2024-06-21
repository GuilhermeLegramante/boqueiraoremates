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

        $sellerCommissionValue = $this->getPageTableQuery()
            ->sum(DB::raw('(gross_value * seller_commission) / 100'));

        $buyerCommissionValue = $this->getPageTableQuery()
            ->sum(DB::raw('(gross_value * buyer_commission) / 100'));

        $commission = $sellerCommissionValue + $buyerCommissionValue;

        $avgOS = 0;

        if ($total > 0) {
            $avgOS = $commission / $total;
        }

        $avgOS = number_format($avgOS, 2, ',', '.');

        $commission = number_format($commission, 2, ',', '.');

        $paidBuyerParcels = $this->getPageTableQuery()
            ->join('buyer_parcels', 'buyer_parcels.order_id', '=', 'orders.id')
            ->where('buyer_parcels.paid', 1)
            ->sum('buyer_parcels.value');

        $paidSellerParcels = $this->getPageTableQuery()
            ->join('seller_parcels', 'seller_parcels.order_id', '=', 'orders.id')
            ->where('seller_parcels.paid', 1)
            ->sum('seller_parcels.value');

        $totalPaid = $paidBuyerParcels + $paidSellerParcels;

        $totalPaid = number_format($totalPaid, 2, ',', '.');

        return [
            Stat::make('Total Comissão', 'R$ ' . $commission)
                ->description('Comprador + Vendedor'),
            Stat::make('Valor Médio', 'R$ ' . $avgOS)
                ->description('Por negociação'),
            Stat::make('Parcelas Pagas', 'R$ ' . $totalPaid)
                ->description('Total'),
        ];
    }
}
