<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Order;
use App\Utils\ReportFactory;

class OrderController extends Controller
{
    public function getPdf($id)
    {
        $order = Order::where('id', $id)->get()->first();

        $fileName = 'ORDEM_DE_SERVICO_' . $order->id;

        $netValue = floatval($order->gross_value) - (floatval($order->gross_value) * floatval($order->discount_percentage)) / 100;
        $buyerComissionValue = (floatval($order->gross_value) * floatval($order->buyer_commission)) / 100;
        $sellerComissionValue = (floatval($order->gross_value) * floatval($order->seller_commission)) / 100;


        $args = [
            'order' => $order,
            'title' => 'ORDEM DE SERVIÇO',
            'netValue' => $netValue,
            'buyerComissionValue' => $buyerComissionValue,
            'sellerComissionValue' => $sellerComissionValue,
        ];

        return ReportFactory::getBasicPdf('portrait', 'reports.order', $args, $fileName);
    }
}
