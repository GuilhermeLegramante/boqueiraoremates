<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Client;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LinkClientsToUsers extends Command
{
    protected $signature = 'clients:link-users';
    protected $description = 'Cria usuários para clientes que não possuem registered_user_id e vincula, evitando duplicidade';

    public function handle()
    {
        $clients = Client::whereNull('registered_user_id')->get();

        foreach ($clients as $client) {
            // Remove máscara do CPF/CNPJ e pega os 6 primeiros dígitos
            $cpf = preg_replace('/\D/', '', $client->cpf_cnpj);
            $password = substr($cpf, 0, 6);

            // Verifica se já existe usuário com o mesmo username ou email
            $existingUser = User::where('username', $client->cpf_cnpj)
                // ->orWhere('email', $client->email)
                ->first();

            if ($existingUser) {
                $client->registeredUser()->associate($existingUser);
                $client->save();
                $this->info("Cliente {$client->id} vinculado ao usuário existente {$existingUser->id}");
                continue;
            }

            // Cria o usuário
            $user = User::create([
                'name'     => $client->name,
                'username' => $client->cpf_cnpj,
                'email'    => $client->email ?? "{$cpf}@example.com", // fallback se não tiver email
                'password' => Hash::make($password),
            ]);

            // Atribui role 'client'
            $user->assignRole('client');

            // Vincula o usuário ao cliente
            $client->registeredUser()->associate($user);
            $client->save();

            $this->info("Cliente {$client->id} vinculado ao novo usuário {$user->id}");
        }

        $this->info('Todos os clientes foram processados.');
    }
}
