<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReportController;
use App\Models\Client;
use Illuminate\Support\Facades\DB;
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


    // LOCALIDADES

    // $file = file_get_contents('C:\Users\Marca & Sinal\Desktop\santa vitoria do palmar\localidades.csv');

    // $array = explode(PHP_EOL, $file);

    // $locales = [];

    // foreach ($array as $key => $value) {
    //     $exploit = explode(';', $value);

    //     if (isset($exploit[0]) && isset($exploit[1])) {
    //         $locale['id'] = $exploit[0];

    //         $locale['name'] = $exploit[1];

    //         array_push($locales, $locale);
    //     }
    // }

    // foreach ($locales as $key => $locale) {
    //     DB::connection('marcaesinal')->table('agro_localidade')
    //         ->insert([
    //             'id' => $locale['id'],
    //             'idusuario' => 1,
    //             'idmunicipio' => 4218,
    //             'datahora' => now(),
    //             'descricao' => mb_strtoupper(str_replace('"', "", $locale['name'])),
    //             'codigo' => $locale['id'],
    //             'created_at' => now(),
    //         ]);
    // }



    // PRODUTORES

    // set_time_limit(0);

    // $file = file_get_contents('C:\Users\Marca & Sinal\Desktop\santa vitoria do palmar\produtores.csv');

    // $array = explode(PHP_EOL, $file);

    // $farmers = [];

    // foreach ($array as $key => $value) {
    //     $exploit = explode(';', $value);

    //     if (isset($exploit[0]) && isset($exploit[1])) {
    //         $farmer['id'] = $exploit[0];
    //         $farmer['name'] = $exploit[1];
    //         $farmer['cpf'] = $exploit[2];
    //         $farmer['locale'] = $exploit[3];
    //         $farmer['address'] = $exploit[4];
    //         $farmer['phone'] = $exploit[5];

    //         array_push($farmers, $farmer);
    //     }
    // }

    // foreach ($farmers as $key => $farmer) {
    //     if ($farmer['name'] != "") {
    //         $idLogradouro =  DB::connection('marcaesinal')->table('hscad_logradouros')
    //             ->insertGetId([
    //                 'idcidade' => 4218,
    //                 'nome' => mb_strtoupper(str_replace('"', "", $farmer['address'])),
    //                 'cep' => '96230-000',
    //                 'tipo' => 'R',
    //                 'created_at' => now(),
    //             ]);

    //         $idMunicipe = DB::connection('marcaesinal')->table('hscad_cadmunicipal')
    //             ->insertGetId([
    //                 'idusuario' => 1,
    //                 'idlogradouro' => $idLogradouro,
    //                 'nome' => mb_strtoupper(str_replace('"', "", $farmer['name'])),
    //                 'tipopessoa' => 'F',
    //                 'ativo' => 1,
    //                 'datacadastro' => now(),
    //                 'datahora' => now(),
    //                 'fornecedor' => 0,
    //                 'tipocredor' => '02',
    //                 'created_at' => now(),
    //             ]);

    //         if (strlen($farmer['cpf']) == 11) {
    //             DB::connection('marcaesinal')->table('hscad_municipedoc')
    //                 ->insertGetId([
    //                     'idmunicipe' => $idMunicipe,
    //                     'iddocumento' => 3,
    //                     'numero' => $farmer['cpf'][0] .
    //                         $farmer['cpf'][1] . $farmer['cpf'][2] . '.' . $farmer['cpf'][3] . $farmer['cpf'][4] . $farmer['cpf'][5] . '.' .
    //                         $farmer['cpf'][6] . $farmer['cpf'][7] . $farmer['cpf'][8] . '-' . $farmer['cpf'][9] . $farmer['cpf'][10],
    //                     'observacao' => '',
    //                 ]);
    //         }

    //         DB::connection('marcaesinal')->table('agro_produtor')
    //             ->insertGetId([
    //                 'id' => $farmer['id'],
    //                 'idusuario' => 1,
    //                 'idmunicipe' => $idMunicipe,
    //                 'datahora' => now(),
    //                 'created_at' => now(),
    //                 'ativo' => 1,
    //             ]);

    //         $idPropriedade = DB::connection('marcaesinal')->table('agro_propriedade')
    //             ->insertGetId([
    //                 'idusuario' => 1,
    //                 'idlocalidade' =>  mb_strtoupper(str_replace('"', "", $farmer['locale'])),
    //                 'idprodutor' => $farmer['id'],
    //                 'idtitulo' => 1,
    //                 'descricao' => 'SEM DENOMINAÇÃO',
    //                 'created_at' => now(),
    //             ]);

    //         DB::connection('marcaesinal')->table('agro_produtor_propriedade')
    //             ->insertGetId([
    //                 'idprodutor' => $farmer['id'],
    //                 'idpropriedade' => $idPropriedade,
    //                 'created_at' => now(),
    //             ]);
    //     }
    // }

    // dd('SALVOU OS PRODUTORES');

    // MARCAS

    set_time_limit(0);

    $file = file_get_contents('https://santa-vitoria-do-palmar.marcaesinal.com/storage/marcas.csv');

    $array = explode(PHP_EOL, $file);

    $brands = [];

    foreach ($array as $key => $value) {
        $exploit = explode(';', $value);

        if (isset($exploit[0]) && isset($exploit[1])) {
            $brand['id'] = $exploit[0];
            $brand['number'] = $exploit[1];
            $brand['farmerId'] = $exploit[2];
            $brand['filename'] = $exploit[3];

            array_push($brands, $brand);
        }
    }

    foreach ($brands as $key => $brand) {
        $farmer = DB::connection('marcaesinal')->table('agro_produtor')->where('id', $brand['farmerId'])->get()->first();

        if (isset($farmer)) {
            $url = 'https://santa-vitoria-do-palmar.marcaesinal.com/storage/marcas/marcas_png/' . $brand['filename'];

            $handle = curl_init($url);
            curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);

            /* Get the HTML or whatever is linked in $url. */
            $response = curl_exec($handle);

            /* Check for 404 (file not found). */
            $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);

            if ($httpCode == 200) {
                /* Handle 404 here. */
                DB::connection('marcaesinal')->table('agro_marca')
                    ->insertGetId([
                        'id' => $brand['id'],
                        'idusuario' => 1,
                        'idmunicipe' => $farmer->idmunicipe,
                        'numero' => $brand['number'],
                        'numero_original' => $brand['number'],
                        'ano' => date('Y'),
                        'ano_original' => date('Y'),
                        'path' => 'https://santa-vitoria-do-palmar.marcaesinal.com/storage/marcas/marcas_png/' . $brand['filename'],
                        'visivel' => 1,
                        'situacao' => 'L',
                        'datahora' => now(),
                        'created_at' => now(),
                    ]);
            }

            curl_close($handle);
        }
    }
})->name('convert-image');

Route::get('/', function () {
    dd(Client::find(1)->testando);
    return view('welcome');
});
