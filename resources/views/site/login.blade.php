@extends('site.master')

@section('title', 'Login - Boqueirão Remates')

@section('content')
    <section class="flex justify-center items-center py-16 bg-gray-100 min-h-screen">
        <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">
            <h2 class="text-2xl font-bold text-center mb-6">Acesse sua conta</h2>

            <form id="loginForm" class="space-y-5">
                @csrf

                {{-- Campo username --}}
                <div>
                    <label for="username" class="block font-semibold mb-1">Usuário ou CPF</label>
                    <input type="text" name="username" id="username"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none"
                        placeholder="Digite seu usuário ou CPF" required>
                    <p id="usernameError" class="text-red-500 text-sm mt-1 hidden"></p>
                </div>

                {{-- Campo senha (para login normal) --}}
                <div id="passwordContainer" class="space-y-2">
                    <label for="password" class="block font-semibold mb-1">Senha</label>
                    <input type="password" name="password" id="password"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none"
                        placeholder="Digite sua senha">
                    <p id="passwordError" class="text-red-500 text-sm mt-1 hidden"></p>
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
                    class="w-full bg-green-700 text-white py-2 rounded-lg font-semibold hover:bg-green-800 transition-all">
                    Entrar
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

                const formData = new FormData(loginForm);
                const isFirstLogin = firstAccessFields.classList.contains('hidden') === false;
                let url = isFirstLogin ? '{{ route('first_access.validate') }}' :
                    '{{ route('login.submit') }}';

                try {
                    const res = await fetch(url, {
                        method: 'POST',
                        body: formData
                    });

                    if (res.redirected) {
                        window.location.href = res.url;
                    } else {
                        const data = await res.json();
                        alert(data.error || 'Erro no login');
                    }
                } catch (err) {
                    console.error(err);
                }
            });
        });
    </script>
@endsection
