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

                    <div id="step-1" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block font-semibold text-gray-700">CPF ou CNPJ *</label>
                            <input type="text" name="cpf_cnpj" id="cpf_cnpj" required
                                class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 outline-none"
                                placeholder="000.000.000-00">
                            <p id="searchingMsg" class="text-green-600 text-xs mt-1 hidden italic">Verificando cadastro
                                existente...</p>
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700">Nome Completo *</label>
                            <input type="text" name="name" id="name" required
                                class="w-full border rounded-lg px-4 py-2 outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700">E-mail *</label>
                            <input type="email" name="email" id="email" required
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
                            <input type="password" name="password" required
                                class="w-full border rounded-lg px-4 py-2 outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700">Confirmar Senha *</label>
                            <input type="password" name="passwordConfirmation" required
                                class="w-full border rounded-lg px-4 py-2 outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        <button type="button" onclick="goToStep(2)"
                            class="md:col-span-2 bg-green-700 text-white py-3 rounded-lg font-bold hover:bg-green-800 transition-all uppercase">Próximo
                            Passo</button>
                    </div>

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
                                placeholder="EX: RS">
                        </div>
                        <div class="flex gap-3 md:col-span-3 mt-4">
                            <button type="button" onclick="goToStep(1)"
                                class="w-1/3 bg-gray-200 text-gray-700 py-3 rounded-lg font-bold">VOLTAR</button>
                            <button type="button" onclick="goToStep(3)"
                                class="w-2/3 bg-green-700 text-white py-3 rounded-lg font-bold">PRÓXIMO</button>
                        </div>
                    </div>

                    <div id="step-3" class="hidden space-y-4">
                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200 mb-4">
                            <p class="text-sm text-blue-800 italic">Anexe fotos nítidas dos seus documentos para agilizar
                                sua aprovação.</p>
                        </div>

                        <div>
                            <label class="block font-semibold text-gray-700">Documento Pessoal (CNH ou RG) *</label>
                            <input type="file" name="cnh_rg" class="w-full border p-2 rounded-lg bg-gray-50">
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700">Comprovante de Residência</label>
                            <input type="file" name="document_residence"
                                class="w-full border p-2 rounded-lg bg-gray-50">
                        </div>
                        <div>
                            <label class="block font-semibold text-gray-700">Comprovante de Renda</label>
                            <input type="file" name="document_income" class="w-full border p-2 rounded-lg bg-gray-50">
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
        // --- FUNÇÕES AUXILIARES DE MÁSCARA ---
        const cpfCnpjMask = (value) => {
            value = value.replace(/\D/g, '');
            if (value.length <= 11) {
                // CPF: 000.000.000-00
                return value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/g, "\$1.\$2.\$3-\$4");
            } else {
                // CNPJ: 00.000.000/0000-00
                return value.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/g, "\$1.\$2.\$3/\$4-\$5");
            }
        }

        const phoneMask = (value) => {
            if (!value) return "";
            value = value.replace(/\D/g, '');
            value = value.replace(/^(\d{2})(\d)/g, "($1) $2");
            value = value.replace(/(\d{5})(\d)/, "$1-$2");
            return value;
        }

        const dateMask = (value) => {
            let v = value.replace(/\D/g, '');
            if (v.length > 4) v = v.replace(/^(\d{2})(\d{2})(\d{0,4}).*/, '$1/$2/$3');
            else if (v.length > 2) v = v.replace(/^(\d{2})(\d{0,2})/, '$1/$2');
            return v;
        }

        document.addEventListener('DOMContentLoaded', () => {
            const cpfInput = document.getElementById('cpf_cnpj');
            const birthInput = document.getElementById('birth_date');
            const whatsappInput = document.getElementById('whatsapp');

            // Máscara CPF/CNPJ
            cpfInput.addEventListener('input', (e) => {
                e.target.value = cpfCnpjMask(e.target.value);
            });

            // Máscara WhatsApp
            whatsappInput.addEventListener('input', (e) => {
                e.target.value = phoneMask(e.target.value);
            });

            // Máscara Nascimento
            birthInput.addEventListener('input', (e) => {
                e.target.value = dateMask(e.target.value);
            });

            // Lógica de Autocompletar ao sair do campo CPF
            cpfInput.addEventListener('blur', async () => {
                const val = cpfInput.value.replace(/\D/g, '');
                if (val.length < 11) return;

                document.getElementById('searchingMsg').classList.remove('hidden');

                try {
                    const res = await fetch(`/api/check-client?cpf_cnpj=${val}`);
                    const json = await res.json();

                    if (json.exists) {
                        const d = json.data;
                        Swal.fire('Cadastro Localizado', 'Seus dados foram carregados automaticamente.',
                            'info');

                        document.getElementById('name').value = d.name || '';
                        document.getElementById('email').value = d.email || '';

                        if (d.birth_date && d.birth_date.includes('-')) {
                            const [year, month, day] = d.birth_date.split('-');
                            document.getElementById('birth_date').value = `${day}/${month}/${year}`;
                        }

                        document.getElementById('whatsapp').value = d.whatsapp ? phoneMask(d.whatsapp) :
                            '';

                        if (d.address) {
                            document.getElementById('postal_code').value = d.address.postal_code || '';
                            document.getElementById('street').value = d.address.street || '';
                            document.getElementById('number').value = d.address.number || '';
                            document.getElementById('district').value = d.address.district || '';
                            document.getElementById('city').value = d.address.city || '';
                            document.getElementById('state').value = d.address.state || ''; 
                        }
                    }
                } catch (e) {
                    console.log("Erro na busca.");
                } finally {
                    document.getElementById('searchingMsg').classList.add('hidden');
                }
            });
        });

        // Submissão do Formulário com tratamento de erros por campo
        document.getElementById('registerForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            // Limpa mensagens de erro anteriores
            document.querySelectorAll('.error-msg').forEach(el => el.remove());
            document.querySelectorAll('input').forEach(el => el.classList.remove('border-red-500'));

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
                    Swal.fire('Sucesso!', 'Cadastro realizado com sucesso.', 'success').then(() => {
                        window.location.href = data.redirect;
                    });
                } else {
                    // Se houver erros de validação (Laravel 422)
                    if (data.errors) {
                        Object.keys(data.errors).forEach(key => {
                            const input = document.getElementsByName(key)[0];
                            if (input) {
                                input.classList.add('border-red-500');
                                const errorPara = document.createElement('p');
                                errorPara.className = 'text-red-500 text-xs mt-1 error-msg';
                                errorPara.innerText = data.errors[key][0];
                                input.parentNode.appendChild(errorPara);
                            }
                        });

                        // Volta para o primeiro passo se houver erro lá
                        const firstErrorKey = Object.keys(data.errors)[0];
                        if (['name', 'email', 'cpf_cnpj', 'birth_date', 'whatsapp', 'password'].includes(
                                firstErrorKey)) {
                            goToStep(1);
                        }

                        Swal.fire('Atenção', 'Por favor, corrija os erros no formulário.', 'warning');
                    } else {
                        Swal.fire('Erro', data.message || 'Erro ao processar.', 'error');
                    }
                }
            } catch (err) {
                Swal.fire('Erro', 'Falha na comunicação com o servidor.', 'error');
            } finally {
                document.getElementById('btnText').classList.remove('hidden');
                document.getElementById('btnSpinner').classList.add('hidden');
            }
        });

        function goToStep(s) {
            document.querySelectorAll('[id^="step-"]').forEach(el => el.classList.add('hidden'));
            document.getElementById('step-' + s).classList.remove('hidden');
            const l1 = document.getElementById('progress_line');
            const l2 = document.getElementById('progress_line_2');
            if (l1) l1.style.width = s >= 2 ? '100%' : '0%';
            if (l2) l2.style.width = s === 3 ? '100%' : '0%';
            window.scrollTo(0, 0);
        }
    </script>
@endsection
