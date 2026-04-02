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
                            <label class="block font-semibold text-gray-700">Data de Nascimento </label>
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
                        <div>
                            <label class="block font-semibold text-gray-700">Nome da Mãe *</label>
                            <input type="text" name="mother" id="mother"
                                class="w-full border rounded-lg px-4 py-2 outline-none focus:ring-2 focus:ring-green-500 uppercase">
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700">Profissão </label>
                            <input type="text" name="occupation" id="occupation"
                                class="w-full border rounded-lg px-4 py-2 outline-none focus:ring-2 focus:ring-green-500 uppercase">
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700">Renda Mensal </label>
                            <input type="text" name="income" id="income" placeholder="R$ 0,00"
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
                            <label class="block font-semibold text-gray-700">Complemento</label>
                            <input type="text" name="complement" id="complement"
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
                            <label class="block font-semibold text-gray-700">Documento Pessoal (CNH ou RG) </label>
                            <input type="file" name="cnh_rg" class="w-full border p-2 rounded-lg bg-gray-50">
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700">Comprovante de Renda </label>
                            <input type="file" name="document_income" class="w-full border p-2 rounded-lg bg-gray-50">
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700">Comprovante de Residência </label>
                            <input type="file" name="document_residence"
                                class="w-full border p-2 rounded-lg bg-gray-50">
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
        // --- FUNÇÕES DE MÁSCARA ---
        const moneyMask = (v) => {
            v = v.replace(/\D/g, "");
            v = v.replace(/(\d)(\d{2})$/, "$1,$2");
            v = v.replace(/(?=(\d{3})+(\D))\B/g, ".");
            return "R$ " + v;
        };

        const cpfCnpjMask = (v) => {
            v = v.replace(/\D/g, '');
            if (v.length <= 11) {
                return v.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/g, "$1.$2.$3-$4");
            } else {
                return v.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/g, "$1.$2.$3/$4-$5");
            }
        };

        const dateMask = (v) => {
            v = v.replace(/\D/g, '');
            if (v.length > 4) v = v.replace(/^(\d{2})(\d{2})(\d{0,4}).*/, '$1/$2/$3');
            else if (v.length > 2) v = v.replace(/^(\d{2})(\d{0,2})/, '$1/$2');
            return v;
        };

        const phoneMask = (v) => {
            v = v.replace(/\D/g, '');
            if (v.length > 10) {
                return v.replace(/^(\d{2})(\d{5})(\d{4}).*/, '($1) $2-$3');
            } else {
                return v.replace(/^(\d{2})(\d{4})(\d{4}).*/, '($1) $2-$3');
            }
        };

        const cepMask = (v) => {
            v = v.replace(/\D/g, '');
            return v.replace(/^(\d{5})(\d{3}).*/, '$1-$2');
        };

        document.addEventListener('DOMContentLoaded', () => {
            const cpfInput = document.getElementById('cpf_cnpj');
            const birthInput = document.getElementById('birth_date');
            const whatsappInput = document.getElementById('whatsapp');
            const cepInput = document.getElementById('postal_code');
            const incomeInput = document.getElementById('income');

            // Aplicar Máscaras em tempo real
            cpfInput.addEventListener('input', (e) => e.target.value = cpfCnpjMask(e.target.value));
            birthInput.addEventListener('input', (e) => e.target.value = dateMask(e.target.value));
            whatsappInput.addEventListener('input', (e) => e.target.value = phoneMask(e.target.value));
            cepInput.addEventListener('input', (e) => e.target.value = cepMask(e.target.value));
            incomeInput.addEventListener('input', (e) => e.target.value = moneyMask(e.target.value));


            // Autocompletar Endereço pelo CEP (ViaCEP)
            cepInput.addEventListener('blur', async () => {
                const cep = cepInput.value.replace(/\D/g, '');
                if (cep.length !== 8) return;

                try {
                    const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
                    const data = await response.json();
                    if (!data.erro) {
                        document.getElementById('street').value = data.logradouro.toUpperCase();
                        document.getElementById('district').value = data.bairro.toUpperCase();
                        document.getElementById('city').value = data.localidade.toUpperCase();
                        document.getElementById('state').value = data.uf.toUpperCase();
                        document.getElementById('number').focus();
                    }
                } catch (e) {
                    console.error("Erro ao buscar CEP");
                }
            });

            // Lógica de Autocompletar Cadastro Existente (CPF)
            cpfInput.addEventListener('blur', async () => {
                const val = cpfInput.value.replace(/\D/g, '');
                if (val.length < 11) return;

                document.getElementById('searchingMsg').classList.remove('hidden');
                try {
                    const res = await fetch(`/api/check-client?cpf_cnpj=${cpfInput.value}`);
                    const json = await res.json();
                    if (json.exists) {
                        const d = json.data;
                        Swal.fire('Cadastro Localizado', 'Seus dados foram carregados automaticamente.',
                            'info');
                        document.getElementById('name').value = d.name || '';
                        document.getElementById('email').value = d.email || '';
                        if (d.whatsapp) {
                            document.getElementById('whatsapp').value = phoneMask(d.whatsapp);
                        }
                        document.getElementById('birth_date').value = d.birth_date || '';


                        if (d.birth_date && d.birth_date.includes('-')) {
                            const [y, m, d_part] = d.birth_date.split('-');
                            document.getElementById('birth_date').value = `${d_part}/${m}/${y}`;
                        }

                        document.getElementById('mother').value = d.mother || '';
                        document.getElementById('occupation').value = d.occupation || '';
                        document.getElementById('income').value = d.income || '';

                        if (d.address) {
                            document.getElementById('postal_code').value = cepMask(d.address
                                .postal_code || '');
                            document.getElementById('street').value = d.address.street || '';
                            document.getElementById('number').value = d.address.number || '';
                            document.getElementById('district').value = d.address.district || '';
                            document.getElementById('city').value = d.address.city || '';
                            document.getElementById('state').value = d.address.state || '';
                            document.getElementById('complement').value = d.address.complement || '';
                        }
                    }
                } catch (e) {
                    console.log("Erro na busca.");
                } finally {
                    document.getElementById('searchingMsg').classList.add('hidden');
                }
            });
        });

        function goToStep(s) {
            // Se o usuário está tentando ir para o passo 2, validamos o passo 1
            if (s === 2) {
                const nomeMae = document.getElementById('mother_name').value.trim();
                const email = document.getElementById('email').value.trim();
                const p1 = document.getElementsByName('password')[0].value;
                const p2 = document.getElementsByName('passwordConfirmation')[0].value;

                // Validação Nome da Mãe
                if (!nomeMae) {
                    Swal.fire('Atenção', 'O nome da mãe é obrigatório.', 'warning');
                    return;
                }

                // Validação de E-mail (Regex Simples)
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!email || !emailRegex.test(email)) {
                    Swal.fire('E-mail Inválido', 'Por favor, insira um e-mail válido.', 'warning');
                    return;
                }

                // Validação de Senha
                if (p1.length < 6) {
                    Swal.fire('Senha Curta', 'A senha deve ter pelo menos 6 caracteres.', 'warning');
                    return;
                }
                if (p1 !== p2) {
                    Swal.fire('Erro', 'As senhas não conferem!', 'error');
                    return;
                }
            }

            // Lógica de navegação visual (mantida)
            document.querySelectorAll('[id^="step-"]').forEach(el => el.classList.add('hidden'));
            document.getElementById('step-' + s).classList.remove('hidden');

            const l1 = document.getElementById('progress_line');
            const l2 = document.getElementById('progress_line_2');
            if (l1) l1.style.width = s >= 2 ? '100%' : '0%';
            if (l2) l2.style.width = s === 3 ? '100%' : '0%';
            window.scrollTo(0, 0);
        }

        // Submissão Final
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
                    Swal.fire('Sucesso!', 'Cadastro realizado!', 'success').then(() => {
                        window.location.href = data.redirect;
                    });
                } else {
                    Swal.fire('Atenção', data.message || 'Verifique os campos.', 'warning');
                }
            } catch (err) {
                Swal.fire('Erro', 'Falha na comunicação.', 'error');
            } finally {
                document.getElementById('btnText').classList.remove('hidden');
                document.getElementById('btnSpinner').classList.add('hidden');
            }
        });
    </script>
@endsection
