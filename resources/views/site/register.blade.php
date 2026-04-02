@extends('site.master')

@section('content')
    <section class="flex justify-center bg-gray-100 pt-32 pb-16 min-h-screen mt-4">
        <div class="w-full max-w-4xl">
            <div class="bg-white p-8 rounded-xl shadow-lg">
                <h2 class="text-3xl font-bold text-center mb-6">Cadastro de Cliente</h2>

                <div class="flex justify-between mb-8">
                    <div class="step-idx text-green-700 font-bold border-b-2 border-green-700">1. Pessoal</div>
                    <div class="step-idx text-gray-400">2. Endereço</div>
                    <div class="step-idx text-gray-400">3. Adicional</div>
                </div>

                <form id="registerForm" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div class="form-step" id="step-1">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-semibold">CPF/CNPJ</label>
                                <input type="text" name="cpf_cnpj" id="cpf_cnpj"
                                    class="w-full border rounded-lg px-4 py-2" required>
                                <span id="cpf_cnpj_error" class="text-red-500 text-xs hidden"></span>
                            </div>
                            <div>
                                <label class="block font-semibold">Nome Completo</label>
                                <input type="text" name="name" id="name"
                                    class="w-full border rounded-lg px-4 py-2" required>
                            </div>
                            <div>
                                <label class="block font-semibold">E-mail</label>
                                <input type="email" name="email" id="email"
                                    class="w-full border rounded-lg px-4 py-2" required>
                            </div>
                            <div>
                                <label class="block font-semibold">Data de Nascimento</label>
                                <input type="text" name="birth_date" id="birth_date" placeholder="dd/mm/aaaa"
                                    class="w-full border rounded-lg px-4 py-2" required>
                            </div>
                            <div>
                                <label class="block font-semibold">Senha</label>
                                <input type="password" name="password" class="w-full border rounded-lg px-4 py-2" required>
                            </div>
                            <div>
                                <label class="block font-semibold">Confirmar Senha</label>
                                <input type="password" name="password_confirmation"
                                    class="w-full border rounded-lg px-4 py-2" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-step hidden" id="step-2">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-1">
                                <label class="block font-semibold">CEP</label>
                                <input type="text" name="postal_code" id="postal_code"
                                    class="w-full border rounded-lg px-4 py-2">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block font-semibold">Rua</label>
                                <input type="text" name="street" id="street"
                                    class="w-full border rounded-lg px-4 py-2 uppercase">
                            </div>
                            <div class="md:col-span-1">
                                <label class="block font-semibold">Número</label>
                                <input type="text" name="number" id="number"
                                    class="w-full border rounded-lg px-4 py-2">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block font-semibold">Bairro</label>
                                <input type="text" name="district" id="district"
                                    class="w-full border rounded-lg px-4 py-2 uppercase">
                            </div>
                            <div>
                                <label class="block font-semibold">Cidade</label>
                                <input type="text" name="city" id="city"
                                    class="w-full border rounded-lg px-4 py-2 uppercase">
                            </div>
                            <div>
                                <label class="block font-semibold">Estado (UF)</label>
                                <input type="text" name="state" id="state" maxlength="2"
                                    class="w-full border rounded-lg px-4 py-2 uppercase">
                            </div>
                        </div>
                    </div>

                    <div class="form-step hidden" id="step-3">
                        <div class="space-y-4">
                            <div>
                                <label class="block font-semibold">RG / CNH (Upload)</label>
                                <input type="file" name="cnh_rg" class="w-full border px-4 py-2">
                            </div>
                            <div>
                                <label class="block font-semibold">WhatsApp</label>
                                <input type="text" name="whatsapp" id="whatsapp"
                                    class="w-full border rounded-lg px-4 py-2">
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="checkbox" name="has_register_in_another_auctioneer" value="1">
                                <label>Possui cadastro em outro leiloeiro?</label>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between pt-6">
                        <button type="button" id="prevBtn"
                            class="hidden bg-gray-500 text-white px-6 py-2 rounded-lg">Anterior</button>
                        <button type="button" id="nextBtn"
                            class="bg-green-700 text-white px-6 py-2 rounded-lg ml-auto">Próximo</button>
                        <button type="submit" id="submitBtn"
                            class="hidden bg-blue-700 text-white px-6 py-2 rounded-lg ml-auto">Criar Conta</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let currentStep = 1;
            const form = document.getElementById('registerForm');
            const steps = document.querySelectorAll('.form-step');
            const stepIndicators = document.querySelectorAll('.step-idx');

            // Máscara de CPF ou CNPJ Dinâmica
            const cpfCnpjElem = document.getElementById('cpf_cnpj');
            const cpfCnpjMask = IMask(cpfCnpjElem, {
                mask: [{
                        mask: '000.000.000-00',
                        type: 'CPF'
                    },
                    {
                        mask: '00.000.000/0000-00',
                        type: 'CNPJ'
                    }
                ]
            });

            // Máscara de Data de Nascimento
            const birthDateElem = document.getElementById('birth_date');
            IMask(birthDateElem, {
                mask: '00/00/0000'
            });

            // Máscara de CEP
            const cepElem = document.getElementById('postal_code');
            IMask(cepElem, {
                mask: '00000-000'
            });

            // Máscara de WhatsApp (Celular)
            const whatsappElem = document.getElementById('whatsapp');
            IMask(whatsappElem, {
                mask: '(00) 00000-0000'
            });

            // 1. Navegação Wizard
            const updateWizard = () => {
                steps.forEach((s, i) => s.classList.toggle('hidden', i !== currentStep - 1));
                document.getElementById('prevBtn').classList.toggle('hidden', currentStep === 1);
                document.getElementById('nextBtn').classList.toggle('hidden', currentStep === 3);
                document.getElementById('submitBtn').classList.toggle('hidden', currentStep !== 3);

                stepIndicators.forEach((ind, i) => {
                    if (i < currentStep) ind.classList.add('text-green-700', 'font-bold', 'border-b-2',
                        'border-green-700');
                    else ind.classList.remove('text-green-700', 'font-bold', 'border-b-2',
                        'border-green-700');
                });
            };

            document.getElementById('nextBtn').addEventListener('click', () => {
                currentStep++;
                updateWizard();
            });
            document.getElementById('prevBtn').addEventListener('click', () => {
                currentStep--;
                updateWizard();
            });

            // 2. Lógica reativa do CPF (Igual ao Filament afterStateUpdated)
            const cpfInput = document.getElementById('cpf_cnpj');
            cpfInput.addEventListener('blur', async () => {
                // Pegamos o valor direto do input (com máscara)
                const valorComMascara = cpfInput.value;

                if (valorComMascara.length < 14) return; // Mínimo para CPF formatado

                try {
                    const response = await fetch(
                        `/api/check-client?cpf_cnpj=${encodeURIComponent(valorComMascara)}`);
                    const result = await response.json();

                    if (result.exists) {
                        // Preenchimento dos campos...
                        document.getElementById('name').value = result.data.name;
                        document.getElementById('email').value = result.data.email;

                        if (result.data.address) {
                            document.getElementById('street').value = result.data.address.street || '';
                            document.getElementById('city').value = result.data.address.city || '';
                            document.getElementById('district').value = result.data.address.district ||
                                '';
                            document.getElementById('state').value = result.data.address.state || '';
                            document.getElementById('postal_code').value = result.data.address
                                .postal_code || '';
                            document.getElementById('number').value = result.data.address.number || '';
                        }
                    }
                } catch (err) {
                    console.error('Erro ao consultar CPF:', err);
                }
            });

            // 3. Envio do Formulário via AJAX
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const formData = new FormData(form);

                try {
                    const res = await fetch('{{ route('register.custom.store') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    const data = await res.json();
                    if (data.success) window.location.href = data.redirect;
                    else alert('Erro: ' + data.message);
                } catch (err) {
                    console.error(err);
                }
            });
        });
    </script>
@endsection
