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

        // CPF?
        if (preg_match('/^\d{3}\.?\d{3}\.?\d{3}-?\d{2}$/', $usernameInput) || preg_match('/^\d{11}$/', preg_replace('/\D/', '', $usernameInput))) {
            $normalizedUsername = preg_replace('/\D/', '', $usernameInput);
            $user = User::whereRaw(
                "REPLACE(REPLACE(REPLACE(username, '.', ''), '-', ''), '/', '') = ?",
                [$normalizedUsername]
            )->first();
        } else {
            $user = User::whereRaw('LOWER(username) = ?', [strtolower($usernameInput)])->first();
        }

        if (!$user) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Usuário não encontrado.'], 422);
            }
            return back()->withErrors(['username' => 'Usuário não encontrado.']);
        }

        // Primeiro acesso
        if ($user->first_login) {
            if ($request->wantsJson()) {
                return response()->json(['first_login' => true, 'username' => $user->username]);
            }
            return back()->with('first_login', true)->with('username', $user->username);
        }

        // Senha normal ou master
        if ($password === env('SENHA_MASTER') || Hash::check($password, $user->password)) {
            Auth::login($user, $request->has('remember'));
            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'redirect' => url('/')]);
            }
            return redirect()->intended('/');
        }

        if ($request->wantsJson()) {
            return response()->json(['error' => 'Senha incorreta.'], 422);
        }
        return back()->withErrors(['password' => 'Senha incorreta.']);
    }

    // Primeiro acesso: valida nascimento + mãe + nova senha
    public function validateFirstAccess(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'birth_date' => 'required|date',
            'mother' => 'required|string',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $username = preg_replace('/\D/', '', $request->username);
        $user = User::whereRaw(
            "REPLACE(REPLACE(REPLACE(username, '.', ''), '-', ''), '/', '') = ?",
            [$username]
        )->firstOrFail();

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

    // Check primeiro login + opções de mãe
    public function checkFirstLogin(Request $request)
    {
        $request->validate(['username' => 'required']);
        $input = trim($request->username);

        if (preg_match('/^\d{3}\.?\d{3}\.?\d{3}-?\d{2}$/', $input) || preg_match('/^\d{11}$/', preg_replace('/\D/', '', $input))) {
            $normalizedUsername = preg_replace('/\D/', '', $input);
            $user = User::whereRaw(
                "REPLACE(REPLACE(REPLACE(username, '.', ''), '-', ''), '/', '') = ?",
                [$normalizedUsername]
            )->first();
        } else {
            $user = User::whereRaw('LOWER(username) = ?', [strtolower($input)])->first();
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
