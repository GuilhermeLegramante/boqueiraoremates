<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\EarningDiscount;
use App\Models\Event;
use App\Models\Order;
use App\Utils\ReportFactory;
use Illuminate\Http\Request;

class SellerStatementController extends Controller
{
    public function getPdf(Request $request)
    {
        $event = Event::find($request->query('eventId'));
        $seller = Client::find($request->query('sellerId'));

        $orders = Order::where('event_id', $event->id)
            ->with(['animal', 'seller', 'seller.address'])
            ->selectRaw("
            orders.*, 
            SUM(gross_value) OVER() as total_gross_value,
    
            (
                COALESCE((
                    SELECT SUM(value)
                    FROM parcels
                    WHERE order_id = orders.id AND payment_method_id = 5
                ), 0)
                +
                COALESCE((
                    SELECT SUM(value)
                    FROM buyer_parcels
                    WHERE order_id = orders.id AND payment_method_id = 5
                ), 0)
                +
                COALESCE((
                    SELECT SUM(value)
                    FROM seller_parcels
                    WHERE order_id = orders.id AND payment_method_id = 5
                ), 0)
            ) as receipt
        ")
            ->orderByRaw("batch IS NULL, batch")
            ->whereHas('seller', function ($query) use ($seller) {
                $query->where('id', $seller->id);
            })
            ->get();

        $grouped = EarningDiscount::where('event_id', $event->id)
            ->where('client_id', $seller->id)
            ->get()
            ->groupBy('type');

        $earnings = $grouped->get('earning', collect());
        $discounts = $grouped->get('discount', collect());

        $name = strtoupper('EXTRATO DO VENDEDOR  - ' . $event->name . ' - ' . $seller->name);
        $fileName = $name . '.pdf';

        $args = [
            'title' => $event->name,
            'seller' => $seller,
            'orders' => $orders,
            'earnings' => $earnings,
            'discounts' => $discounts,
            'event' => $event,
        ];

        return ReportFactory::getBasicPdf('landscape', 'reports.seller-statement', $args, $fileName);
    }
}
