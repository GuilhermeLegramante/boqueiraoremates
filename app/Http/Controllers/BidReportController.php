<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Bid;
use App\Utils\ReportFactory;

class BidReportController extends Controller
{
    public function generateEventBidsPdf($eventId)
    {
        set_time_limit(0);

        $event = Event::findOrFail($eventId);

        $bids = Bid::where('event_id', $eventId)
            ->where('status', 1)
            ->with(['user', 'approvedBy'])
            ->get();

        $fileName = 'RELATORIO_LANCES_' . str_replace(' ', '_', $event->name);

        $args = [
            'event' => $event,
            'bids' => $bids,
            'title' => 'RELATÓRIO DE LANCES APROVADOS',
        ];

        // dd($args);

        // Usando a tua Factory
        return ReportFactory::getBasicPdf('portrait', 'reports.bids-event', $args, $fileName);
    }
}
