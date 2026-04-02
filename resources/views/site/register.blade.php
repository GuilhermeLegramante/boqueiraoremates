@extends('site.master')

@section('title', 'Cadastro - Boqueirão Remates')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <section class="flex justify-center bg-gray-100 pt-48 pb-16 min-h-screen">
        <div class="w-full max-w-2xl px-4">
            <div class="bg-white p-8 rounded-xl shadow-lg flex flex-col space-y-6">
                <h2 class="text-3xl font-bold text-center mb-2">Crie sua conta</h2>

                <p class="text-center text-gray-600 mb-6">
                    Já possui uma conta?
                    <a href="{{ route('login') }}"
                        class="text-green-700 font-semibold hover:text-green-800 transition-colors">
                        Acesse aqui
                    </a>
                </p>

                {{-- Barra de Progresso --}}
                <div class="flex items-center justify-between mb-8">
                    <div id="step1_dot" class="flex flex-col items-center">
                        <span
                            class="w-10 h-10 flex items-center justify-center rounded-full bg-green-700 text-white font-bold transition-all">1</span>
                        <span class="text-xs mt-1 font-bold text-green-700 uppercase">Dados</span>
                    </div>
                    <div class="flex-1 h-1 bg-gray-200 mx-2 -mt-4">
                        <div id="progress_line" class="h-full bg-green-700 w-0 transition-all duration-500"></div>
                    </div>
                    <div id="step2_dot" class="flex flex-col items-center">
                        <span
                            class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-200 text-gray-500 font-bold transition-all">2</span>
                        <span class="text-xs mt-1 font-bold text-gray-400 uppercase">Endereço</span>
                    </div>
                    <div class="flex-1 h-1 bg-gray-200 mx-2 -mt-4">
                        <div id="progress_line_2" class="h-full bg-green-700 w-0 transition-all duration-500"></div>
                    </div>
                    <div id="step3_dot" class="flex flex-col items-center">
                        <span
                            class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-200 text-gray-500 font-bold transition-all">3</span>
                        <span class="text-xs mt-1 font-bold text-gray-400 uppercase">Senha</span>
                    </div>
                </div>

                <form id="registerForm" class="space-y-5">
                    @csrf
                    <p id="formError" class="text-red-500 text-sm hidden text-center font-bold"></p>

                    <div id="step-1" class="space-y-4">
                        <div>
                            <label class="block font-semibold mb-1">CPF ou CNPJ</label>
                            <input type="text" name="cpf_cnpj" id="cpf_cnpj"
                                class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none"
                                placeholder="000.000.000-00">
                            <p id="cpf_cnpjError" class="text-red-500 text-sm mt-1 hidden"></p>
                            <p id="searchingMsg" class="text-blue-600 text-xs mt-1 hidden italic">Buscando dados...</p>
                        </div>
                        <div>
                            <label class="block font-semibold mb-1">Nome Completo</label>
                            <input type="text" name="name" id="name"
                                class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none">
                            <p id="nameError" class="text-red-500 text-sm mt-1 hidden"></p>
                        </div>
                        <div>
                            <label class="block font-semibold mb-1">E-mail</label>
                            <input type="email" name="email" id="email"
                                class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none">
                            <p id="emailError" class="text-red-500 text-sm mt-1 hidden"></p>
                        </div>
                        <button type="button" onclick="goToStep(2)"
                            class="w-full bg-green-700 text-white py-3 rounded-lg font-semibold hover:bg-green-800 transition-all">PRÓXIMO
                            PASSO</button>
                    </div>

                    <div id="step-2" class="hidden space-y-4">
                        <div class="grid grid-cols-3 gap-4">
                            <div class="col-span-1">
                                <label class="block font-semibold mb-1">CEP</label>
                                <input type="text" name="postal_code" id="postal_code"
                                    class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none">
                            </div>
                            <div class="col-span-2">
                                <label class="block font-semibold mb-1">Rua</label>
                                <input type="text" name="street" id="street"
                                    class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block font-semibold mb-1">Bairro</label>
                                <input type="text" name="district" id="district"
                                    class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none">
                            </div>
                            <div>
                                <label class="block font-semibold mb-1">Número</label>
                                <input type="text" name="number" id="number"
                                    class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none">
                            </div>
                        </div>
                        <div>
                            <label class="block font-semibold mb-1">Cidade</label>
                            <input type="text" name="city" id="city"
                                class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none">
                        </div>
                        <div class="flex gap-3 mt-6">
                            <button type="button" onclick="goToStep(1)"
                                class="w-1/3 bg-gray-100 text-gray-600 py-3 rounded-lg font-semibold">VOLTAR</button>
                            <button type="button" onclick="goToStep(3)"
                                class="w-2/3 bg-green-700 text-white py-3 rounded-lg font-semibold">PRÓXIMO</button>
                        </div>
                    </div>

                    <div id="step-3" class="hidden space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block font-semibold mb-1">Nascimento</label>
                                <input type="text" name="birth_date" id="birth_date" placeholder="dd/mm/aaaa"
                                    class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none">
                            </div>
                            <div>
                                <label class="block font-semibold mb-1">WhatsApp</label>
                                <input type="text" name="whatsapp" id="whatsapp" placeholder="(00) 00000-0000"
                                    class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none">
                            </div>
                        </div>
                        <div>
                            <label class="block font-semibold mb-1">Crie sua Senha</label>
                            <input type="password" name="password"
                                class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none">
                            <p id="passwordError" class="text-red-500 text-sm mt-1 hidden"></p>
                        </div>
                        <div>
                            <label class="block font-semibold mb-1">Confirmar Senha</label>
                            <input type="password" name="password_confirmation"
                                class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none">
                        </div>
                        <div class="flex gap-3 mt-6">
                            <button type="button" onclick="goToStep(2)"
                                class="w-1/3 bg-gray-100 text-gray-600 py-3 rounded-lg font-semibold">VOLTAR</button>
                            <button id="registerBtn" type="submit"
                                class="w-2/3 bg-green-700 text-white py-3 rounded-lg font-semibold hover:bg-green-800 transition-all flex justify-center items-center">
                                <span id="btnText">FINALIZAR CADASTRO</span>
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
            </div>
        </div>
    </section>

    <script>
        // Função Navegação
        function goToStep(s) {
            document.querySelectorAll('[id^="step-"]').forEach(el => el.classList.add('hidden'));
            document.getElementById('step-' + s).classList.remove('hidden');

            const l1 = document.getElementById('progress_line');
            const l2 = document.getElementById('progress_line_2');
            const d2 = document.getElementById('step2_dot');
            const d3 = document.getElementById('step3_dot');

            l1.style.width = s >= 2 ? '100%' : '0%';
            l2.style.width = s === 3 ? '100%' : '0%';

            // Update Dots (Cores do Boqueirão)
            if (s >= 2) {
                d2.querySelector('span').classList.replace('bg-gray-200', 'bg-green-700');
                d2.querySelector('span').classList.replace('text-gray-500', 'text-white');
            }
            if (s === 3) {
                d3.querySelector('span').classList.replace('bg-gray-200', 'bg-green-700');
                d3.querySelector('span').classList.replace('text-gray-500', 'text-white');
            }
            window.scrollTo(0, 0);
        }

        document.addEventListener('DOMContentLoaded', () => {
            const cpfInput = document.getElementById('cpf_cnpj');
            const postalInput = document.getElementById('postal_code');
            const birthInput = document.getElementById('birth_date');
            const whatsappInput = document.getElementById('whatsapp');

            // --- REGRAS DE NEGÓCIO: BUSCA DE CLIENTE (CPF/CNPJ) ---
            cpfInput.addEventListener('blur', async () => {
                const val = cpfInput.value.replace(/\D/g, '');
                if (val.length < 11) return;

                document.getElementById('searchingMsg').classList.remove('hidden');

                try {
                    const res = await fetch(`/api/check-client?cpf_cnpj=${val}`);
                    const json = await res.json();

                    if (json.exists) {
                        Swal.fire({
                            title: 'Dados Localizados!',
                            text: 'Já encontramos seu cadastro. Preenchemos os dados para você.',
                            icon: 'info',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        document.getElementById('name').value = json.data.name || '';
                        document.getElementById('email').value = json.data.email || '';
                        if (json.data.address) {
                            document.getElementById('postal_code').value = json.data.address
                                .postal_code || '';
                            document.getElementById('street').value = json.data.address.street || '';
                            document.getElementById('district').value = json.data.address.district ||
                            '';
                            document.getElementById('city').value = json.data.address.city || '';
                            document.getElementById('number').value = json.data.address.number || '';
                        }
                    }
                } catch (e) {
                    console.error("Erro busca cliente");
                } finally {
                    document.getElementById('searchingMsg').classList.add('hidden');
                }
            });

            // --- REGRAS DE NEGÓCIO: BUSCA CEP ---
            postalInput.addEventListener('blur', async () => {
                const cep = postalInput.value.replace(/\D/g, '');
                if (cep.length !== 8) return;

                try {
                    const res = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
                    const data = await res.json();
                    if (!data.erro) {
                        document.getElementById('street').value = data.logradouro;
                        document.getElementById('district').value = data.bairro;
                        document.getElementById('city').value = data.localidade;
                    }
                } catch (e) {}
            });

            // --- MÁSCARAS (IGUAL AO LOGIN) ---
            cpfInput.addEventListener('input', e => {
                let v = e.target.value.replace(/\D/g, '');
                if (v.length <= 11) v = v.replace(/(\d{3})(\d)/, '$1.$2').replace(/(\d{3})(\d)/, '$1.$2')
                    .replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                else v = v.replace(/^(\d{2})(\d)/, '$1.$2').replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3')
                    .replace(/\.(\d{3})(\d)/, '.$1/$2').replace(/(\d{4})(\d)/, '$1-$2');
                e.target.value = v;
            });

            birthInput.addEventListener('input', e => {
                let v = e.target.value.replace(/\D/g, '');
                if (v.length > 4) v = v.replace(/^(\d{2})(\d{2})(\d{0,4}).*/, '$1/$2/$3');
                else if (v.length > 2) v = v.replace(/^(\d{2})(\d{0,2})/, '$1/$2');
                e.target.value = v;
            });

            // --- ENVIO DO FORMULÁRIO COM MODAL MODERNA ---
            document.getElementById('registerForm').addEventListener('submit', async e => {
                e.preventDefault();
                const btnText = document.getElementById('btnText');
                const btnSpinner = document.getElementById('btnSpinner');

                btnText.classList.add('hidden');
                btnSpinner.classList.remove('hidden');
                document.querySelectorAll('.text-red-500').forEach(p => p.classList.add('hidden'));

                try {
                    const res = await fetch('{{ route('register.custom.store') }}', {
                        method: 'POST',
                        body: new FormData(e.target),
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });
                    const data = await res.json();

                    if (data.success) {
                        Swal.fire({
                            title: '<span style="color:#15803d">Cadastro Concluído!</span>',
                            html: 'Seja bem-vindo à Boqueirão Remates.<br>Redirecionando para o sistema...',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 3500,
                            allowOutsideClick: false,
                            willClose: () => {
                                window.location.href = data.redirect;
                            }
                        });
                    } else if (data.errors) {
                        for (const [key, msg] of Object.entries(data.errors)) {
                            const errEl = document.getElementById(key + 'Error');
                            if (errEl) {
                                errEl.textContent = msg[0];
                                errEl.classList.remove('hidden');
                            }
                        }
                        if (data.errors.cpf_cnpj || data.errors.name) goToStep(1);
                        else if (data.errors.postal_code) goToStep(2);
                    }
                } catch (err) {
                    Swal.fire('Erro', 'Falha na conexão.', 'error');
                } finally {
                    btnText.classList.remove('hidden');
                    btnSpinner.classList.add('hidden');
                }
            });
        });
    </script>
@endsection
