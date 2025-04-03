<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SalesMapController;
use App\Models\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

use function PHPUnit\Framework\fileExists;

Livewire::setScriptRoute(function ($handle) {
    return Route::get('/v2/public/livewire/livewire.js', $handle);
});

Livewire::setUpdateRoute(function ($handle) {
    return Route::post('/v2/public/livewire/update', $handle);
});

/**
 * Ao trocar a senha do usuário, o Laravel exige um novo login.
 * Para isso, é necessário informar a rota de login
 */
Route::redirect('/v2/public/admin/login', '/v2/public/admin/login')->name('login');


Route::middleware(['auth'])->group(function () {
    Route::get('/ficha-cadastral/{clientId}', [ClientController::class, 'getPdf'])->name('client-details-pdf');
    Route::get('/ordem-de-servico/{orderId}', [OrderController::class, 'getPdf'])->name('order-pdf');

    Route::get('/mapa-de-vendas/pdf', [SalesMapController::class, 'getPdf'])->name('sales-map-pdf');

});

// Route::get('/ficha-cadastral/{id}', [ReportController::class, 'clientDetails']);

Route::get('/teste/{id}', function (string $id) {
    return 'User ' . $id;
});

Route::get('/salvar-datas-santavitoria', function () {
    // set_time_limit(0);

    // $file = file_get_contents('C:\Users\Marca & Sinal\Desktop\santa vitoria do palmar\ajuste-datas\marcas-sem-cabecalho.txt');

    // $array = explode(PHP_EOL, $file);

    // foreach ($array as $key => $value) {
    //     if (empty($value)) continue;

    //     $exploit = explode("\t", $value);

    //     $brandId = $exploit[0];
    //     $date = $exploit[5];

    //     $brand = DB::connection('marcaesinal')->table('agro_marca')
    //         ->where('id', $brandId)
    //         ->get()
    //         ->first();

    //     if (isset($brand) && isset($date)) {
    //         if ($brandId > 6118) {
    //             DB::connection('marcaesinal')->table('agro_marca')
    //                 ->where('id', $brandId)
    //                 ->update([
    //                     'datahora' => $date,
    //                 ]);
    //             Log::info("Id Marca: {$brandId} - {$date}");
    //         }
    //     }
    // }

    dd('salvou as datas');
});


Route::get('/gerando-log-conversao', function () {
    // set_time_limit(0);

    // $file = file_get_contents('C:\Users\Marca & Sinal\Desktop\CONVERSAO_ALEGRETE_MeS\CSVs\OK\marcas_e_produtores.csv');

    // $array = explode(PHP_EOL, $file);

    // // Itera sobre as linhas do CSV
    // foreach ($array as $key => $value) {
    //     // Se a linha estiver vazia, pula
    //     if (empty($value)) continue;

    //     // Divide a linha do CSV em partes
    //     $exploit = explode(',', $value);

    //     $nomeImagem = trim($exploit[5]);

    //     $brand_and_farmer_data['id_marca'] = isset($exploit[0]) && trim($exploit[0]) != 'NULL' ? trim($exploit[0]) : null;
    //     $brand_and_farmer_data['nome_imagem_marca'] = isset($exploit[5]) && trim($exploit[5]) != 'NULL'
    //         ? substr(trim($exploit[5]), 0, -4)
    //         : null;
    //     $brand_and_farmer_data['id_produtor'] = isset($exploit[8]) && trim($exploit[8]) != 'NULL' ? trim($exploit[8]) : null;
    //     $brand_and_farmer_data['nome_produtor'] = isset($exploit[9]) && trim($exploit[9]) != 'NULL' ? trim($exploit[9]) : null;

    //     if (!file_exists("C:\Users\Marca & Sinal\Desktop\CONVERSAO_ALEGRETE_MeS\marcas-png\\" . $brand_and_farmer_data['nome_imagem_marca'] . ".png")) {
    //         Log::error("Arquivo não encontrado: https://ecidade.alegrete.rs.gov.br/w/3/tmp/{$nomeImagem}");
    //     } else if (!isset($brand_and_farmer_data['nome_produtor'])) {
    //         Log::error("Campo nome_produtor nulo. ID Produtor: {$brand_and_farmer_data['id_produtor']}");
    //     } else if (!isset($brand_and_farmer_data['nome_imagem_marca'])) {
    //         Log::error("Campo nome_imagem_marca nulo. ID Marca: {$brand_and_farmer_data['id_marca']}");
    //     } else {
    //         // Busca a marca
    //         $brand = DB::connection('marcaesinal')->table('agro_marca')
    //             ->join('hscad_cadmunicipal', 'hscad_cadmunicipal.inscricaomunicipal', '=', 'agro_marca.idmunicipe')
    //             ->select('agro_marca.id AS id', 'hscad_cadmunicipal.nome AS nome', 'agro_marca.path as path')
    //             ->where('hscad_cadmunicipal.nome', 'like', $brand_and_farmer_data['nome_produtor'])
    //             ->get()
    //             ->first();

    //         if (isset($brand)) {
    //             Log::info("Id Marca: {$brand->id} - {$brand->nome} - {$brand->path}");
    //         }
    //     }
    // }
});



Route::get('/', function () {
    return redirect(route('filament.admin.pages.dashboard'));
});
