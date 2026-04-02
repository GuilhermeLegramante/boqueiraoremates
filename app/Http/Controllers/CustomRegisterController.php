<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Client;
use App\Models\Address;
use App\Models\Document;
use App\Models\DocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class CustomRegisterController extends Controller
{
    public function checkClient(Request $request)
    {
        $client = Client::with('address', 'registeredUser')
            ->where('cpf_cnpj', $request->cpf_cnpj)
            ->first();

        if ($client) {
            return response()->json([
                'exists' => true,
                'data' => [
                    'name' => $client->registeredUser->name ?? $client->name,
                    'email' => $client->registeredUser->email ?? '',
                    'address' => $client->address
                ]
            ]);
        }
        return response()->json(['exists' => false]);
    }

    public function store(Request $request)
    {
        // 1. Validação básica de idade (Regra de negócio)
        $birthDate = Carbon::createFromFormat('d/m/Y', $request->birth_date);
        if ($birthDate->greaterThan(now()->subYears(18))) {
            return response()->json(['success' => false, 'message' => 'Necessário ser maior de 18 anos.'], 422);
        }

        return DB::transaction(function () use ($request, $birthDate) {
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

            // 3. Endereço (Regra de Uppercase do seu form)
            $address = Address::updateOrCreate(
                ['id' => $data['address_id'] ?? null],
                [
                    'postal_code' => $data['postal_code'],
                    'street' => strtoupper($data['street']),
                    'city' => strtoupper($data['city']),
                    'state' => strtoupper($data['state']),
                    'district' => strtoupper($data['district']),
                    'number' => $data['number'],
                ]
            );

            // 4. Cliente
            $client = Client::updateOrCreate(
                ['cpf_cnpj' => $cpf],
                [
                    'name' => $data['name'],
                    'birth_date' => $birthDate->format('Y-m-d'),
                    'whatsapp' => $data['whatsapp'],
                    'address_id' => $address->id,
                    'situation' => 'disabled', // Conforme seu código original
                    'register_origin' => 'site'
                ]
            );
            $client->registeredUser()->associate($user);
            $client->save();

            // 5. Upload de Documentos
            if ($request->hasFile('cnh_rg')) {
                $path = $request->file('cnh_rg')->store('documents', 'public');
                $docType = DocumentType::where('name', 'DOCUMENTO PESSOAL')->first();
                
                Document::updateOrCreate(
                    ['client_id' => $client->id, 'document_type_id' => $docType->id],
                    ['user_id' => $user->id, 'path' => $path]
                );
            }

            auth()->login($user);
            return response()->json(['success' => true, 'redirect' => '/home']);
        });
    }
}