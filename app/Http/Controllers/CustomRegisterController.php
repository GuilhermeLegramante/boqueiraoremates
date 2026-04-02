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
                        'whatsapp' => $client->whatsapp,
                        'birth_date' => $client->birth_date ? Carbon::parse($client->birth_date)->format('d/m/Y') : '',
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
            $dateObj = Carbon::createFromFormat('d/m/Y', $request->birth_date);
            if ($dateObj->greaterThan(now()->subYears(18))) {
                return response()->json(['success' => false, 'message' => 'Necessário ser maior de 18 anos.'], 422);
            }
            $birthDateDb = $dateObj->format('Y-m-d');
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Data de nascimento inválida.'], 422);
        }

        return DB::transaction(function () use ($request, $birthDateDb) {
            $data = $request->all();
            $cpfLimpo = preg_replace('/\D/', '', $data['cpf_cnpj']);

            // 1. Usuário
            $user = User::updateOrCreate(
                ['username' => $cpfLimpo],
                [
                    'name' => strtoupper($data['name']),
                    'email' => strtolower($data['email']),
                    'password' => Hash::make($data['password']),
                ]
            );
            $user->assignRole('client'); // Garante a role

            // 2. Endereço
            $address = Address::create([
                'postal_code' => preg_replace('/\D/', '', $data['postal_code'] ?? ''),
                'street'      => strtoupper($data['street'] ?? ''),
                'city'        => strtoupper($data['city'] ?? ''),
                'state'       => strtoupper($data['state'] ?? 'RS'),
                'district'    => strtoupper($data['district'] ?? ''),
                'number'      => $data['number'] ?? 'S/N',
            ]);

            // 3. Cliente (CORRIGIDO WHATSAPP E EMAIL)
            $client = Client::updateOrCreate(
                ['cpf_cnpj' => $cpfLimpo],
                [
                    'name'               => strtoupper($data['name']),
                    'email'              => strtolower($data['email']), // SALVANDO EMAIL
                    'birth_date'         => $birthDateDb,
                    'whatsapp'           => preg_replace('/\D/', '', $data['whatsapp']), // SALVANDO WHATSAPP
                    'address_id'         => $address->id,
                    'registered_user_id' => $user->id,
                    'situation'          => 'disabled',
                    'register_origin'    => 'site'
                ]
            );

            // 4. Documento (CORRIGIDO PATH)
            if ($request->hasFile('cnh_rg')) {
                $file = $request->file('cnh_rg');
                // Salva com nome único para evitar conflitos
                $fileName = time() . '_' . $client->id . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('documents', $fileName, 'public');

                $docType = DocumentType::where('name', 'LIKE', '%DOCUMENTO PESSOAL%')->first();

                if ($docType) {
                    Document::updateOrCreate(
                        ['client_id' => $client->id, 'document_type_id' => $docType->id],
                        ['path' => $path] // O Model Document já tem o boot para criar a Note
                    );
                }
            }

            auth()->login($user);

            return response()->json([
                'success' => true,
                'redirect' => 'https://sistema.boqueiraoremates.com/'
            ]);
        });
    }
}
