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
Route::redirect('/boqueiraoremates/public/admin/login', '/boqueiraoremates/public/admin/login')->name('login');


Route::middleware(['auth'])->group(function () {
    Route::get('/ficha-cadastral/{clientId}', [ClientController::class, 'getPdf'])->name('client-details-pdf');
    Route::get('/ordem-de-servico/{orderId}', [OrderController::class, 'getPdf'])->name('order-pdf');

});

// Route::get('/ficha-cadastral/{id}', [ReportController::class, 'clientDetails']);



Route::get('/teste/{id}', function (string $id) {
    return 'User ' . $id;
});


Route::get('/teste/{record}', function () {
    dd('a');
})->name('teste');
Route::get('/', function () {
    dd(Client::find(1)->testando);
    return view('welcome');
});
