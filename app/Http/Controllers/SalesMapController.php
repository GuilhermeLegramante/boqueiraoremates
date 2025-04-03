<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use App\Utils\ReportFactory;
use Illuminate\Http\Request;

class SalesMapController extends Controller
{
    public function getPdf(Request $request)
    {
        $sellerId = $request->query('seller');
        $eventId = $request->query('record');

        $event = Event::find($eventId);

        // Consulta base de pedidos com filtros condicionais
        $ordersQuery = Order::where('event_id', $eventId)
            ->with(['animal', 'seller', 'seller.address'])
            ->selectRaw('orders.*, SUM(gross_value) OVER() as total_gross_value')
            ->orderByRaw("batch IS NULL, batch");

        // Se houver um vendedor selecionado, filtra os pedidos pelo vendedor
        if ($sellerId) {
            $ordersQuery->whereHas('seller', function ($query) use ($sellerId) {
                $query->where('orders.seller_id', $sellerId);
            });
        }

        $orders = $ordersQuery->get();

        // Contagem total de pedidos (aplicando filtro do vendedor, se houver)
        $totalOrders = (clone $ordersQuery)->count();

        // Contagem de pedidos com venda confirmada (excluindo 'SEM VENDA')
        $totalSaleOrders = (clone $ordersQuery)
            ->whereHas('buyer', function ($query) {
                $query->where('name', '!=', 'SEM VENDA');
            })->count();

        // Receita média de animais machos
        $avgMaleRevenue = (clone $ordersQuery)
            ->whereIn('animal_id', function ($query) {
                $query->select('id')->from('animals')->where('gender', 'male');
            })
            ->whereHas('buyer', function ($query) {
                $query->where('name', '!=', 'SEM VENDA');
            })
            ->avg('gross_value') ?? 0.00;

        // Receita média de animais fêmeas
        $avgFemaleRevenue = (clone $ordersQuery)
            ->whereIn('animal_id', function ($query) {
                $query->select('id')->from('animals')->where('gender', 'female');
            })
            ->whereHas('buyer', function ($query) {
                $query->where('name', '!=', 'SEM VENDA');
            })
            ->avg('gross_value') ?? 0.00;

        // Receita média por lote
        $avgRevenuePerBatch = (clone $ordersQuery)
            ->whereHas('buyer', function ($query) {
                $query->where('name', '!=', 'SEM VENDA');
            })
            ->avg('gross_value') ?? 0.00;

        // Receita total
        $totalRevenue = (clone $ordersQuery)->sum('gross_value') ?? 0.00;

        $name = strtoupper('MAPA DE VENDAS  - ' . $event->name);
        $fileName = $name . '.pdf';

        $args = [
            'title' => $name,
            'orders' => $orders,
            'totalOrders' => $totalOrders,
            'totalSaleOrders' => $totalSaleOrders,
            'avgMaleRevenue' => $avgMaleRevenue,
            'avgFemaleRevenue' => $avgFemaleRevenue,
            'totalRevenue' => $totalRevenue,
            'avgRevenuePerBatch' => $avgRevenuePerBatch,
        ];

        return ReportFactory::getBasicPdf('landscape', 'reports.sales-map', $args, $fileName);
    }
}
