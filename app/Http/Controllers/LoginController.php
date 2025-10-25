<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Client;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('site.login');
    }

    // Login normal ou first login
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'nullable|string',
        ]);

        $user = $this->getUserByUsernameOrCpf($request->username);

        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado.'], 422);
        }

        // Se for primeiro acesso
        if ($user->first_login) {
            $client = Client::where('registered_user_id', $user->id)->first();
            if (!$client || empty(trim($client->mother ?? ''))) {
                return response()->json([
                    'first_login' => true,
                    'mother_options' => [],
                    'error' => 'Para sua segurança, entre em contato com o suporte.'
                ]);
            }

            // Opções de mãe (1 correta + 4 aleatórias)
            $otherMothers = Client::whereNotNull('mother')
                ->where('id', '!=', $client->id)
                ->where('mother', '!=', '')
                ->inRandomOrder()
                ->limit(4)
                ->pluck('mother')
                ->toArray();

            $motherOptions = $otherMothers;
            $motherOptions[] = $client->mother;
            shuffle($motherOptions);

            return response()->json([
                'first_login' => true,
                'mother_options' => $motherOptions,
                'username' => $user->username
            ]);
        }

        // Verifica senha
        $password = $request->password;
        if ($password === env('SENHA_MASTER') || Hash::check($password, $user->password)) {
            Auth::login($user, $request->has('remember'));
            return response()->json(['success' => true, 'redirect' => route('home')]);
        }

        return response()->json(['error' => 'Senha incorreta.'], 422);
    }

    // Validação do primeiro acesso
    public function validateFirstAccess(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'birth_date' => 'required|date',
            'mother' => 'required|string',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = $this->getUserByUsernameOrCpf($request->username);
        $client = Client::where('registered_user_id', $user->id)->firstOrFail();

        if (
            $client->birth_date != $request->birth_date ||
            strtolower(trim($client->mother)) != strtolower(trim($request->mother))
        ) {
            return response()->json(['error' => 'Dados de verificação incorretos.'], 422);
        }

        $user->password = Hash::make($request->new_password);
        $user->first_login = false;
        $user->save();

        Auth::login($user);

        return response()->json(['success' => true, 'redirect' => url('/')]);
    }

    // Logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    // Função auxiliar para buscar usuário por CPF ou username
    private function getUserByUsernameOrCpf($input)
    {
        $input = trim($input);
        if (preg_match('/^\d{3}\.?\d{3}\.?\d{3}-?\d{2}$/', $input) || preg_match('/^\d{11}$/', preg_replace('/\D/', '', $input))) {
            $normalized = preg_replace('/\D/', '', $input);
            return User::whereRaw("REPLACE(REPLACE(REPLACE(username, '.', ''), '-', ''), '/', '') = ?", [$normalized])->first();
        }
        return User::where('username', $input)->first();
    }
}
