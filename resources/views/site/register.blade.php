@extends('site.master')

@section('title', 'Cadastro - Boqueirão Remates')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <section class="flex justify-center bg-gray-100 pt-48 pb-16 min-h-screen">
        <div class="w-full max-w-4xl px-4">
            <div class="bg-white p-8 rounded-xl shadow-lg flex flex-col space-y-6">
                <h2 class="text-3xl font-bold text-center text-green-800">Crie sua conta</h2>

                {{-- Barra de Progresso --}}
                <div class="flex items-center justify-between mb-8 max-w-md mx-auto w-full">
                    <div id="step1_dot" class="flex flex-col items-center">
                        <span
                            class="w-10 h-10 flex items-center justify-center rounded-full bg-green-700 text-white font-bold transition-all">1</span>
                        <span class="text-[10px] mt-1 font-bold text-green-700 uppercase">Pessoal</span>
                    </div>
                    <div class="flex-1 h-1 bg-gray-200 mx-2 -mt-4">
                        <div id="progress_line" class="h-full bg-green-700 w-0 transition-all duration-500"></div>
                    </div>
                    <div id="step2_dot" class="flex flex-col items-center">
                        <span
                            class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-200 text-gray-500 font-bold transition-all">2</span>
                        <span class="text-[10px] mt-1 font-bold text-gray-400 uppercase">Endereço</span>
                    </div>
                    <div class="flex-1 h-1 bg-gray-200 mx-2 -mt-4">
                        <div id="progress_line_2" class="h-full bg-green-700 w-0 transition-all duration-500"></div>
                    </div>
                    <div id="step3_dot" class="flex flex-col items-center">
                        <span
                            class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-200 text-gray-500 font-bold transition-all">3</span>
                        <span class="text-[10px] mt-1 font-bold text-gray-400 uppercase">Documentos</span>
                    </div>
                </div>

                <form id="registerForm" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    {{-- STEP 1: DADOS PESSOAIS --}}
                    <div id="step-1" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block font-semibold text-gray-700">CPF ou CNPJ *</label>
                            <input type="text" name="cpf_cnpj" id="cpf_cnpj"
                                class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none"
                                placeholder="000.000.000-00">
                            <p id="searchingMsg" class="text-green-600 text-xs mt-1 hidden italic">Verificando cadastro
                                existente...</p>
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700">Nome Completo *</label>
                            <input type="text" name="name" id="name"
                                class="w-full border rounded-lg px-4 py-2 outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700">E-mail *</label>
                            <input type="email" name="email" id="email"
                                class="w-full border rounded-lg px-4 py-2 outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700">Data de Nascimento *</label>
                            <input type="text" name="birth_date" id="birth_date" placeholder="dd/mm/aaaa"
                                class="w-full border rounded-lg px-4 py-2 outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700">WhatsApp *</label>
                            <input type="text" name="whatsapp" id="whatsapp" placeholder="(00) 00000-0000"
                                class="w-full border rounded-lg px-4 py-2 outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700">Senha *</label>
                            <input type="password" name="password"
                                class="w-full border rounded-lg px-4 py-2 outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700">Confirmar Senha *</label>
                            <input type="password" name="passwordConfirmation"
                                class="w-full border rounded-lg px-4 py-2 outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        <button type="button" onclick="goToStep(2)"
                            class="md:col-span-2 bg-green-700 text-white py-3 rounded-lg font-bold hover:bg-green-800 transition-all uppercase">Próximo
                            Passo</button>
                    </div>

                    {{-- STEP 2: ENDEREÇO --}}
                    <div id="step-2" class="hidden grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block font-semibold text-gray-700">CEP</label>
                            <input type="text" name="postal_code" id="postal_code"
                                class="w-full border rounded-lg px-4 py-2 outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block font-semibold text-gray-700">Rua</label>
                            <input type="text" name="street" id="street"
                                class="w-full border rounded-lg px-4 py-2 outline-none focus:ring-2 focus:ring-green-500 uppercase">
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700">Número</label>
                            <input type="text" name="number" id="number"
                                class="w-full border rounded-lg px-4 py-2 outline-none focus:ring-2 focus:ring-green-500 uppercase">
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700">Bairro</label>
                            <input type="text" name="district" id="district"
                                class="w-full border rounded-lg px-4 py-2 outline-none focus:ring-2 focus:ring-green-500 uppercase">
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700">Cidade</label>
                            <input type="text" name="city" id="city"
                                class="w-full border rounded-lg px-4 py-2 outline-none focus:ring-2 focus:ring-green-500 uppercase">
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700">Estado (UF)</label>
                            <input type="text" name="state" id="state" maxlength="2"
                                class="w-full border rounded-lg px-4 py-2 outline-none focus:ring-2 focus:ring-green-500 uppercase"
                                placeholder="RS">
                        </div>
                        <div class="flex gap-3 md:col-span-3 mt-4">
                            <button type="button" onclick="goToStep(1)"
                                class="w-1/3 bg-gray-200 text-gray-700 py-3 rounded-lg font-bold">VOLTAR</button>
                            <button type="button" onclick="goToStep(3)"
                                class="w-2/3 bg-green-700 text-white py-3 rounded-lg font-bold">PRÓXIMO</button>
                        </div>
                    </div>

                    {{-- STEP 3: DOCUMENTOS --}}
                    <div id="step-3" class="hidden space-y-4">
                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200 mb-4 text-sm text-blue-800 italic">
                            Anexe fotos nítidas dos seus documentos para agilizar sua aprovação.
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700">Documento Pessoal (CNH ou RG) *</label>
                            <input type="file" name="cnh_rg" class="w-full border p-2 rounded-lg bg-gray-50">
                        </div>
                        <div class="flex gap-3 mt-6">
                            <button type="button" onclick="goToStep(2)"
                                class="w-1/3 bg-gray-200 text-gray-700 py-3 rounded-lg font-bold">VOLTAR</button>
                            <button id="registerBtn" type="submit"
                                class="w-2/3 bg-green-700 text-white py-3 rounded-lg font-bold hover:bg-green-800 transition-all flex justify-center items-center">
                                <span id="btnText">FINALIZAR CADASTRO</span>
                                <div id="btnSpinner"
                                    class="hidden animate-spin rounded-full h-5 w-5 border-b-2 border-white"></div>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script>
        // Máscaras e Eventos (mesma lógica anterior)
        const cpfCnpjMask = (v) => {
            v = v.replace(/\D/g, '');
            return v.length <= 11 ? v.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/g, "$1.$2.$3-$4") : v.replace(
                /(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/g, "$1.$2.$3/$4-$5");
        };

        document.addEventListener('DOMContentLoaded', () => {
            const cpfInput = document.getElementById('cpf_cnpj');
            cpfInput.addEventListener('input', (e) => e.target.value = cpfCnpjMask(e.target.value));

            cpfInput.addEventListener('blur', async () => {
                const val = cpfInput.value.replace(/\D/g, '');
                if (val.length < 11) return;
                document.getElementById('searchingMsg').classList.remove('hidden');
                try {
                    const res = await fetch(`/api/check-client?cpf_cnpj=${val}`);
                    const json = await res.json();
                    if (json.exists) {
                        const d = json.data;
                        Swal.fire('Cadastro Localizado', 'Dados carregados.', 'info');
                        document.getElementById('name').value = d.name || '';
                        document.getElementById('email').value = d.email || '';
                        if (d.address) {
                            document.getElementById('postal_code').value = d.address.postal_code || '';
                            document.getElementById('street').value = d.address.street || '';
                            document.getElementById('city').value = d.address.city || '';
                            document.getElementById('state').value = d.address.state ||
                            ''; // PREENCHE ESTADO
                        }
                    }
                } catch (e) {
                    console.error(e);
                } finally {
                    document.getElementById('searchingMsg').classList.add('hidden');
                }
            });
        });

        function goToStep(s) {
            // Validação simples de senha no Step 1
            if (s === 2) {
                const p1 = document.getElementsByName('password')[0].value;
                const p2 = document.getElementsByName('passwordConfirmation')[0].value;
                if (p1 && p1 !== p2) {
                    Swal.fire('Erro', 'As senhas não coincidem!', 'error');
                    return;
                }
            }
            document.querySelectorAll('[id^="step-"]').forEach(el => el.classList.add('hidden'));
            document.getElementById('step-' + s).classList.remove('hidden');
            const l1 = document.getElementById('progress_line');
            const l2 = document.getElementById('progress_line_2');
            if (l1) l1.style.width = s >= 2 ? '100%' : '0%';
            if (l2) l2.style.width = s === 3 ? '100%' : '0%';
            window.scrollTo(0, 0);
        }

        document.getElementById('registerForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            document.getElementById('btnText').classList.add('hidden');
            document.getElementById('btnSpinner').classList.remove('hidden');

            try {
                const res = await fetch('{{ route('register.custom.store') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                const data = await res.json();
                if (res.ok && data.success) {
                    Swal.fire('Sucesso!', 'Cadastro realizado!', 'success').then(() => window.location.href =
                        data.redirect);
                } else {
                    Swal.fire('Erro', data.message || 'Verifique os dados.', 'error');
                }
            } catch (err) {
                Swal.fire('Erro', 'Falha no servidor.', 'error');
            } finally {
                document.getElementById('btnText').classList.remove('hidden');
                document.getElementById('btnSpinner').classList.add('hidden');
            }
        });
    </script>
@endsection
