@extends('site.master')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Estilo para destacar campos com erro */
        .input-error {
            border-color: #ef4444 !important;
            background-color: #fef2f2 !important;
        }

        .text-error {
            color: #ef4444;
            font-size: 0.875rem;
            font-weight: 600;
            margin-top: 0.25rem;
            display: block;
        }

        /* Animação suave de tremer */
        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-4px);
            }

            75% {
                transform: translateX(4px);
            }
        }

        .shake {
            animation: shake 0.2s ease-in-out 0s 2;
        }

        /* Wizard simples (Passos) */
        .step-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }

        .step-number {
            background: #333;
            color: #fff;
            width: 25px;
            height: 25px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin-right: 10px;
            font-size: 14px;
        }
    </style>

    <section class="register-section pt-24 pb-16">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto bg-white shadow-lg rounded-lg border border-gray-200">

                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-2xl font-bold text-gray-800">Cadastro de Usuário</h2>
                    <p class="text-gray-500">Preencha os campos abaixo para criar sua conta.</p>
                </div>

                <form id="registerForm" class="p-8">
                    @csrf

                    <div class="form-step" id="step-1">
                        <div class="step-header">
                            <span class="step-number">1</span>
                            <h3 class="font-bold uppercase text-gray-700">Identificação</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block font-semibold text-gray-700">CPF ou CNPJ *</label>
                                <input type="text" name="cpf_cnpj" id="cpf_cnpj"
                                    class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 outline-none transition-all"
                                    placeholder="000.000.000-00">
                                <span id="error-cpf_cnpj" class="text-error hidden"></span>
                            </div>

                            <div>
                                <label class="block font-semibold text-gray-700">Nome Completo *</label>
                                <input type="text" name="name" id="name"
                                    class="w-full border border-gray-300 rounded-md p-2 outline-none">
                                <span id="error-name" class="text-error hidden"></span>
                            </div>

                            <div>
                                <label class="block font-semibold text-gray-700">E-mail *</label>
                                <input type="email" name="email" id="email"
                                    class="w-full border border-gray-300 rounded-md p-2 outline-none">
                                <span id="error-email" class="text-error hidden"></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-step hidden" id="step-2">
                        <div class="step-header">
                            <span class="step-number">2</span>
                            <h3 class="font-bold uppercase text-gray-700">Endereço</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block font-semibold text-gray-700">CEP *</label>
                                <input type="text" name="postal_code" id="postal_code"
                                    class="w-full border border-gray-300 rounded-md p-2 outline-none">
                                <span id="error-postal_code" class="text-error hidden"></span>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block font-semibold text-gray-700">Rua *</label>
                                <input type="text" name="street" id="street"
                                    class="w-full border border-gray-300 rounded-md p-2 outline-none">
                                <span id="error-street" class="text-error hidden"></span>
                            </div>
                            <div>
                                <label class="block font-semibold text-gray-700">Bairro *</label>
                                <input type="text" name="district" id="district"
                                    class="w-full border border-gray-300 rounded-md p-2 outline-none">
                            </div>
                            <div>
                                <label class="block font-semibold text-gray-700">Número *</label>
                                <input type="text" name="number" id="number"
                                    class="w-full border border-gray-300 rounded-md p-2 outline-none">
                            </div>
                            <div>
                                <label class="block font-semibold text-gray-700">Cidade *</label>
                                <input type="text" name="city" id="city"
                                    class="w-full border border-gray-300 rounded-md p-2 outline-none">
                            </div>
                        </div>
                    </div>

                    <div class="form-step hidden" id="step-3">
                        <div class="step-header">
                            <span class="step-number">3</span>
                            <h3 class="font-bold uppercase text-gray-700">Dados Finais</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-semibold text-gray-700">Data de Nascimento *</label>
                                <input type="text" name="birth_date" id="birth_date"
                                    class="w-full border border-gray-300 rounded-md p-2 outline-none"
                                    placeholder="00/00/0000">
                                <span id="error-birth_date" class="text-error hidden"></span>
                            </div>
                            <div>
                                <label class="block font-semibold text-gray-700">WhatsApp *</label>
                                <input type="text" name="whatsapp" id="whatsapp"
                                    class="w-full border border-gray-300 rounded-md p-2 outline-none">
                            </div>
                            <div>
                                <label class="block font-semibold text-gray-700">Senha *</label>
                                <input type="password" name="password"
                                    class="w-full border border-gray-300 rounded-md p-2 outline-none">
                                <span id="error-password" class="text-error hidden"></span>
                            </div>
                            <div>
                                <label class="block font-semibold text-gray-700">Confirmar Senha *</label>
                                <input type="password" name="password_confirmation"
                                    class="w-full border border-gray-300 rounded-md p-2 outline-none">
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-between">
                        <button type="button" id="prevBtn"
                            class="hidden px-6 py-2 bg-gray-200 text-gray-700 rounded font-bold hover:bg-gray-300">ANTERIOR</button>

                        <button type="button" id="nextBtn"
                            class="ml-auto px-6 py-2 bg-blue-600 text-white rounded font-bold hover:bg-blue-700">PRÓXIMO</button>

                        <button type="submit" id="submitBtn"
                            class="hidden ml-auto px-6 py-2 bg-green-600 text-white rounded font-bold hover:bg-green-700">FINALIZAR
                            CADASTRO</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script src="https://unpkg.com/imask"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('registerForm');
            const steps = document.querySelectorAll('.form-step');
            let currentStep = 0;

            // Máscaras
            IMask(document.getElementById('cpf_cnpj'), {
                mask: [{
                    mask: '000.000.000-00'
                }, {
                    mask: '00.000.000/0000-00'
                }]
            });
            IMask(document.getElementById('postal_code'), {
                mask: '00000-000'
            });
            IMask(document.getElementById('birth_date'), {
                mask: '00/00/0000'
            });
            IMask(document.getElementById('whatsapp'), {
                mask: '(00) 00000-0000'
            });

            const updateWizard = () => {
                steps.forEach((s, i) => s.classList.toggle('hidden', i !== currentStep));
                document.getElementById('prevBtn').classList.toggle('hidden', currentStep === 0);
                document.getElementById('nextBtn').classList.toggle('hidden', currentStep === steps.length - 1);
                document.getElementById('submitBtn').classList.toggle('hidden', currentStep !== steps.length -
                    1);
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
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

            // Consulta CPF
            document.getElementById('cpf_cnpj').addEventListener('blur', async (e) => {
                if (e.target.value.length < 14) return;
                const res = await fetch(
                    `/api/check-client?cpf_cnpj=${encodeURIComponent(e.target.value)}`);
                const json = await res.json();
                if (json.exists) {
                    document.getElementById('name').value = json.data.name;
                    document.getElementById('email').value = json.data.email;
                    if (json.data.address) {
                        document.getElementById('street').value = json.data.address.street;
                        document.getElementById('district').value = json.data.address.district;
                        document.getElementById('city').value = json.data.address.city;
                        document.getElementById('postal_code').value = json.data.address.postal_code;
                    }
                }
            });

            // Envio
            form.addEventListener('submit', async (e) => {
                e.preventDefault();

                // Limpar erros
                document.querySelectorAll('.text-error').forEach(d => d.classList.add('hidden'));
                document.querySelectorAll('input').forEach(i => i.classList.remove('input-error',
                    'shake'));

                const submitBtn = document.getElementById('submitBtn');
                submitBtn.disabled = true;
                submitBtn.innerText = "Enviando...";

                try {
                    const res = await fetch('{{ route('register.custom.store') }}', {
                        method: 'POST',
                        body: new FormData(form),
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });

                    const data = await res.json();

                    if (res.status === 422) {
                        Object.keys(data.errors).forEach(key => {
                            const errSpan = document.getElementById(`error-${key}`);
                            const input = document.getElementsByName(key)[0];
                            if (errSpan) {
                                errSpan.textContent = data.errors[key][0];
                                errSpan.classList.remove('hidden');
                            }
                            if (input) {
                                input.classList.add('input-error', 'shake');
                            }
                        });

                        // Se erro no passo 1, volta
                        if (data.errors.cpf_cnpj || data.errors.name) {
                            currentStep = 0;
                            updateWizard();
                        }
                    } else if (data.success) {
                        // MODAL DE SUCESSO
                        Swal.fire({
                            title: 'Sucesso!',
                            text: 'Seu cadastro foi realizado. Redirecionando...',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 3000,
                            willClose: () => {
                                window.location.href = data.redirect;
                            }
                        });
                    }
                } catch (err) {
                    Swal.fire('Erro', 'Ocorreu um problema na rede.', 'error');
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerText = "FINALIZAR CADASTRO";
                }
            });
        });
    </script>
@endsection
