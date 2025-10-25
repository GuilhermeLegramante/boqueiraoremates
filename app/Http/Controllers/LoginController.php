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

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'nullable|string',
        ]);

        $usernameInput = trim($request->username);
        $password = $request->password;

        // Detecta se é CPF
        $normalizedUsername = preg_replace('/\D/', '', $usernameInput);
        $isCpf = is_numeric($normalizedUsername) && strlen($normalizedUsername) === 11;

        if ($isCpf) {
            // Busca CPF sem máscara
            $user = User::whereRaw(
                "REPLACE(REPLACE(REPLACE(username, '.', ''), '-', ''), '/', '') = ?",
                [$normalizedUsername]
            )->first();
        } else {
            // Username textual
            $user = User::whereRaw('LOWER(TRIM(username)) = ?', [strtolower($usernameInput)])->first();
        }

        if (!$user) {
            return $request->wantsJson()
                ? response()->json(['error' => 'Usuário não encontrado.'], 422)
                : back()->withErrors(['username' => 'Usuário não encontrado.']);
        }

        // Primeiro login
        if ($user->first_login) {
            return $request->wantsJson()
                ? response()->json(['first_login' => true, 'username' => $user->username])
                : back()->with('first_login', true)->with('username', $user->username);
        }

        // Verifica senha ou senha master
        if ($password === env('SENHA_MASTER') || Hash::check($password, $user->password)) {
            Auth::login($user, $request->has('remember'));
            return $request->wantsJson()
                ? response()->json(['success' => true, 'redirect' => url('/')])
                : redirect()->intended('/');
        }

        return $request->wantsJson()
            ? response()->json(['error' => 'Senha incorreta.'], 422)
            : back()->withErrors(['password' => 'Senha incorreta.']);
    }

    // Primeiro acesso: valida data de nascimento e mãe
    public function validateFirstAccess(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'birth_date' => 'required|date',
            'mother' => 'required|string',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $usernameInput = trim($request->username);
        $normalizedUsername = preg_replace('/\D/', '', $usernameInput);
        $isCpf = is_numeric($normalizedUsername) && strlen($normalizedUsername) === 11;

        if ($isCpf) {
            $user = User::whereRaw(
                "REPLACE(REPLACE(REPLACE(username, '.', ''), '-', ''), '/', '') = ?",
                [$normalizedUsername]
            )->firstOrFail();
        } else {
            $user = User::whereRaw('LOWER(TRIM(username)) = ?', [strtolower($usernameInput)])->firstOrFail();
        }

        $client = Client::where('registered_user_id', $user->id)->firstOrFail();

        if ($client->birth_date !== $request->birth_date || $client->mother !== $request->mother) {
            return response()->json(['error' => 'Dados de verificação incorretos.'], 422);
        }

        $user->password = Hash::make($request->new_password);
        $user->first_login = false;
        $user->save();

        Auth::login($user);

        return response()->json(['success' => true, 'redirect' => url('/')]);
    }

    // Checa primeiro login e traz opções da mãe
    public function checkFirstLogin(Request $request)
    {
        $request->validate(['username' => 'required']);

        $usernameInput = trim($request->username);
        $normalizedUsername = preg_replace('/\D/', '', $usernameInput);
        $isCpf = is_numeric($normalizedUsername) && strlen($normalizedUsername) === 11;

        if ($isCpf) {
            $user = User::whereRaw(
                "REPLACE(REPLACE(REPLACE(username, '.', ''), '-', ''), '/', '') = ?",
                [$normalizedUsername]
            )->first();
        } else {
            $user = User::whereRaw('LOWER(TRIM(username)) = ?', [strtolower($usernameInput)])->first();
        }

        if (!$user || !$user->first_login) {
            return response()->json(['first_login' => false]);
        }

        $client = Client::where('registered_user_id', $user->id)->first();
        if (!$client) return response()->json(['first_login' => false]);

        $correctMother = $client->mother;

        $otherMothers = Client::where('id', '!=', $client->id)
            ->inRandomOrder()
            ->limit(4)
            ->pluck('mother')
            ->toArray();

        $motherOptions = $otherMothers;
        $motherOptions[] = $correctMother;
        shuffle($motherOptions);

        return response()->json([
            'first_login' => true,
            'mother_options' => $motherOptions
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
