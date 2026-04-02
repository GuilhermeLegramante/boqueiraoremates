<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Client;
use App\Models\Address;
use App\Models\Document;
use App\Models\DocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class CustomRegisterController extends Controller
{
    public function checkClient(Request $request)
    {
        try {
            $cpfEnviado = $request->cpf_cnpj;

            if (empty($cpfEnviado)) {
                return response()->json(['exists' => false]);
            }

            // Busca o cliente. 
            // Usamos o nome exato da função que você me mandou: registeredUser
            $client = \App\Models\Client::with(['address', 'registeredUser'])
                ->where('cpf_cnpj', $cpfEnviado)
                ->orWhere('cpf_cnpj', preg_replace('/\D/', '', $cpfEnviado))
                ->first();

            if ($client) {
                return response()->json([
                    'exists' => true,
                    'data' => [
                        'name' => $client->name,
                        // Se o cliente existe mas não tem usuário vinculado, evitamos erro:
                        'email' => $client->registeredUser->email ?? '',
                        'address' => $client->address
                    ]
                ]);
            }

            return response()->json(['exists' => false]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        // 1. Tratamento da Data e Validação de Idade
        try {
            // Criamos o objeto Carbon primeiro
            $dateObj = Carbon::createFromFormat('d/m/Y', $request->birth_date);

            // Verificação de maioridade (18 anos)
            if ($dateObj->greaterThan(now()->subYears(18))) {
                return response()->json(['success' => false, 'message' => 'Necessário ser maior de 18 anos.'], 422);
            }

            // Formatamos para o banco de dados (string Y-m-d)
            $birthDateDb = $dateObj->format('Y-m-d');
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Data de nascimento inválida.'], 422);
        }

        return DB::transaction(function () use ($request, $birthDateDb) {
            $data = $request->all();
            $cpf = preg_replace('/\D/', '', $data['cpf_cnpj']);

            // 2. Localiza ou Cria Usuário
            $user = User::updateOrCreate(
                ['username' => $cpf],
                [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                ]
            );

            // 3. Endereço
            $address = Address::updateOrCreate(
                ['id' => $data['address_id'] ?? null],
                [
                    'postal_code' => preg_replace('/\D/', '', $data['postal_code']),
                    'street' => strtoupper($data['street']),
                    'city' => strtoupper($data['city']),
                    'state' => strtoupper($data['state']),
                    'district' => strtoupper($data['district']),
                    'number' => $data['number'],
                ]
            );

            // 4. Cliente (Criar ou Atualizar)
            $client = Client::updateOrCreate(
                ['cpf_cnpj' => $cpf],
                [
                    'name' => $data['name'],
                    'birth_date' => $birthDateDb,
                    'whatsapp' => preg_replace('/\D/', '', $data['whatsapp']),
                    'address_id' => $address->id,
                    'registered_user_id' => $user->id, // <--- IMPORTANTE: Vincula o ID do usuário aqui
                    'situation' => 'disabled',
                    'register_origin' => 'site'
                ]
            );

            // Se quiser garantir a associação pelo método do Eloquent também:
            $client->registeredUser()->associate($user);
            $client->save();

            // 5. Upload de Documentos
            if ($request->hasFile('cnh_rg')) {
                $path = $request->file('cnh_rg')->store('documents', 'public');
                $docType = DocumentType::where('name', 'DOCUMENTO PESSOAL')->first();

                if ($docType) {
                    Document::updateOrCreate(
                        ['client_id' => $client->id, 'document_type_id' => $docType->id],
                        ['user_id' => $user->id, 'path' => $path]
                    );
                }
            }

            auth()->login($user);

            // Altere para a URL correta do seu sistema se necessário
            return response()->json([
                'success' => true,
                'redirect' => 'https://sistema.boqueiraoremates.com/'
            ]);
        });
    }
}
