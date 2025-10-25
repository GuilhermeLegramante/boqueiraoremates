@extends('site.master')

@section('title', 'Login - Boqueirão Remates')

@section('content')
    <section class="flex justify-center items-center py-16 bg-gray-100 min-h-screen">
        <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md relative">
            <h2 class="text-2xl font-bold text-center mb-6">Acesse sua conta</h2>

            <form id="loginForm" class="space-y-5">
                @csrf
                <div>
                    <label for="username" class="block font-semibold mb-1">Usuário ou CPF</label>
                    <input type="text" name="username" id="username"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none"
                        placeholder="Digite seu usuário ou CPF" required>
                    <p id="usernameError" class="text-red-500 text-sm mt-1 hidden"></p>
                </div>

                {{-- Campo senha (inicialmente oculto) --}}
                <div id="passwordContainer" class="space-y-2 hidden">
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

                <button type="submit" id="loginBtn"
                    class="w-full bg-green-700 text-white py-2 rounded-lg font-semibold hover:bg-green-800 transition-all flex justify-center items-center">
                    <span id="btnText">Entrar</span>
                    <svg id="btnSpinner" class="animate-spin h-5 w-5 text-white ml-2 hidden"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 11-8 8h4l-3 3 3 3h-4z"></path>
                    </svg>
                </button>

                <p id="generalError" class="text-red-500 text-sm mt-2 hidden"></p>
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
            const usernameError = document.getElementById('usernameError');
            const passwordError = document.getElementById('passwordError');
            const generalError = document.getElementById('generalError');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');

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

            // Primeiro acesso
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
                        generalError.classList.add('hidden');

                        motherOptions.innerHTML = data.mother_options.map(name => `
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

            // Submit do login
            loginForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                usernameError.classList.add('hidden');
                passwordError.classList.add('hidden');
                generalError.classList.add('hidden');

                // Spinner
                btnText.classList.add('hidden');
                btnSpinner.classList.remove('hidden');

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

                    const contentType = res.headers.get('content-type');

                    if (contentType && contentType.includes('application/json')) {
                        const data = await res.json();
                        if (data.success && data.redirect) {
                            window.location.href = data.redirect;
                        } else if (data.error) {
                            // Exibir mensagem adequada
                            if (data.error.includes('Usuário')) usernameError.textContent = data.error,
                                usernameError.classList.remove('hidden');
                            else if (data.error.includes('Senha')) passwordError.textContent = data
                                .error, passwordError.classList.remove('hidden');
                            else generalError.textContent = data.error, generalError.classList.remove(
                                'hidden');
                        }
                    } else if (res.redirected) {
                        window.location.href = res.url;
                    }
                } catch (err) {
                    console.error(err);
                    generalError.textContent = 'Erro de comunicação com o servidor.';
                    generalError.classList.remove('hidden');
                } finally {
                    btnText.classList.remove('hidden');
                    btnSpinner.classList.add('hidden');
                }
            });
        });
    </script>
@endsection
