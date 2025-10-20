<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Client;
use App\Models\ClientNote;
use Illuminate\Support\Facades\Auth;

class MigrateClientNotes extends Command
{
    /**
     * Nome e assinatura do comando
     */
    protected $signature = 'clients:migrate-notes';

    /**
     * Descrição
     */
    protected $description = 'Cria anotações (ClientNote) a partir do campo note dos clientes';

    /**
     * Executa o comando
     */
    public function handle()
    {
        $count = 0;

        $clients = Client::whereNotNull('note')
            ->where('note', '!=', '')
            ->get();

        foreach ($clients as $client) {
            // evita duplicar se já existir anotação idêntica
            $exists = ClientNote::where('client_id', $client->id)
                ->where('content', $client->note)
                ->exists();

            if (! $exists) {
                ClientNote::create([
                    'client_id' => $client->id,
                    'user_id' => 1, // 👈 opcional: define o ID de um usuário (ex: admin)
                    'content' => $client->note,
                ]);

                $count++;
            }
        }

        $this->info("✅ $count anotações criadas a partir do campo note dos clientes.");

        return Command::SUCCESS;
    }
}
