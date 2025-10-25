<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Client;

class LoginController extends Controller
{
    // Exibe a tela de login
    public function showLoginForm()
    {
        return view('site.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'nullable|string',
        ]);

        $usernameInput = $request->username;
        $password = $request->password;

        // Normaliza o CPF (remove máscara)
        $normalizedUsername = preg_replace('/\D/', '', $usernameInput);

        // Busca usuário, comparando sem máscara
        $user = User::whereRaw(
            "REPLACE(REPLACE(REPLACE(username, '.', ''), '-', ''), '/', '') = ?",
            [$normalizedUsername]
        )->first();

        if (!$user) {
            return back()->withErrors(['username' => 'Usuário não encontrado.']);
        }

        // Se for primeiro login
        if ($user->first_login) {
            // Retorna para a view com flag first_login = true
            return back()->with('first_login', true)
                ->with('username', $user->username);
        }

        // Verifica senha ou senha master
        if ($password === env('SENHA_MASTER') || Hash::check($password, $user->password)) {
            Auth::login($user, $request->has('remember'));
            return redirect()->intended('/');
        }

        return back()->withErrors(['password' => 'Senha incorreta.']);
    }


    // Validação do primeiro acesso (data de nascimento + mãe)
    public function validateFirstAccess(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'birth_date' => 'required|date',
            'mother' => 'required|string',
            'new_password' => 'required|min:6|confirmed',
        ]);

        // Normaliza CPF do input
        $normalizedUsername = preg_replace('/\D/', '', $request->username);

        // Busca usuário, ignorando máscara
        $user = User::whereRaw(
            "REPLACE(REPLACE(REPLACE(username, '.', ''), '-', ''), '/', '') = ?",
            [$normalizedUsername]
        )->firstOrFail();

        $client = Client::where('registered_user_id', $user->id)->firstOrFail();

        // Valida dados de segurança
        if ($client->birth_date !== $request->birth_date || $client->mother !== $request->mother) {
            return back()->withErrors(['username' => 'Dados de verificação incorretos.']);
        }

        // Atualiza senha e marca como logado
        $user->password = Hash::make($request->new_password);
        $user->first_login = false;
        $user->save();

        Auth::login($user);

        return redirect()->intended('/');
    }


    // Logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    // 🔹 Rotas de recuperação de senha
    public function showRecoverForm()
    {
        return view('site.recover');
    }

    public function recoverValidate(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'birth_date' => 'required|date',
            'mother' => 'required|string',
        ]);

        $username = preg_replace('/\D/', '', $request->username) ?: $request->username;
        $user = User::where('username', $username)->first();
        if (!$user) return response()->json(['error' => 'Usuário não encontrado.']);

        $client = Client::where('registered_user_id', $user->id)->first();
        if (!$client) return response()->json(['error' => 'Cliente não encontrado.']);

        if ($client->birth_date === $request->birth_date && $client->mother === $request->mother) {
            return response()->json(['verified' => true]);
        }

        return response()->json(['error' => 'Dados incorretos.']);
    }

    public function recoverSetNewPassword(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $username = preg_replace('/\D/', '', $request->username) ?: $request->username;
        $user = User::where('username', $username)->first();
        if (!$user) return response()->json(['error' => 'Usuário não encontrado.']);

        $user->password = Hash::make($request->password);
        $user->first_login = false;
        $user->save();

        return response()->json(['success' => true, 'redirect' => route('login')]);
    }
}
