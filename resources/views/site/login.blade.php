@extends('site.master')

@section('title', 'Login - Boqueirão Remates')

@section('content')
    <section class="flex justify-center items-center py-16 bg-gray-100 min-h-screen">
        <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md relative">
            <h2 class="text-2xl font-bold text-center mb-6">Acesse sua conta</h2>

            {{-- Mensagem de erro geral --}}
            <p id="generalError" class="text-red-500 text-center mb-4 hidden"></p>

            <form id="loginForm" class="space-y-5">
                @csrf

                {{-- Campo username --}}
                <div>
                    <label for="username" class="block font-semibold mb-1">Usuário ou CPF</label>
                    <input type="text" name="username" id="username"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none"
                        placeholder="Digite seu usuário ou CPF" required>
                </div>

                {{-- Campo senha (para login normal) - escondido por padrão --}}
                <div id="passwordContainer" class="space-y-2 hidden">
                    <label for="password" class="block font-semibold mb-1">Senha</label>
                    <input type="password" name="password" id="password"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none"
                        placeholder="Digite sua senha">
                </div>

                {{-- Campos do primeiro acesso (inicialmente ocultos) --}}
                <div id="firstAccessFields" class="hidden space-y-4">
                    <div>
                        <label for="birth_date" class="block font-semibold mb-1">Data de nascimento</label>
                        <input type="date" name="birth_date" id="birth_date"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none">
                    </div>

                    <div>
                        <label class="block font-semibold mb-1">Nome da mãe</label>
                        <div id="motherOptions" class="space-y-2"></div>
                    </div>

                    <div>
                        <label for="new_password" class="block font-semibold mb-1">Nova senha</label>
                        <input type="password" name="new_password" id="new_password"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none"
                            placeholder="Crie sua nova senha">
                    </div>

                    <div>
                        <label for="new_password_confirmation" class="block font-semibold mb-1">Confirme a nova
                            senha</label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none"
                            placeholder="Confirme a nova senha">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="mr-2">
                        <span>Lembrar-me</span>
                    </label>
                    <button type="button" id="forgotPasswordBtn" class="text-green-700 hover:underline text-sm">Esqueci
                        minha senha</button>
                </div>

                <button id="loginBtn" type="submit"
                    class="w-full bg-green-700 text-white py-2 rounded-lg font-semibold hover:bg-green-800 transition-all relative">
                    <span id="btnText">Entrar</span>
                    <span id="btnSpinner" class="hidden absolute right-4 top-1/2 -translate-y-1/2">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                    </span>
                </button>
            </form>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const usernameInput = document.getElementById('username');
            const passwordContainer = document.getElementById('passwordContainer');
            const firstAccessFields = document.getElementById('firstAccessFields');
            const motherOptions = document.getElementById('motherOptions');
            const loginForm = document.getElementById('loginForm');
            const generalError = document.getElementById('generalError');
            const loginBtn = document.getElementById('loginBtn');
            const btnSpinner = document.getElementById('btnSpinner');
            const btnText = document.getElementById('btnText');

            // Máscara CPF
            usernameInput.addEventListener('input', () => {
                let value = usernameInput.value;
                if (/^\d/.test(value)) {
                    value = value.replace(/\D/g, '')
                        .replace(/(\d{3})(\d)/, '$1.$2')
                        .replace(/(\d{3})(\d)/, '$1.$2')
                        .replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                    usernameInput.value = value;
                }
            });

            // Verifica se é primeiro acesso via AJAX
            usernameInput.addEventListener('blur', async () => {
                const username = usernameInput.value.trim();
                if (!username) return;

                const token = document.querySelector('input[name="_token"]').value;
                try {
                    const res = await fetch('{{ route('check.first_login') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            username
                        })
                    });
                    const data = await res.json();

                    if (data.first_login) {
                        passwordContainer.classList.add('hidden');
                        firstAccessFields.classList.remove('hidden');

                        // Opções da mãe
                        const options = data.mother_options || ['Maria das Dores', 'Joana Silva',
                            'Ana Souza', 'Carla Oliveira', 'Marta Santos'
                        ];
                        motherOptions.innerHTML = options.map(name => `
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="mother" value="${name}" required>
                        <span>${name}</span>
                    </label>
                `).join('');
                    } else {
                        passwordContainer.classList.remove('hidden');
                        firstAccessFields.classList.add('hidden');
                    }
                } catch (err) {
                    console.error(err);
                }
            });

            // Submit do formulário
            loginForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                generalError.classList.add('hidden');
                btnSpinner.classList.remove('hidden');
                btnText.classList.add('hidden');

                const formData = new FormData(loginForm);
                const isFirstLogin = !firstAccessFields.classList.contains('hidden');
                const url = isFirstLogin ? '{{ route('first_access.validate') }}' :
                    '{{ route('login.submit') }}';

                try {
                    const res = await fetch(url, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (res.status === 422) {
                        const data = await res.json();
                        generalError.textContent = data.error || 'Dados incorretos.';
                        generalError.classList.remove('hidden');
                        return;
                    }

                    const data = await res.json();
                    if (data.success && data.redirect) {
                        window.location.href = data.redirect;
                    } else if (data.error) {
                        generalError.textContent = data.error;
                        generalError.classList.remove('hidden');
                    }
                } catch (err) {
                    console.error(err);
                    generalError.textContent = 'Erro de comunicação com o servidor.';
                    generalError.classList.remove('hidden');
                } finally {
                    btnSpinner.classList.add('hidden');
                    btnText.classList.remove('hidden');
                }
            });
        });
    </script>
@endsection
