<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Client;
use App\Models\Address;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\Bank;
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
                return response()->json([
                    'exists' => false,
                    'message' => 'CPF/CNPJ é obrigatório.'
                ]);
            }

            // Busca o cliente com os novos campos
            $client = \App\Models\Client::with(['address', 'registeredUser'])
                ->where('cpf_cnpj', $cpfEnviado)
                ->orWhere('cpf_cnpj', preg_replace('/\D/', '', $cpfEnviado))
                ->first();

            if ($client) {
                return response()->json([
                    'exists' => true,
                    'data' => [
                        'name'            => $client->name,
                        'mother'          => $client->mother,
                        'father'          => $client->father, // NOVO
                        'rg'              => $client->rg,     // NOVO
                        'occupation'      => $client->occupation,
                        'income'          => $client->income,
                        'bank_id'         => $client->bank_id,         // NOVO
                        'bank_agency'     => $client->bank_agency,     // NOVO
                        'current_account' => $client->current_account, // NOVO
                        'email'           => $client->registeredUser->email ?? $client->email ?? '',
                        'whatsapp'        => $client->whatsapp,
                        'birth_date'      => $client->birth_date ? \Carbon\Carbon::parse($client->birth_date)->format('d/m/Y') : '',
                        'address'         => [
                            'postal_code' => $client->address->postal_code ?? '',
                            'street'      => $client->address->street ?? '',
                            'number'      => $client->address->number ?? '',
                            'complement'  => $client->address->complement ?? '',
                            'district'    => $client->address->district ?? '',
                            'city'        => $client->address->city ?? '',
                            'state'       => $client->address->state ?? '',
                        ]
                    ]
                ]);
            }

            return response()->json([
                'exists' => false,
                'message' => 'CPF/CNPJ não encontrado.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        // 1. Validação de Campos Obrigatórios e Formato de E-mail
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'name'            => 'required|string|max:255',
            'rg'              => 'required|string|max:50',  // RG Obrigatório
            'father'          => 'required|string|max:255', // Nome do pai obrigatório
            'mother'          => 'required|string|max:255', // Nome da mãe obrigatório
            'email'           => 'required|email:rfc,dns',  // E-mail obrigatório e formato válido
            'birth_date'      => 'required',
            'whatsapp'        => 'required',
            'password'        => 'required|min:6',
            'income'          => 'required', // Renda obrigatória
            'occupation'      => 'required', // Profissão obrigatória
            'bank_id'         => 'required|exists:banks,id', // Banco obrigatório
            'bank_agency'     => 'required|string|max:20',    // Agência obrigatória
            'current_account' => 'required|string|max:30',    // Conta corrente obrigatória
        ], [
            'rg.required'              => 'O RG é obrigatório.',
            'father.required'          => 'O nome do pai é obrigatório.',
            'mother.required'          => 'O nome da mãe é obrigatório.',
            'email.required'           => 'O e-mail é obrigatório.',
            'email.email'              => 'Por favor, insira um endereço de e-mail válido.',
            'bank_id.required'         => 'Selecione o banco.',
            'bank_id.exists'           => 'O banco selecionado é inválido.',
            'bank_agency.required'     => 'A agência bancária é obrigatória.',
            'current_account.required' => 'A conta corrente é obrigatória.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

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
            $cpfComMascara = $data['cpf_cnpj']; // Mantendo a máscara conforme solicitado

            // 1. Usuário (Update ou Create pelo Username/CPF)
            $user = User::updateOrCreate(
                ['username' => $cpfComMascara],
                [
                    'name' => strtoupper($data['name']),
                    'email' => strtolower($data['email']),
                    'password' => Hash::make($data['password']),
                ]
            );
            $user->assignRole('client');

            // 2. Endereço
            $address = Address::create([
                'postal_code' => preg_replace('/\D/', '', $data['postal_code'] ?? ''),
                'street'      => strtoupper($data['street'] ?? ''),
                'number'      => strtoupper($data['number'] ?? 'S/N'),
                'complement'  => strtoupper($data['complement'] ?? ''),
                'district'    => strtoupper($data['district'] ?? ''),
                'city'        => strtoupper($data['city'] ?? ''),
                'state'       => strtoupper($data['state'] ?? 'RS'),
            ]);

            // Limpeza do campo Income (Renda)
            $income = $data['income'] ?? null;
            if ($income) {
                // Remove "R$", pontos de milhar e troca a vírgula por ponto
                $income = str_replace(['R$', '.', ' '], '', $income);
                $income = str_replace(',', '.', $income);
                $income = (float) $income;
            }

            // 3. Cliente (Campos novos e bancários adicionados)
            $client = Client::updateOrCreate(
                ['cpf_cnpj' => $cpfComMascara],
                [
                    'name'               => strtoupper($data['name']),
                    'rg'                 => strtoupper($data['rg']),
                    'father'             => strtoupper($data['father']),
                    'mother'             => strtoupper($data['mother']),
                    'occupation'         => strtoupper($data['occupation']),
                    'income'             => $income ?? null,
                    'bank_id'            => $data['bank_id'],
                    'bank_agency'        => strtoupper($data['bank_agency']),
                    'current_account'    => strtoupper($data['current_account']),
                    'email'              => strtolower($data['email']),
                    'birth_date'         => $birthDateDb,
                    'whatsapp'           => $data['whatsapp'], // Com máscara
                    'address_id'         => $address->id,
                    'registered_user_id' => $user->id,
                    'situation'          => 'disabled',
                    'register_origin'    => 'site'
                ]
            );

            // 4. Mapeamento e Upload de Documentos
            $documentsMap = [
                'cnh_rg'             => 'DOCUMENTO PESSOAL',
                'document_income'    => 'COMPROVANTE DE RENDA',
                'document_residence' => 'COMPROVANTE DE RESIDÊNCIA',
            ];

            foreach ($documentsMap as $inputName => $docTypeName) {
                if ($request->hasFile($inputName)) {
                    $file = $request->file($inputName);
                    $path = $file->store('documents', 'public');
                    $docType = DocumentType::where('name', $docTypeName)->first();

                    if ($docType) {
                        // UpdateOrCreate para evitar duplicidade do mesmo tipo para o mesmo cliente
                        Document::updateOrCreate(
                            [
                                'client_id'        => $client->id,
                                'document_type_id' => $docType->id,
                            ],
                            [
                                'user_id' => $user->id,
                                'path'    => $path,
                            ]
                        );
                    }
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
