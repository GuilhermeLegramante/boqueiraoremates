@extends('site.master')

@section('title', 'Cadastro - Boqueirão Remates')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <section class="flex justify-center bg-gray-100 pt-48 pb-16 min-h-screen">
        <div class="w-full max-w-2xl"> {{-- Um pouco mais largo que o login para caber os grids --}}
            <div class="bg-white p-8 rounded-xl shadow-lg flex flex-col space-y-6">
                <h2 class="text-3xl font-bold text-center mb-2">Crie sua conta</h2>

                <p class="text-center text-gray-600 mb-6">
                    Já possui uma conta?
                    <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:text-blue-800 transition-colors">
                        Acesse aqui
                    </a>
                </p>

                {{-- Barra de Progresso Estilo Boqueirão --}}
                <div class="flex items-center justify-between px-4 mb-4">
                    <div id="step1_indicator" class="flex items-center text-green-700 font-bold">
                        <span
                            class="w-8 h-8 flex items-center justify-center rounded-full bg-green-700 text-white mr-2">1</span>
                        Identificação
                    </div>
                    <div class="flex-1 h-1 bg-gray-200 mx-4 rounded">
                        <div id="progress_bar" class="h-full bg-green-600 w-1/3 transition-all duration-300"></div>
                    </div>
                    <div id="step2_indicator" class="flex items-center text-gray-400 font-bold">
                        <span
                            class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-200 text-gray-500 mr-2">2</span>
                        Finalizar
                    </div>
                </div>

                <form id="registerForm" class="space-y-5">
                    @csrf

                    {{-- Erro geral --}}
                    <p id="formError" class="text-red-500 text-sm mt-1 hidden text-center"></p>

                    {{-- PASSO 1: DADOS INICIAIS --}}
                    <div id="step-1" class="space-y-5">
                        <div>
                            <label for="cpf_cnpj" class="block font-semibold mb-1">CPF ou CNPJ</label>
                            <input type="text" name="cpf_cnpj" id="cpf_cnpj"
                                class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none"
                                placeholder="000.000.000-00" required>
                            <p id="cpf_cnpjError" class="text-red-500 text-sm mt-1 hidden"></p>
                        </div>

                        <div>
                            <label for="name" class="block font-semibold mb-1">Nome Completo</label>
                            <input type="text" name="name" id="name"
                                class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none"
                                placeholder="Digite seu nome completo">
                            <p id="nameError" class="text-red-500 text-sm mt-1 hidden"></p>
                        </div>

                        <div>
                            <label for="email" class="block font-semibold mb-1">E-mail</label>
                            <input type="email" name="email" id="email"
                                class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none"
                                placeholder="exemplo@email.com">
                            <p id="emailError" class="text-red-500 text-sm mt-1 hidden"></p>
                        </div>

                        <button type="button" onclick="nextStep()"
                            class="w-full bg-green-700 text-white py-3 rounded-lg font-semibold hover:bg-green-800 transition-all">
                            Próximo Passo
                        </button>
                    </div>

                    {{-- PASSO 2: SEGURANÇA E FINALIZAÇÃO --}}
                    <div id="step-2" class="hidden space-y-5">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="birth_date" class="block font-semibold mb-1">Data de Nascimento</label>
                                <input type="text" name="birth_date" id="birth_date" placeholder="dd/mm/aaaa"
                                    class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none">
                                <p id="birth_dateError" class="text-red-500 text-sm mt-1 hidden"></p>
                            </div>
                            <div>
                                <label for="whatsapp" class="block font-semibold mb-1">WhatsApp</label>
                                <input type="text" name="whatsapp" id="whatsapp" placeholder="(00) 00000-0000"
                                    class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none">
                            </div>
                        </div>

                        <div>
                            <label for="password" class="block font-semibold mb-1">Crie uma Senha</label>
                            <input type="password" name="password" id="password"
                                class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none"
                                placeholder="Mínimo 6 caracteres">
                            <p id="passwordError" class="text-red-500 text-sm mt-1 hidden"></p>
                        </div>

                        <div>
                            <label for="password_confirmation" class="block font-semibold mb-1">Confirme a Senha</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none"
                                placeholder="Repita a senha">
                        </div>

                        <div class="flex gap-3">
                            <button type="button" onclick="prevStep()"
                                class="w-1/3 bg-gray-200 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-300 transition-all">
                                Voltar
                            </button>
                            <button id="registerBtn" type="submit"
                                class="w-2/3 bg-green-700 text-white py-3 rounded-lg font-semibold hover:bg-green-800 transition-all flex justify-center items-center">
                                <span id="btnText">Finalizar Cadastro</span>
                                <svg id="btnSpinner" class="animate-spin h-5 w-5 text-white ml-2 hidden"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 11-8 8z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </form>

                {{-- Suporte via WhatsApp (Mantido o padrão do login) --}}
                <div class="mt-4 text-center">
                    <a href="https://wa.me/5555997331395" target="_blank"
                        class="inline-flex items-center justify-center gap-2 text-green-700 font-semibold hover:underline text-sm">
                        Precisa de ajuda? Fale conosco no WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </section>

    <script>
        let currentStep = 1;

        function updateUI() {
            document.getElementById('step-1').classList.toggle('hidden', currentStep !== 1);
            document.getElementById('step-2').classList.toggle('hidden', currentStep !== 2);

            const progress = document.getElementById('progress_bar');
            const step2Ind = document.getElementById('step2_indicator');

            if (currentStep === 2) {
                progress.style.width = '100%';
                step2Ind.classList.replace('text-gray-400', 'text-green-700');
                step2Ind.querySelector('span').classList.replace('bg-gray-200', 'bg-green-700');
                step2Ind.querySelector('span').classList.replace('text-gray-500', 'text-white');
            } else {
                progress.style.width = '33%';
                step2Ind.classList.replace('text-green-700', 'text-gray-400');
                step2Ind.querySelector('span').classList.replace('bg-green-700', 'bg-gray-200');
                step2Ind.querySelector('span').classList.replace('text-white', 'text-gray-500');
            }
        }

        function nextStep() {
            currentStep = 2;
            updateUI();
        }

        function prevStep() {
            currentStep = 1;
            updateUI();
        }

        document.addEventListener('DOMContentLoaded', () => {
            const registerForm = document.getElementById('registerForm');
            const cpfInput = document.getElementById('cpf_cnpj');
            const birthInput = document.getElementById('birth_date');
            const whatsappInput = document.getElementById('whatsapp');

            // Máscara CPF/CNPJ (Igual ao login)
            cpfInput.addEventListener('input', () => {
                let value = cpfInput.value.replace(/\D/g, '');
                if (value.length <= 11) {
                    value = value.replace(/(\d{3})(\d)/, '$1.$2').replace(/(\d{3})(\d)/, '$1.$2').replace(
                        /(\d{3})(\d{1,2})$/, '$1-$2');
                } else {
                    value = value.replace(/^(\d{2})(\d)/, '$1.$2').replace(/^(\d{2})\.(\d{3})(\d)/,
                        '$1.$2.$3').replace(/\.(\d{3})(\d)/, '.$1/$2').replace(/(\d{4})(\d)/, '$1-$2');
                }
                cpfInput.value = value;
            });

            // Máscara Data (Igual ao login)
            birthInput.addEventListener('input', (e) => {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 8) value = value.slice(0, 8);
                if (value.length > 4) value = value.replace(/^(\d{2})(\d{2})(\d{0,4}).*/, '$1/$2/$3');
                else if (value.length > 2) value = value.replace(/^(\d{2})(\d{0,2})/, '$1/$2');
                e.target.value = value;
            });

            // Submit
            registerForm.addEventListener('submit', async e => {
                e.preventDefault();

                // Limpa erros
                document.querySelectorAll('#registerForm p.text-red-500').forEach(p => p.classList.add(
                    'hidden'));

                const btnText = document.getElementById('btnText');
                const btnSpinner = document.getElementById('btnSpinner');
                btnText.classList.add('hidden');
                btnSpinner.classList.remove('hidden');

                const formData = new FormData(registerForm);

                try {
                    const res = await fetch('{{ route('register.custom.store') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json'
                        }
                    });

                    const data = await res.json();

                    if (data.success) {
                        Swal.fire({
                            title: 'Sucesso!',
                            text: 'Seu cadastro foi realizado. Redirecionando para o site...',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 3000,
                            willClose: () => {
                                window.location.href = data.redirect;
                            }
                        });
                        return;
                    }

                    if (data.errors) {
                        for (const [key, messages] of Object.entries(data.errors)) {
                            const errorElem = document.getElementById(key + 'Error');
                            if (errorElem) {
                                errorElem.textContent = messages[0];
                                errorElem.classList.remove('hidden');
                            }
                        }
                        // Se houver erro no passo 1, volta para lá
                        if (data.errors.cpf_cnpj || data.errors.name || data.errors.email) {
                            prevStep();
                        }
                    }
                } catch (err) {
                    console.error(err);
                    document.getElementById('formError').textContent = 'Erro de comunicação.';
                    document.getElementById('formError').classList.remove('hidden');
                } finally {
                    btnText.classList.remove('hidden');
                    btnSpinner.classList.add('hidden');
                }
            });
        });
    </script>
@endsection
