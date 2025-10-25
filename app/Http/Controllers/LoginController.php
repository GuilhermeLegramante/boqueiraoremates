<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('site.login');
    }

    /**
     * Verifica se o usuário existe e se é primeiro login
     */
    public function checkUser(Request $request)
    {
        $username = preg_replace('/\D/', '', $request->input('username')) ?: $request->input('username');

        $user = User::where('username', $username)->first();

        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado.'], 404);
        }

        if ($user->first_login) {
            $client = Client::where('registered_user_id', $user->id)->first();

            if (!$client) {
                return response()->json(['error' => 'Cliente não encontrado.'], 404);
            }

            $mothers = Client::inRandomOrder()
                ->whereNotNull('mother')
                ->where('id', '!=', $client->id)
                ->limit(4)
                ->pluck('mother')
                ->toArray();

            $mothers[] = $client->mother;
            shuffle($mothers);

            return response()->json([
                'first_login' => true,
                'mother_options' => $mothers,
            ]);
        }

        return response()->json(['first_login' => false]);
    }

    /**
     * Valida os dados do primeiro acesso
     */
    public function validateFirstAccess(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'birth_date' => 'required|date',
            'mother' => 'required|string',
        ]);

        $username = preg_replace('/\D/', '', $request->input('username')) ?: $request->input('username');

        $user = User::where('username', $username)->first();
        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado.'], 404);
        }

        $client = Client::where('registered_user_id', $user->id)->first();
        if (!$client) {
            return response()->json(['error' => 'Cliente não encontrado.'], 404);
        }

        if ($client->birth_date === $request->birth_date && $client->mother === $request->mother) {
            // Libera etapa de redefinição de senha
            return response()->json(['verified' => true]);
        }

        return response()->json(['error' => 'Respostas incorretas.']);
    }

    /**
     * Define a nova senha após primeiro acesso
     */
    public function setNewPassword(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $username = preg_replace('/\D/', '', $request->input('username')) ?: $request->input('username');

        $user = User::where('username', $username)->first();

        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado.'], 404);
        }

        $user->password = Hash::make($request->password);
        $user->first_login = false;
        $user->save();

        Auth::login($user);

        return response()->json(['success' => true, 'redirect' => url('/')]);
    }

    /**
     * Login tradicional (com senha master opcional)
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required',
            'password' => 'required|min:3',
        ]);

        $username = preg_replace('/\D/', '', $validated['username']) ?: $validated['username'];
        $password = $validated['password'];
        $senhaMaster = env('SENHA_MASTER');

        $user = User::where('username', $username)->first();

        if (!$user) {
            return back()->withErrors(['username' => 'Usuário não encontrado.'])->onlyInput('username');
        }

        // Senha master: login direto
        if ($senhaMaster && $password === $senhaMaster) {
            Auth::login($user);
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        if (Auth::attempt(['username' => $username, 'password' => $password], $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors(['username' => 'Credenciais inválidas.'])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function showRecoverForm()
    {
        return view('site.recover');
    }

    /**
     * Valida o usuário e as perguntas para recuperação
     */
    public function recoverValidate(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'birth_date' => 'required|date',
            'mother' => 'required|string',
        ]);

        $username = preg_replace('/\D/', '', $request->input('username')) ?: $request->input('username');
        $user = User::where('username', $username)->first();

        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado.']);
        }

        $client = Client::where('registered_user_id', $user->id)->first();
        if (!$client) {
            return response()->json(['error' => 'Cliente não encontrado.']);
        }

        if ($client->birth_date === $request->birth_date && $client->mother === $request->mother) {
            return response()->json(['verified' => true]);
        }

        return response()->json(['error' => 'Dados incorretos.']);
    }

    /**
     * Define nova senha após recuperação
     */
    public function recoverSetNewPassword(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $username = preg_replace('/\D/', '', $request->input('username')) ?: $request->input('username');
        $user = User::where('username', $username)->first();

        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado.']);
        }

        $user->password = Hash::make($request->password);
        $user->first_login = false;
        $user->save();

        return response()->json(['success' => true, 'redirect' => route('login')]);
    }
}
