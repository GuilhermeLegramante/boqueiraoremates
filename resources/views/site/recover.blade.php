@extends('site.master')

@section('title', 'Recuperar Senha - Boqueirão Remates')

@section('content')
    <section class="flex justify-center items-center py-16 bg-gray-100">
        <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">
            <h2 class="text-2xl font-bold text-center mb-6">Recuperar Senha</h2>

            <div id="recoverStep1" class="space-y-4">
                <label for="username" class="block font-semibold mb-1">Usuário ou CPF</label>
                <input type="text" id="username"
                    class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none">

                <label for="birth_date" class="block font-semibold mb-1">Data de Nascimento</label>
                <input type="date" id="birth_date"
                    class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none">

                <label for="mother" class="block font-semibold mb-1">Nome da Mãe</label>
                <input type="text" id="mother" placeholder="Digite o nome completo da mãe"
                    class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none">

                <button id="verifyData"
                    class="w-full bg-green-700 text-white py-2 rounded-lg font-semibold hover:bg-green-800 transition-all">
                    Verificar
                </button>

                <p id="recoverError" class="text-red-600 text-center hidden"></p>
            </div>

            <div id="recoverStep2" class="hidden space-y-4">
                <p class="text-center text-gray-700 font-semibold mb-2">Defina sua nova senha</p>

                <input type="password" id="new_password" placeholder="Nova senha"
                    class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none">
                <input type="password" id="confirm_password" placeholder="Confirme a senha"
                    class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none">

                <button id="saveNewPassword"
                    class="w-full bg-green-700 text-white py-2 rounded-lg font-semibold hover:bg-green-800 transition-all">
                    Salvar nova senha
                </button>

                <p id="setPasswordError" class="text-red-600 text-center hidden"></p>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('login') }}" class="text-green-700 hover:underline">Voltar ao login</a>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const usernameInput = document.getElementById('username');
            const birthInput = document.getElementById('birth_date');
            const motherInput = document.getElementById('mother');
            const verifyBtn = document.getElementById('verifyData');
            const recoverError = document.getElementById('recoverError');
            const recoverStep1 = document.getElementById('recoverStep1');
            const recoverStep2 = document.getElementById('recoverStep2');
            const saveNewPassword = document.getElementById('saveNewPassword');
            const setPasswordError = document.getElementById('setPasswordError');

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

            verifyBtn.addEventListener('click', async () => {
                recoverError.classList.add('hidden');
                if (!usernameInput.value || !birthInput.value || !motherInput.value) {
                    recoverError.textContent = 'Preencha todos os campos.';
                    recoverError.classList.remove('hidden');
                    return;
                }

                currentUsername = usernameInput.value.trim();

                const res = await fetch('{{ route('recover.validate') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        username: currentUsername,
                        birth_date: birthInput.value,
                        mother: motherInput.value
                    })
                });

                const data = await res.json();
                if (data.verified) {
                    recoverStep1.classList.add('hidden');
                    recoverStep2.classList.remove('hidden');
                } else {
                    recoverError.textContent = data.error || 'Dados incorretos.';
                    recoverError.classList.remove('hidden');
                }
            });

            saveNewPassword.addEventListener('click', async () => {
                setPasswordError.classList.add('hidden');
                const pass1 = document.getElementById('new_password').value;
                const pass2 = document.getElementById('confirm_password').value;

                if (pass1.length < 6 || pass1 !== pass2) {
                    setPasswordError.textContent = 'Senhas não conferem ou são muito curtas.';
                    setPasswordError.classList.remove('hidden');
                    return;
                }

                const res = await fetch('{{ route('recover.set') }}', {
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
