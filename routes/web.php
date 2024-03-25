<?php

use App\Http\Controllers\ReportController;
use App\Models\Client;
use Illuminate\Support\Facades\Route;

use Livewire\Livewire;

Livewire::setScriptRoute(function ($handle) {
    return Route::get('/boqueiraoremates/public/livewire/livewire.js', $handle);
});

Livewire::setUpdateRoute(function ($handle) {
    return Route::post('/boqueiraoremates/public/livewire/update', $handle);
});

/**
 * Ao trocar a senha do usuário, o Laravel exige um novo login.
 * Para isso, é necessário informar a rota de login
 */
Route::redirect('/boqueiraoremates/public/admin/login', '/boqueiraoremates/public/admin/login')->name('login');


Route::middleware(['auth'])->group(function () {
    Route::get('/ficha-cadastral/{clientId}', [ReportController::class, 'clientDetails'])->name('client-details');
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
