@extends('site.master')

@section('title', 'Login - Boqueirão Remates')

@section('content')
    <section class="flex justify-center bg-gray-100 pt-48 pb-16 min-h-screen">
        <div class="w-full max-w-md">
            <div class="bg-white p-8 rounded-xl shadow-lg flex flex-col space-y-6">
                <h2 class="text-3xl font-bold text-center mb-6">Acesse sua conta</h2>

                {{-- Aviso primeiro acesso --}}
                <div id="firstAccessNotice"
                    class="hidden mb-6 p-5 bg-blue-50 border-l-4 border-blue-500 text-blue-800 rounded-lg shadow-sm text-sm font-medium transition-all duration-300 hover:shadow-md">
                    <strong>Atenção:</strong> Estamos migrando para uma nova plataforma para garantir mais segurança e
                    qualidade no atendimento. Por favor, preencha os dados solicitados com atenção.
                </div>

                {{-- Botão WhatsApp --}}
                <div class="mt-4 text-center">
                    <a href="https://wa.me/5555997331395" target="_blank"
                        class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-green-500 to-green-700 hover:from-green-600 hover:to-green-800 text-white font-semibold py-3 px-5 rounded-full shadow-lg transition-all transform hover:-translate-y-1 hover:scale-105">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M20.52 3.48a11.81 11.81 0 0 0-16.68 0 11.74 11.74 0 0 0 0 16.68l-1.55 5.69 5.84-1.53a11.74 11.74 0 0 0 16.89-16.89zm-8.37 17.14c-1.15.05-2.28-.27-3.23-.91l-.23-.15-3.47.91.92-3.38-.15-.24a8.25 8.25 0 0 1 3.15-11.5 8.18 8.18 0 0 1 11.57 3.15 8.25 8.25 0 0 1-3.15 11.57 8.17 8.17 0 0 1-5.21 1.55zm4.68-7.86c-.25-.12-1.48-.73-1.71-.81-.23-.08-.4-.12-.57.12-.17.25-.66.81-.81.97-.15.17-.3.19-.55.07-.25-.12-1.06-.39-2.01-1.23-.74-.66-1.24-1.48-1.39-1.73-.15-.25-.02-.38.11-.5.11-.11.25-.3.37-.45.12-.15.16-.25.25-.42.08-.17.04-.31-.02-.43-.06-.12-.57-1.38-.78-1.88-.2-.5-.4-.43-.57-.44-.15-.01-.32-.01-.49-.01s-.43.06-.66.31c-.23.25-.89.87-.89 2.12 0 1.25.91 2.46 1.03 2.63.12.17 1.77 2.7 4.28 3.78.6.26 1.07.42 1.43.54.6.2 1.15.17 1.58.1.48-.08 1.48-.6 1.69-1.18.21-.58.21-1.08.15-1.19-.06-.12-.23-.17-.48-.29z" />
                        </svg>
                        Suporte via WhatsApp
                    </a>
                </div>

                <form id="loginForm" class="space-y-5">
                    @csrf

                    {{-- Erro geral --}}
                    <p id="formError" class="text-red-500 text-sm mt-1 hidden text-center"></p>

                    {{-- Usuário / CPF --}}
                    <div>
                        <label for="username" class="block font-semibold mb-1">Usuário ou CPF</label>
                        <input type="text" name="username" id="username"
                            class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none"
                            placeholder="Digite seu usuário ou CPF" required>
                        <p id="usernameError" class="text-red-500 text-sm mt-1 hidden"></p>
                    </div>

                    {{-- Senha normal --}}
                    <div id="passwordContainer" class="hidden space-y-2 transition-all duration-300">
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
                            <input type="text" name="birth_date" id="birth_date" placeholder="dd/mm/aaaa" maxlength="10"
                                inputmode="numeric"
                                class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none">
                            <p id="birth_dateError" class="text-red-500 text-sm mt-1 hidden"></p>
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
                            <p id="new_passwordError" class="text-red-500 text-sm mt-1 hidden"></p>
                        </div>

                        <div>
                            <label for="new_password_confirmation" class="block font-semibold mb-1">Confirme a nova
                                senha</label>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                                class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none"
                                placeholder="Confirme a nova senha">
                            <p id="new_password_confirmationError" class="text-red-500 text-sm mt-1 hidden"></p>
                        </div>
                    </div>

                    {{-- Lembrar-me e esqueci senha --}}
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" class="mr-2">
                            <span>Lembrar-me</span>
                        </label>
                        {{-- <button type="button" id="forgotPasswordBtn" class="text-green-700 hover:underline text-sm">
                            Esqueci minha senha
                        </button> --}}
                    </div>

                    {{-- Botão de login --}}
                    <button id="loginBtn" type="submit"
                        class="w-full bg-green-700 text-white py-2 rounded-lg font-semibold hover:bg-green-800 transition-all flex justify-center items-center relative">
                        <span id="loginText">Entrar</span>
                        <svg id="loginSpinner" class="animate-spin h-5 w-5 text-white ml-2 hidden"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 11-8 8z">
                            </path>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const input = document.getElementById('birth_date');
            const error = document.getElementById('birth_dateError');

            // Aplica máscara
            input.addEventListener('input', (e) => {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 8) value = value.slice(0, 8);

                if (value.length > 4) {
                    value = value.replace(/^(\d{2})(\d{2})(\d{0,4}).*/, '$1/$2/$3');
                } else if (value.length > 2) {
                    value = value.replace(/^(\d{2})(\d{0,2})/, '$1/$2');
                }

                e.target.value = value;
            });

            // Validação simples
            input.addEventListener('blur', () => {
                const regex = /^(\d{2})\/(\d{2})\/(\d{4})$/;
                if (input.value && !regex.test(input.value)) {
                    error.textContent = 'Data inválida. Use o formato dd/mm/aaaa.';
                    error.classList.remove('hidden');
                } else {
                    error.classList.add('hidden');
                }
            });

            // Converte para formato do banco (YYYY-MM-DD) antes de enviar o form
            const form = input.closest('form');
            if (form) {
                form.addEventListener('submit', () => {
                    const parts = input.value.split('/');
                    if (parts.length === 3) {
                        input.value = `${parts[2]}-${parts[1]}-${parts[0]}`;
                    }
                });
            }


            const usernameInput = document.getElementById('username');
            const passwordContainer = document.getElementById('passwordContainer');
            const firstAccessFields = document.getElementById('firstAccessFields');
            const motherOptions = document.getElementById('motherOptions');
            const loginForm = document.getElementById('loginForm');
            const loginText = document.getElementById('loginText');
            const loginSpinner = document.getElementById('loginSpinner');
            const formError = document.getElementById('formError');
            const firstAccessNotice = document.getElementById('firstAccessNotice');

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

            // Checar primeiro acesso
            usernameInput.addEventListener('blur', async () => {
                const username = usernameInput.value.trim();
                if (!username) return;

                try {
                    const token = document.querySelector('input[name="_token"]').value;
                    const res = await fetch('{{ route('login.submit') }}', {
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
                        firstAccessNotice.classList.remove('hidden');
                        passwordContainer.classList.add('hidden');
                        firstAccessFields.classList.remove('hidden');
                        firstAccessFields.style.opacity = 1;
                        formError.classList.add('hidden');

                        motherOptions.innerHTML = (data.mother_options.length ? data.mother_options : [
                                'Não informado'
                            ])
                            .map(name => `
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="radio" name="mother" value="${name}" required>
                            <span>${name}</span>
                        </label>
                    `).join('');
                    } else {
                        passwordContainer.classList.remove('hidden');
                        firstAccessFields.classList.add('hidden');
                        firstAccessFields.style.opacity = 0;
                        firstAccessNotice.classList.add('hidden');
                    }
                } catch (err) {
                    console.error(err);
                    formError.textContent = 'Erro de comunicação com o servidor.';
                    formError.classList.remove('hidden');
                }
            });

            // Submit do formulário
            // Submit do formulário
            loginForm.addEventListener('submit', async e => {
                e.preventDefault();
                formError.classList.add('hidden');
                document.querySelectorAll('#loginForm p.text-red-500').forEach(p => p.classList.add(
                    'hidden'));

                loginText.classList.add('hidden');
                loginSpinner.classList.remove('hidden');

                const formData = new FormData(loginForm);
                const isFirstLogin = !firstAccessFields.classList.contains('hidden');
                const url = isFirstLogin ? '{{ route('first_access.validate') }}' :
                    '{{ route('login.submit') }}';

                try {
                    const res = await fetch(url, {
                        method: 'POST',
                        body: formData
                    });

                    const data = await res.json();

                    if (data.success) {
                        // Redireciona para o site externo após login
                        window.location.href = 'https://sistema.boqueiraoremates.com/';
                        return;
                    }

                    if (data.error) {
                        formError.textContent = data.error;
                        formError.classList.remove('hidden');
                    }

                    if (data.errors) {
                        for (const [key, messages] of Object.entries(data.errors)) {
                            const errorElem = document.getElementById(key + 'Error');
                            if (errorElem) {
                                errorElem.textContent = messages.join(', ');
                                errorElem.classList.remove('hidden');
                            }
                        }
                    }
                } catch (err) {
                    formError.textContent = 'Erro de comunicação com o servidor.';
                    formError.classList.remove('hidden');
                    console.error(err);
                } finally {
                    loginText.classList.remove('hidden');
                    loginSpinner.classList.add('hidden');
                }
            });

        });
    </script>
@endsection
