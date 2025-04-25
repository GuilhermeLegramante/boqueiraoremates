<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\EarningDiscount;
use App\Models\Event;
use App\Models\Order;
use App\Utils\ReportFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SellerStatementController extends Controller
{
    public function getPdf(Request $request)
    {
        $event = Event::find($request->query('eventId'));
        $seller = Client::find($request->query('sellerId'));

        $orders = Order::where('event_id', $event->id)
            ->with(['animal', 'seller', 'seller.address'])
            ->whereHas('seller', fn($query) => $query->where('id', $seller->id))
            ->orderByRaw("batch IS NULL, batch")
            ->get();

        foreach ($orders as $order) {
            $receipt1 = DB::table('parcels')
                ->where('order_id', $order->id)
                ->where('payment_method_id', 5)
                ->sum('value');

            $receipt2 = DB::table('buyer_parcels')
                ->where('order_id', $order->id)
                ->where('payment_method_id', 5)
                ->sum('value');

            $receipt3 = DB::table('seller_parcels')
                ->where('order_id', $order->id)
                ->where('payment_method_id', 5)
                ->sum('value');

            $order->receipt = $receipt1 + $receipt2 + $receipt3;

            $notes = [];

            foreach (['parcels', 'buyer_parcels', 'seller_parcels'] as $table) {
                $mapNotes = DB::table($table)
                    ->where('order_id', $order->id)
                    // ->where('payment_method_id', 5) Filtra somente as do tipo MAPA DO REMATE
                    ->whereNotNull('map_note')
                    ->where('map_note', '!=', '')
                    ->pluck('map_note')
                    ->toArray();

                $notes = array_merge($notes, $mapNotes);
            }

            $order->map_note = mb_strtoupper(implode(' | ', $notes), 'UTF-8');
        }




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

        return ReportFactory::download('landscape', 'reports.seller-statement', $args, $fileName);
    }
}
