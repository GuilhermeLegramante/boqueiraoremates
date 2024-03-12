<?php

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
Route::redirect('/boqueiraoremates/public/login', '/boqueiraoremates/public/login')->name('login');

Route::get('/', function () {
    return view('welcome');
});
