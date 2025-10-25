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

        $usernameInput = $request->username;
        $password = $request->password;

        // Detecta CPF ou username normal
        if (preg_match('/^\d{3}\.?\d{3}\.?\d{3}-?\d{2}$/', $usernameInput) || preg_match('/^\d{11}$/', preg_replace('/\D/', '', $usernameInput))) {
            $normalizedUsername = preg_replace('/\D/', '', $usernameInput);
            $user = User::whereRaw(
                "REPLACE(REPLACE(REPLACE(username, '.', ''), '-', ''), '/', '') = ?",
                [$normalizedUsername]
            )->first();
        } else {
            $user = User::where('username', $usernameInput)->first();
        }

        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado.'], 422);
        }

        // Primeiro acesso
        if ($user->first_login) {
            return response()->json([
                'first_login' => true,
                'username' => $user->username
            ]);
        }

        // Verifica senha ou senha master
        if ($password === env('SENHA_MASTER') || Hash::check($password, $user->password)) {
            Auth::login($user, $request->has('remember'));
            return response()->json(['success' => true, 'redirect' => url('/')]);
        }

        return response()->json(['error' => 'Senha incorreta.'], 422);
    }

    public function checkFirstLogin(Request $request)
    {
        $request->validate(['username' => 'required']);

        $input = $request->username;

        // Detecta CPF
        if (preg_match('/^\d{3}\.?\d{3}\.?\d{3}-?\d{2}$/', $input)) {
            $normalizedUsername = preg_replace('/\D/', '', $input);
            $user = User::whereRaw(
                "REPLACE(REPLACE(REPLACE(username, '.', ''), '-', ''), '/', '') = ?",
                [$normalizedUsername]
            )->first();
        } else {
            $user = User::where('username', $input)->first();
        }

        if (!$user) {
            return response()->json([
                'first_login' => false,
                'message' => 'Usuário não encontrado.'
            ]);
        }

        if (!$user->first_login) {
            return response()->json(['first_login' => false]);
        }

        $client = Client::where('registered_user_id', $user->id)->first();

        if (!$client) {
            return response()->json([
                'first_login' => false,
                'message' => 'Cliente não encontrado.'
            ]);
        }

        $correctMother = trim($client->mother);

        if (!$correctMother) {
            return response()->json([
                'first_login' => false,
                'message' => 'Não há registro do nome da mãe cadastrado para este usuário.'
            ]);
        }

        // Pega 4 mothers aleatórias válidas
        $otherMothers = Client::where('id', '!=', $client->id)
            ->whereNotNull('mother')
            ->whereRaw("TRIM(mother) != ''")
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

    public function validateFirstAccess(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'birth_date' => 'required|date',
            'mother' => 'required|string',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $usernameInput = $request->username;

        if (preg_match('/^\d{3}\.?\d{3}\.?\d{3}-?\d{2}$/', $usernameInput) || preg_match('/^\d{11}$/', preg_replace('/\D/', '', $usernameInput))) {
            $normalizedUsername = preg_replace('/\D/', '', $usernameInput);
            $user = User::whereRaw(
                "REPLACE(REPLACE(REPLACE(username, '.', ''), '-', ''), '/', '') = ?",
                [$normalizedUsername]
            )->firstOrFail();
        } else {
            $user = User::where('username', $usernameInput)->firstOrFail();
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

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
