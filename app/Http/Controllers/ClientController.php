<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Utils\ReportFactory;

class ClientController extends Controller
{
    public function getPdf($clientId)
    {
        $client = Client::with('notes.user') // 👈 traz também o usuário autor da nota
            ->findOrFail($clientId);

        $fileName = $client->name . '_FICHA_CADASTRAL';

        $args = [
            'client' => $client,
            'title' => 'FICHA CADASTRAL',
        ];

        return ReportFactory::getBasicPdf('portrait', 'reports.client-details', $args, $fileName);
    }
}
