<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\Event;
use App\Models\Order;
use App\Models\User;
use App\Utils\ReportFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesMapController extends Controller
{
    public function getPdf($id)
    {
        $eventId = $id;

        $event = Event::find($id);

        $orders = Order::where('event_id', $eventId)
            ->with(['animal', 'seller', 'seller.address'])
            ->selectRaw('orders.*, SUM(gross_value) OVER() as total_gross_value')
            ->orderByRaw(
                "batch IS NULL, batch"
            )
            ->get();

        // Cálculo de resumos
        $totalOrders = Order::where('event_id', $eventId)->count();

        $avgGeneral = Animal::leftJoin('orders', function ($join) use ($eventId) {
            $join->on('animals.id', '=', 'orders.animal_id')
                ->where('orders.event_id', $eventId);
        })
            ->whereHas('events', function ($query) use ($eventId) {
                $query->where('event_id', $eventId);
            })
            ->avg(DB::raw('IFNULL(orders.gross_value, 0)')) ?? 0.00;

        $avgMaleRevenue = Order::whereIn('animal_id', function ($query) {
            $query->select('id')->from('animals')->where('gender', 'male');
        })
            ->where('event_id', $eventId)
            ->avg('gross_value') ?? 0.00;

        $avgFemaleRevenue = Order::whereIn('animal_id', function ($query) {
            $query->select('id')->from('animals')->where('gender', 'female');
        })
            ->where('event_id', $eventId)
            ->avg('gross_value') ?? 0.00;

        $totalRevenue = Order::where('event_id', $eventId)->sum('gross_value') ?? 0.00;

        $avgRevenuePerBatch = Order::where('event_id', $eventId)->avg('gross_value') ?? 0.00;

        $name = strtoupper('MAPA DE VENDAS  - ' . $event->name);

        $fileName = $name . '.pdf';

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
            'title' => $name,
            'user' => $user,
            'orders' => $orders,
            'totalOrders' => $totalOrders,
            'avgGeneral' => $avgGeneral,
            'avgMaleRevenue' => $avgMaleRevenue,
            'avgFemaleRevenue' => $avgFemaleRevenue,
            'totalRevenue' => $totalRevenue,
            'avgRevenuePerBatch' => $avgRevenuePerBatch,
        ];

        return ReportFactory::getBasicPdf('landscape', 'reports.sales-map', $args, $fileName);
    }
}
