<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReportController;
use App\Models\Client;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

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
});

// Route::get('/ficha-cadastral/{id}', [ReportController::class, 'clientDetails']);



Route::get('/teste/{id}', function (string $id) {
    return 'User ' . $id;
});


Route::get('/converter-imagem', function () {
    // CONVERTE AS IMAGENS JPG PARA PNG
    // $file = file_get_contents('C:\Users\Marca & Sinal\Desktop\santa vitoria do palmar\marca\20100505142601.jpg');

    // $files = scandir('C:\Users\Marca & Sinal\Desktop\santa vitoria do palmar\sinal');

    // foreach ($files as $filename) {
    //     if ($filename != '.' && $filename != '..') {
    //         $file = file_get_contents("C:\Users\Marca & Sinal\Desktop\santa vitoria do palmar\sinal\\". $filename);

    //         $filenameWithoutExtension = substr($filename, 0, -4);

    //         file_put_contents("C:\Users\Marca & Sinal\Desktop\santa vitoria do palmar\\sinais_png\\{$filenameWithoutExtension}.png", $file);
    //     }
    // }


    $file = file_get_contents('C:\Users\Marca & Sinal\Desktop\santa vitoria do palmar\localidades.csv');

    $array = explode(PHP_EOL, $file);

    $locales = [];

    foreach ($array as $key => $value) {
        $exploit = explode(';', $value);

        if(isset($exploit[0]) && isset($exploit[1])){
            $locale['id'] = $exploit[0];
            
            $locale['name'] = $exploit[1];
    
            array_push($locales, $locale);
        }
        
    }

    dd($locales);
})->name('convert-image');

Route::get('/', function () {
    dd(Client::find(1)->testando);
    return view('welcome');
});
