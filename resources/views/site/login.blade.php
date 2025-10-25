@extends('site.master')

@section('title', 'Login - Boqueirão Remates')

@section('content')
    <section class="flex justify-center items-center py-16 bg-gray-100 min-h-screen">
        <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">
            <h2 class="text-2xl font-bold text-center mb-6">Acesse sua conta</h2>

            <form id="loginForm" method="POST" action="{{ route('login.submit') }}" class="space-y-5">
                @csrf

                {{-- Campo username --}}
                <div>
                    <label for="username" class="block font-semibold mb-1">Usuário ou CPF</label>
                    <input type="text" name="username" id="username"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none"
                        placeholder="Digite seu usuário ou CPF" required>
                    <p id="usernameError" class="text-red-500 text-sm mt-1 hidden"></p>
                </div>

                {{-- Campo senha (escondido se for primeiro acesso) --}}
                <div id="passwordContainer" class="space-y-2">
                    <label for="password" class="block font-semibold mb-1">Senha</label>
                    <input type="password" name="password" id="password"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none"
                        placeholder="Digite sua senha" minlength="6">
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
                            placeholder="Crie sua nova senha" minlength="6">
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

    {{-- Modal de Recuperação de Senha --}}
    <div id="recoverModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-xl p-8 w-full max-w-md shadow-lg relative">
            <h3 class="text-xl font-bold mb-4 text-center">Recuperar senha</h3>

            <form id="recoverForm" class="space-y-4">
                <div>
                    <label for="recover_username" class="block font-semibold mb-1">Usuário ou CPF</label>
                    <input type="text" id="recover_username" name="recover_username"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none"
                        placeholder="Digite seu usuário ou CPF" required>
                </div>

                <div>
                    <label for="recover_birth_date" class="block font-semibold mb-1">Data de nascimento</label>
                    <input type="date" id="recover_birth_date" name="recover_birth_date"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none" required>
                </div>

                <div>
                    <label class="block font-semibold mb-1">Nome da mãe</label>
                    <div id="recoverMotherOptions" class="space-y-2"></div>
                </div>

                <div>
                    <label for="recover_new_password" class="block font-semibold mb-1">Nova senha</label>
                    <input type="password" id="recover_new_password" name="recover_new_password"
                        class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none"
                        placeholder="Digite a nova senha" minlength="6" required>
                </div>

                <div class="flex justify-end space-x-2 mt-6">
                    <button type="button" id="closeModal"
                        class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">Cancelar</button>
                    <button type="submit"
                        class="bg-green-700 text-white px-4 py-2 rounded-lg hover:bg-green-800">Salvar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const usernameInput = document.getElementById('username');
            const passwordContainer = document.getElementById('passwordContainer');
            const firstAccessFields = document.getElementById('firstAccessFields');
            const motherOptions = document.getElementById('motherOptions');

            // Máscara CPF
            usernameInput.addEventListener('input', () => {
                const value = usernameInput.value;
                if (/^\d/.test(value)) {
                    usernameInput.value = value
                        .replace(/\D/g, '')
                        .replace(/(\d{3})(\d)/, '$1.$2')
                        .replace(/(\d{3})(\d)/, '$1.$2')
                        .replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                }
            });

            // Verifica se é primeiro acesso via backend (simulação)
            usernameInput.addEventListener('blur', () => {
                const username = usernameInput.value.trim();

                // Exemplo de simulação, você pode substituir por AJAX real
                if (username === '123.456.789-00') {
                    passwordContainer.classList.add('hidden'); // ⚡ esconde campo senha normal
                    firstAccessFields.classList.remove('hidden');

                    // Opções da mãe
                    const options = ['Maria das Dores', 'Joana Silva', 'Ana Souza', 'Carla Oliveira',
                        'Marta Santos'
                    ];
                    motherOptions.innerHTML = options.map(name => `
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="radio" name="mother_option" value="${name}" required>
                    <span>${name}</span>
                </label>
            `).join('');
                } else {
                    passwordContainer.classList.remove('hidden');
                    firstAccessFields.classList.add('hidden');
                }
            });

            // Modal de recuperação
            const recoverModal = document.getElementById('recoverModal');
            const forgotBtn = document.getElementById('forgotPasswordBtn');
            const closeModal = document.getElementById('closeModal');
            const recoverForm = document.getElementById('recoverForm');
            const recoverMotherOptions = document.getElementById('recoverMotherOptions');

            forgotBtn.addEventListener('click', () => {
                recoverModal.classList.remove('hidden');

                const options = ['Maria das Dores', 'Joana Silva', 'Ana Souza', 'Carla Oliveira',
                    'Marta Santos'
                ];
                recoverMotherOptions.innerHTML = options.map(name => `
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="radio" name="recover_mother_option" value="${name}" required>
                <span>${name}</span>
            </label>
        `).join('');
            });

            closeModal.addEventListener('click', () => {
                recoverModal.classList.add('hidden');
            });

            recoverForm.addEventListener('submit', e => {
                e.preventDefault();
                alert('Senha redefinida com sucesso!');
                recoverModal.classList.add('hidden');
            });
        });
    </script>
@endsection
