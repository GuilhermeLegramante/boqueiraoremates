<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Filament\Resources\OrderResource\Pages\ListOrders;
use App\Models\Order;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class OrderStatsOverview extends BaseWidget
{
    use InteractsWithPageTable, HasWidgetShield;

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

        $totalComissionDescription = 'C: R$' .
            number_format($buyerCommissionValue, 2, ',', '.') .
            ' V: R$' . number_format($sellerCommissionValue, 2, ',', '.');


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

        $noPaidBuyerParcels = $this->getPageTableQuery()
            ->join('buyer_parcels', 'buyer_parcels.order_id', '=', 'orders.id')
            ->where('buyer_parcels.paid', 0)
            ->sum('buyer_parcels.value');

        $noPaidSellerParcels = $this->getPageTableQuery()
            ->join('seller_parcels', 'seller_parcels.order_id', '=', 'orders.id')
            ->where('seller_parcels.paid', 0)
            ->sum('seller_parcels.value');

        $totalPaid = $paidBuyerParcels + $paidSellerParcels;
        $totalPaid = number_format($totalPaid, 2, ',', '.');

        $totalNoPaid = $noPaidBuyerParcels + $noPaidSellerParcels;
        $totalNoPaid = number_format($totalNoPaid, 2, ',', '.');

        return [
            Stat::make('Total Comissão', 'R$ ' . $commission)
                ->description($totalComissionDescription),
            Stat::make('Valor Médio', 'R$ ' . $avgOS)
                ->description('Por negociação'),
            Stat::make('Parcelas Pagas', 'R$ ' . $totalPaid)
                ->description('Em aberto: R$' . $totalNoPaid),
        ];
    }
}
