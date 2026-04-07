<?php

use App\Http\Controllers\AnimalController;
use App\Http\Controllers\CustomRegisterController;
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
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

use function PHPUnit\Framework\fileExists;

// Livewire::setScriptRoute(function ($handle) {
//     return Route::get('/boqueiraoremates/public/livewire/livewire.js', $handle);
// });

// Livewire::setUpdateRoute(function ($handle) {
//     return Route::post('/boqueiraoremates/public/livewire/update', $handle);
// });

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

    Route::get('/report/bids-pdf/{eventId}', [App\Http\Controllers\BidReportController::class, 'generateEventBidsPdf'])
        ->name('report.bids.pdf');
});

Route::get('/teste', function () {
    $now = \Carbon\Carbon::now();

    $count = \App\Models\Event::where('published', true)
        ->where('finish_date', '<', $now->subHours(5))
        ->get();

    dd($count);
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
        // 🔹 Dados fictícios para teste
        $user = (object)[
            'name' => 'João da Silva',
            'email' => 'joao.silva@example.com',
        ];

        $event = (object)[
            'name' => 'Leilão de Primavera 2025',
        ];

        $animal = (object)[
            'name' => 'Touro Brangus Campeão',
        ];

        $amount = 15750.00;

        // 📧 Envio do e-mail
        Mail::send('emails.new-bid', [
            'user' => $user,
            'event' => $event,
            'animal' => $animal,
            'amount' => $amount,
        ], function ($mail) use ($event) {
            $mail->to(['lances@boqueiraoremates.com', 'guilhermelegramante@gmail.com'])
                ->subject('Novo Lance Recebido - ' . $event->name)
                ->from('contato@boqueiraoremates.com', 'Boqueirão Remates');
        });

        return '✅ E-mail de teste enviado com sucesso!';
    } catch (\Exception $e) {
        return '❌ Erro ao enviar e-mail: ' . $e->getMessage();
    }
});


Route::get('/despublicar-eventos', function () {
    Artisan::call('events:unpublish-old');

    return response()->json([
        'status' => 'ok',
        'message' => 'Comando events:unpublish-old executado com sucesso!',
    ]);
});

// Rotas de Cadastro Personalizado
// Route::middleware('guest')->group(function () {

// 1. Rota para exibir a página (a view Blade que criamos)
Route::get('/cadastro', function () {
    return view('site.register');
})->name('register.custom');

// 2. Rota para a lógica de salvar os dados (POST)
Route::post('/cadastro/store', [CustomRegisterController::class, 'store'])
    ->name('register.custom.store');

// 3. Rota API interna para busca de CPF/CNPJ (Reatividade)
// Usamos GET aqui pois é apenas uma consulta de dados
Route::get('/api/check-client', [CustomRegisterController::class, 'checkClient'])
    ->name('api.check.client');
// });

Route::get('/teste-cpf', function () {
    $cpf = '017.859.290-03';

    // Busca o cliente
    $client = \App\Models\Client::where('cpf_cnpj', $cpf)->first();

    if (!$client) {
        return "Cliente com CPF {$cpf} NÃO encontrado no banco.";
    }

    // Tenta carregar as relações
    $client->load(['address', 'registeredUser']);

    return response()->json([
        'cliente_encontrado' => $client->name,
        'cpf_no_banco' => $client->cpf_cnpj,
        'possui_endereco' => !is_null($client->address),
        'dados_endereco' => $client->address,
        'possui_usuario_vinculado' => !is_null($client->registeredUser),
        'email_do_usuario' => $client->registeredUser->email ?? 'Sem e-mail (registered_user_id está nulo?)',
        'id_do_usuario_no_cliente' => $client->registered_user_id
    ]);
});
