<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Order;
use App\Models\User;
use App\Utils\ReportFactory;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function getPdf($id)
    {
        $order = Order::where('id', $id)->get()->first();

        $fileName = 'ORDEM_DE_SERVICO_' . $order->number . '.pdf';

        $netValue = floatval($order->gross_value) - (floatval($order->gross_value) * floatval($order->discount_percentage)) / 100;
        $buyerComissionValue = (floatval($order->gross_value) * floatval($order->buyer_commission)) / 100;
        $sellerComissionValue = (floatval($order->gross_value) * floatval($order->seller_commission)) / 100;

        $searchFromActivityLog = DB::table('activity_log')
            ->where('subject_type', 'App\Models\Order')
            ->where('event', 'Created')
            ->where('subject_id', $id)
            ->select(
                'causer_id AS userId'
            )
            ->get()
            ->first();

        $user = User::where('id', $searchFromActivityLog->userId)->get()->first();

        $args = [
            'order' => $order,
            'title' => 'ORDEM DE SERVIÇO',
            'netValue' => $netValue,
            'buyerComissionValue' => $buyerComissionValue,
            'sellerComissionValue' => $sellerComissionValue,
            'user' => $user,
        ];

        return ReportFactory::getBasicPdf('portrait', 'reports.order', $args, $fileName);
    }
}
