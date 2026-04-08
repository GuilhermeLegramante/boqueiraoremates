<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Bid;
use App\Utils\ReportFactory;
use Illuminate\Http\Request; 
class BidReportController extends Controller
{
    public function generateEventBidsPdf(Request $request, $eventId)
    {
        set_time_limit(0);

        $event = Event::findOrFail($eventId);

        // Captura os IDs que enviamos pelo link do Filament
        $selectedBids = $request->get('selectedBids');

        $query = Bid::where('event_id', $eventId)
            ->where('status', 1)
            ->with(['user', 'approvedBy']);

        // LÓGICA DE FILTRO:
        // Se vierem IDs selecionados, filtramos por eles. 
        // Se não vier nada (ex: acesso direto), usamos o filtro padrão dos nomes.
        if (!empty($selectedBids) && is_array($selectedBids)) {
            $query->whereIn('id', $selectedBids);
        } else {
            $query->whereHas('user', function ($query) {
                $query->whereNotIn('name', [
                    'LUIS EMERSON HOISLER DA ROSA',
                    'LEANDRO CESAR DORNELES DE OLIVEIRA'
                ]);
            });
        }

        $bids = $query->get();

        $fileName = 'RELATORIO_LANCES_' . str_replace(' ', '_', $event->name);

        $args = [
            'event' => $event,
            'bids' => $bids,
            'title' => 'RELATÓRIO DE LANCES APROVADOS',
        ];

        return ReportFactory::getBasicPdf('portrait', 'reports.bids-event', $args, $fileName);
    }
}
