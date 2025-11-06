<?php

use App\Http\Controllers\AnimalController;
use App\Http\Controllers\BidController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FilamentFilterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SalesMapController;
use App\Http\Controllers\SellerStatementController;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

use function PHPUnit\Framework\fileExists;

Livewire::setScriptRoute(function ($handle) {
    return Route::get('/boqueirao/boqueiraoremates/public/livewire/livewire.js', $handle);
});

Livewire::setUpdateRoute(function ($handle) {
    return Route::post('/boqueirao/boqueiraoremates/public/livewire/update', $handle);
});


// Livewire::setScriptRoute(function ($handle) {
//     return Route::get('/v2/public/livewire/livewire.js', $handle);
// });

// Livewire::setUpdateRoute(function ($handle) {
//     return Route::post('/v2/public/livewire/update', $handle);
// });

/**
 * Ao trocar a senha do usuário, o Laravel exige um novo login.
 * Para isso, é necessário informar a rota de login
 */
Route::redirect('/boqueirao/boqueiraoremates/public/admin/login', '/boqueirao/boqueiraoremates/public/admin/login')->name('login');


Route::middleware(['auth'])->group(function () {
    Route::get('/ficha-cadastral/{clientId}', [ClientController::class, 'getPdf'])->name('client-details-pdf');
    Route::get('/ordem-de-servico/{orderId}', [OrderController::class, 'getPdf'])->name('order-pdf');

    Route::get('/mapa-de-vendas/pdf', [SalesMapController::class, 'getPdf'])->name('sales-map-pdf');
    Route::get('/extrato-do-vendedor/pdf', [SellerStatementController::class, 'getPdf'])->name('seller-statement-pdf');

    Route::post('/bids', [BidController::class, 'store'])->name('bids.store'); // Recebe lance do site
    Route::post('/bids/{bid}/approve', [BidController::class, 'approve'])->name('bids.approve'); // Aprovar manualmente
});

Route::get('/teste/{id}', function (string $id) {
    return 'User ' . $id;
});

Route::get('/', function () {
    return redirect(route('home'));
});

Route::get('/site', [HomeController::class, 'index'])->name('home');

Route::get('/eventos/{event}', [EventController::class, 'show'])->name('events.show');

// Route::get('/evento/{event}/animal/{animal}', [AnimalController::class, 'show'])
//     ->name('animals.show');
Route::get('/event/{event}/lote/{animalEvent}', [AnimalController::class, 'show'])
    ->name('animals.show');


Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login.submit');
Route::post('check-first-login', [LoginController::class, 'checkFirstLogin'])->name('check.first_login');
Route::post('first-access-validate', [LoginController::class, 'validateFirstAccess'])->name('first_access.validate');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');


Route::post('/forgot-password-validate', [LoginController::class, 'validateFirstAccess'])->name('forgot_password.validate');

// Recuperação de senha
// Route::get('/recover', [LoginController::class, 'showRecoverForm'])->name('recover.form');
// Route::post('/recover/validate', [LoginController::class, 'recoverValidate'])->name('recover.validate');
// Route::post('/recover/set-password', [LoginController::class, 'recoverSetNewPassword'])->name('recover.set_password');

Route::match(['get', 'post'], 'filament/filters/update', [FilamentFilterController::class, 'update'])
    ->name('filament.filters.update');
Route::get('/filament/filters/lots/{eventId}', [FilamentFilterController::class, 'lots']);

Route::get('/teste-email', function () {
    try {
        Mail::raw('Este é um teste de envio de e-mail pelo Laravel usando SMTP da Hostinger.', function ($message) {
            $message->to('guilhermelegramante@gmail.com') // 👉 altere para o e-mail onde quer receber o teste
                    ->subject('Teste de Envio - Hostinger SMTP')
                    ->from('contato@boqueiraoremates.com', 'Boqueirão Remates');
        });

        return '✅ E-mail de teste enviado com sucesso!';
    } catch (\Exception $e) {
        return '❌ Erro ao enviar e-mail: ' . $e->getMessage();
    }
});
