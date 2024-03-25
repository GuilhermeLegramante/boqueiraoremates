<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Utils\ReportFactory;

class ReportController extends Controller
{
    public function clientDetails($clientId)
    {
        $client = Client::where('id', $clientId)->get()->first();

        $fileName = $client->name . '_FICHA_CADASTRAL';

        $args = [
            'client' => $client,
            'title' => 'FICHA CADASTRAL',
        ];

        return ReportFactory::getBasicPdf('portrait', 'reports.client-details', $args, $fileName);
    }
}
