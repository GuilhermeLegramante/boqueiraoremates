@extends('site.master')

@section('title', 'Login - Boqueirão Remates')

@section('content')
    <section class="flex justify-center items-center py-16 bg-gray-100">
        <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">
            <h2 class="text-2xl font-bold text-center mb-6">Acesse sua conta</h2>

            {{-- Login comum --}}
            <form id="loginForm" method="POST" action="{{ route('login.submit') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="username" class="block font-semibold mb-1">Usuário ou CPF</label>
                    <input type="text" name="username" id="username"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none"
                        placeholder="Digite seu usuário" required>
                </div>

                <div id="passwordContainer">
                    <label for="password" class="block font-semibold mb-1">Senha</label>
                    <input type="password" name="password" id="password"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none"
                        placeholder="Digite sua senha" required>
                </div>

                <button type="submit"
                    class="w-full bg-green-700 text-white py-2 rounded-lg font-semibold hover:bg-green-800 transition-all">
                    Entrar
                </button>

                <div id="errorMessage" class="bg-red-100 text-red-700 p-3 rounded-lg mt-3 hidden"></div>
            </form>

            {{-- Primeiro acesso --}}
            <div id="firstLoginSection" class="hidden space-y-4">
                <p class="text-center text-gray-700 font-semibold mb-2">Primeiro acesso: confirme seus dados</p>

                <label>Data de nascimento</label>
                <input type="date" id="birth_date"
                    class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none">

                <label>Selecione o nome da mãe</label>
                <div id="motherOptions" class="grid grid-cols-1 gap-2"></div>

                <button id="confirmFirstAccess"
                    class="w-full bg-green-700 text-white py-2 rounded-lg font-semibold hover:bg-green-800 transition-all">
                    Confirmar
                </button>

                <p id="firstAccessError" class="text-red-600 text-center hidden"></p>
            </div>

            {{-- Etapa de redefinição de senha --}}
            <div id="setPasswordSection" class="hidden space-y-4">
                <p class="text-center text-gray-700 font-semibold mb-2">Defina sua nova senha</p>

                <input type="password" id="new_password" placeholder="Nova senha"
                    class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none">
                <input type="password" id="confirm_password" placeholder="Confirme a senha"
                    class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none">

                <button id="saveNewPassword"
                    class="w-full bg-green-700 text-white py-2 rounded-lg font-semibold hover:bg-green-800 transition-all">
                    Salvar e entrar
                </button>

                <p id="setPasswordError" class="text-red-600 text-center hidden"></p>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const usernameInput = document.getElementById('username');
            const loginForm = document.getElementById('loginForm');
            const firstLoginSection = document.getElementById('firstLoginSection');
            const setPasswordSection = document.getElementById('setPasswordSection');
            const passwordContainer = document.getElementById('passwordContainer');
            const birthInput = document.getElementById('birth_date');
            const motherOptions = document.getElementById('motherOptions');
            const confirmFirstAccess = document.getElementById('confirmFirstAccess');
            const saveNewPassword = document.getElementById('saveNewPassword');
            const firstAccessError = document.getElementById('firstAccessError');
            const setPasswordError = document.getElementById('setPasswordError');

            let selectedMother = null;
            let currentUsername = null;

            // Máscara CPF
            usernameInput.addEventListener('input', () => {
                const value = usernameInput.value.replace(/\D/g, '');
                if (/^\d+$/.test(usernameInput.value)) {
                    let cpf = value
                        .replace(/(\d{3})(\d)/, '$1.$2')
                        .replace(/(\d{3})(\d)/, '$1.$2')
                        .replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                    usernameInput.value = cpf;
                }
            });

            // Verifica tipo de login ao sair do campo
            usernameInput.addEventListener('blur', async () => {
                const username = usernameInput.value.trim();
                if (!username) return;
                currentUsername = username;

                const res = await fetch('{{ route('login.checkUser') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        username
                    })
                });

                const data = await res.json();

                if (data.first_login) {
                    loginForm.classList.add('hidden');
                    firstLoginSection.classList.remove('hidden');
                    motherOptions.innerHTML = '';
                    data.mother_options.forEach(name => {
                        const btn = document.createElement('button');
                        btn.textContent = name;
                        btn.className = "w-full border py-2 rounded-lg hover:bg-green-100";
                        btn.onclick = () => {
                            selectedMother = name;
                            document.querySelectorAll('#motherOptions button').forEach(b =>
                                b.classList.remove('bg-green-200'));
                            btn.classList.add('bg-green-200');
                        };
                        motherOptions.appendChild(btn);
                    });
                }
            });

            // Validação de primeiro acesso
            confirmFirstAccess.addEventListener('click', async () => {
                firstAccessError.classList.add('hidden');
                if (!birthInput.value || !selectedMother) {
                    firstAccessError.textContent = 'Preencha todos os campos.';
                    firstAccessError.classList.remove('hidden');
                    return;
                }

                const res = await fetch('{{ route('login.validateFirst') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        username: currentUsername,
                        birth_date: birthInput.value,
                        mother: selectedMother
                    })
                });

                const data = await res.json();

                if (data.verified) {
                    firstLoginSection.classList.add('hidden');
                    setPasswordSection.classList.remove('hidden');
                } else {
                    firstAccessError.textContent = data.error || 'Respostas incorretas.';
                    firstAccessError.classList.remove('hidden');
                }
            });

            // Salvar nova senha
            saveNewPassword.addEventListener('click', async () => {
                setPasswordError.classList.add('hidden');
                const pass1 = document.getElementById('new_password').value;
                const pass2 = document.getElementById('confirm_password').value;

                if (pass1.length < 6 || pass1 !== pass2) {
                    setPasswordError.textContent = 'Senhas não conferem ou são muito curtas.';
                    setPasswordError.classList.remove('hidden');
                    return;
                }

                const res = await fetch('{{ route('login.setNewPassword') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        username: currentUsername,
                        password: pass1,
                        password_confirmation: pass2
                    })
                });

                const data = await res.json();
                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    setPasswordError.textContent = data.error || 'Erro ao salvar senha.';
                    setPasswordError.classList.remove('hidden');
                }
            });
        });
    </script>
@endsection
