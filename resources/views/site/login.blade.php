@extends('site.master')

@section('title', 'Login - Boqueirão Remates')

@section('content')

    <section class="flex mt-8 justify-center items-start py-16 bg-gray-100 min-h-screen">
        <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md flex flex-col overflow-auto">
            <h2 class="text-2xl font-bold text-center mb-6">Acesse sua conta</h2>

            <form id="loginForm" class="space-y-5">
                @csrf

                {{-- Usuário / CPF --}}
                <div class="mt-8">
                    <label for="username" class="block font-semibold mb-1">Usuário ou CPF</label>
                    <input type="text" name="username" id="username"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none"
                        placeholder="Digite seu usuário ou CPF" required>
                    <p id="usernameError" class="text-red-500 text-sm mt-1 hidden"></p>
                </div>

                {{-- Senha (login normal) --}}
                <div id="passwordContainer" class="hidden space-y-2">
                    <label for="password" class="block font-semibold mb-1">Senha</label>
                    <input type="password" name="password" id="password"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none"
                        placeholder="Digite sua senha">
                    <p id="passwordError" class="text-red-500 text-sm mt-1 hidden"></p>
                </div>

                {{-- Campos do primeiro acesso --}}
                <div id="firstAccessFields" class="hidden space-y-4 transition-all duration-300 opacity-0">
                    <div>
                        <label for="birth_date" class="block font-semibold mb-1">Data de nascimento</label>
                        <input type="date" name="birth_date" id="birth_date"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none">
                        <p id="birthDateError" class="text-red-500 text-sm mt-1 hidden"></p>
                    </div>

                    <div>
                        <label class="block font-semibold mb-1">Nome da mãe</label>
                        <div id="motherOptions" class="space-y-2"></div>
                        <p id="motherError" class="text-red-500 text-sm mt-1 hidden"></p>
                    </div>

                    <div>
                        <label for="new_password" class="block font-semibold mb-1">Nova senha</label>
                        <input type="password" name="new_password" id="new_password"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none"
                            placeholder="Crie sua nova senha">
                        <p id="newPasswordError" class="text-red-500 text-sm mt-1 hidden"></p>
                    </div>

                    <div>
                        <label for="new_password_confirmation" class="block font-semibold mb-1">Confirme a nova
                            senha</label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none"
                            placeholder="Confirme a nova senha">
                    </div>
                </div>

                {{-- Lembrar e esqueci senha --}}
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="mr-2">
                        <span>Lembrar-me</span>
                    </label>
                    <button type="button" id="forgotPasswordBtn" class="text-green-700 hover:underline text-sm">
                        Esqueci minha senha
                    </button>
                </div>

                {{-- Botão de login --}}
                <button id="loginBtn" type="submit"
                    class="w-full bg-green-700 text-white py-2 rounded-lg font-semibold hover:bg-green-800 transition-all relative">
                    <span id="loginText">Entrar</span>
                    <svg id="loginSpinner" class="animate-spin h-5 w-5 text-white absolute right-4 top-2.5 hidden"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 11-8 8z"></path>
                    </svg>
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
            const loginBtn = document.getElementById('loginBtn');
            const loginText = document.getElementById('loginText');
            const loginSpinner = document.getElementById('loginSpinner');

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

            // Check first login
            usernameInput.addEventListener('blur', async () => {
                const username = usernameInput.value.trim();
                if (!username) return;

                const token = document.querySelector('input[name="_token"]').value;
                try {
                    const res = await fetch('{{ route('check.first_login') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token
                        },
                        body: JSON.stringify({
                            username
                        })
                    });
                    const data = await res.json();

                    if (data.first_login) {
                        passwordContainer.classList.add('hidden');
                        firstAccessFields.classList.remove('hidden');
                        firstAccessFields.style.opacity = 1;

                        const options = data.mother_options || ['Maria', 'Joana', 'Ana', 'Carla',
                            'Marta'
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
                        firstAccessFields.style.opacity = 0;
                    }
                } catch (err) {
                    console.error(err);
                }
            });

            // Submit
            loginForm.addEventListener('submit', async (e) => {
                e.preventDefault();

                // mostra spinner
                loginText.classList.add('hidden');
                loginSpinner.classList.remove('hidden');

                const formData = new FormData(loginForm);
                const isFirstLogin = !firstAccessFields.classList.contains('hidden');
                let url = isFirstLogin ? '{{ route('first_access.validate') }}' :
                    '{{ route('login.submit') }}';

                try {
                    const res = await fetch(url, {
                        method: 'POST',
                        body: formData
                    });

                    if (res.redirected) {
                        window.location.href = res.url;
                        return;
                    }

                    const data = await res.json();
                    alert(data.error || 'Erro no login');
                } catch (err) {
                    alert('Erro de comunicação com o servidor.');
                    console.error(err);
                } finally {
                    loginText.classList.remove('hidden');
                    loginSpinner.classList.add('hidden');
                }
            });
        });
    </script>
@endsection
