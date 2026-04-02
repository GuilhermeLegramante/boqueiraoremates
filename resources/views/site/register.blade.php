@extends('site.master')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Animação para erro (Tremer) */
        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-8px);
            }

            75% {
                transform: translateX(8px);
            }
        }

        .animate-shake {
            animation: shake 0.2s ease-in-out 0s 2;
        }

        /* Estilos de progresso */
        .step-active {
            border-color: #059669 !important;
            background-color: #059669 !important;
            color: white !important;
            transform: scale(1.1);
        }

        .step-line-active {
            background-color: #059669 !important;
        }

        /* Melhoria visual para idosos: Foco e Labels */
        input:focus {
            border-color: #2563eb !important;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        label {
            cursor: pointer;
        }
    </style>

    <section class="flex justify-center bg-slate-100 pt-24 pb-16 min-h-screen">
        <div class="w-full max-w-4xl px-4">
            <div class="bg-white rounded-[40px] shadow-2xl overflow-hidden border border-slate-200">

                <div class="bg-slate-900 p-10 text-center">
                    <h2 class="text-4xl font-black text-white mb-3 uppercase tracking-tight">Criar Minha Conta</h2>
                    <p class="text-slate-400 text-xl font-medium">Preencha os passos abaixo para acessar o sistema.</p>

                    <div class="flex items-center justify-center mt-10 gap-3 md:gap-6">
                        <div id="dot-1"
                            class="step-active flex items-center justify-center w-14 h-14 rounded-full border-4 border-slate-700 text-xl font-black transition-all">
                            1</div>
                        <div id="line-1" class="h-2 w-10 md:w-20 bg-slate-700 rounded-full"></div>
                        <div id="dot-2"
                            class="flex items-center justify-center w-14 h-14 rounded-full border-4 border-slate-700 text-slate-500 text-xl font-black transition-all">
                            2</div>
                        <div id="line-2" class="h-2 w-10 md:w-20 bg-slate-700 rounded-full"></div>
                        <div id="dot-3"
                            class="flex items-center justify-center w-14 h-14 rounded-full border-4 border-slate-700 text-slate-500 text-xl font-black transition-all">
                            3</div>
                    </div>
                </div>

                <form id="registerForm" class="p-8 md:p-14">
                    @csrf

                    <div class="form-step" id="step-1">
                        <div class="mb-10 flex items-center gap-4">
                            <span
                                class="bg-blue-600 text-white w-10 h-10 flex items-center justify-center rounded-full font-bold">1</span>
                            <h3 class="text-2xl font-black text-slate-800 uppercase">Dados de Identificação</h3>
                        </div>

                        <div class="grid grid-cols-1 gap-8">
                            <div>
                                <label class="block text-xl font-black text-slate-700 mb-3" for="cpf_cnpj">Seu CPF ou
                                    CNPJ</label>
                                <input type="text" name="cpf_cnpj" id="cpf_cnpj"
                                    class="w-full text-2xl font-bold bg-slate-50 border-4 border-slate-200 rounded-2xl px-8 py-6 outline-none transition-all"
                                    placeholder="000.000.000-00">
                                <div id="error-cpf_cnpj"
                                    class="hidden mt-3 p-4 bg-red-50 border-l-8 border-red-500 text-red-700 font-bold rounded-r-xl">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div>
                                    <label class="block text-xl font-black text-slate-700 mb-3">Nome Completo</label>
                                    <input type="text" name="name" id="name"
                                        class="w-full text-xl font-bold bg-slate-50 border-4 border-slate-200 rounded-2xl px-8 py-6 outline-none transition-all">
                                    <div id="error-name" class="hidden mt-2 text-red-600 font-bold"></div>
                                </div>
                                <div>
                                    <label class="block text-xl font-black text-slate-700 mb-3">E-mail</label>
                                    <input type="email" name="email" id="email"
                                        class="w-full text-xl font-bold bg-slate-50 border-4 border-slate-200 rounded-2xl px-8 py-6 outline-none transition-all">
                                    <div id="error-email" class="hidden mt-2 text-red-600 font-bold"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-step hidden" id="step-2">
                        <div class="mb-10 flex items-center gap-4">
                            <span
                                class="bg-blue-600 text-white w-10 h-10 flex items-center justify-center rounded-full font-bold">2</span>
                            <h3 class="text-2xl font-black text-slate-800 uppercase">Onde você mora?</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <div>
                                <label class="block text-xl font-black text-slate-700 mb-3">CEP</label>
                                <input type="text" name="postal_code" id="postal_code"
                                    class="w-full text-xl font-bold bg-slate-50 border-4 border-slate-200 rounded-2xl px-8 py-6 outline-none transition-all">
                                <div id="error-postal_code" class="hidden mt-2 text-red-600 font-bold"></div>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xl font-black text-slate-700 mb-3">Rua / Logradouro</label>
                                <input type="text" name="street" id="street"
                                    class="w-full text-xl font-bold bg-slate-50 border-4 border-slate-200 rounded-2xl px-8 py-6 outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-xl font-black text-slate-700 mb-3">Bairro</label>
                                <input type="text" name="district" id="district"
                                    class="w-full text-xl font-bold bg-slate-50 border-4 border-slate-200 rounded-2xl px-8 py-6 outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-xl font-black text-slate-700 mb-3">Número</label>
                                <input type="text" name="number" id="number"
                                    class="w-full text-xl font-bold bg-slate-50 border-4 border-slate-200 rounded-2xl px-8 py-6 outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-xl font-black text-slate-700 mb-3">Cidade</label>
                                <input type="text" name="city" id="city"
                                    class="w-full text-xl font-bold bg-slate-50 border-4 border-slate-200 rounded-2xl px-8 py-6 outline-none transition-all">
                            </div>
                        </div>
                    </div>

                    <div class="form-step hidden" id="step-3">
                        <div class="mb-10 flex items-center gap-4">
                            <span
                                class="bg-blue-600 text-white w-10 h-10 flex items-center justify-center rounded-full font-bold">3</span>
                            <h3 class="text-2xl font-black text-slate-800 uppercase">Segurança e Contato</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-xl font-black text-slate-700 mb-3">Data de Nascimento</label>
                                <input type="text" name="birth_date" id="birth_date"
                                    class="w-full text-xl font-bold bg-slate-50 border-4 border-slate-200 rounded-2xl px-8 py-6 outline-none transition-all"
                                    placeholder="DD/MM/AAAA">
                                <div id="error-birth_date" class="hidden mt-2 text-red-600 font-bold"></div>
                            </div>
                            <div>
                                <label class="block text-xl font-black text-slate-700 mb-3">WhatsApp</label>
                                <input type="text" name="whatsapp" id="whatsapp"
                                    class="w-full text-xl font-bold bg-slate-50 border-4 border-slate-200 rounded-2xl px-8 py-6 outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-xl font-black text-slate-700 mb-3">Criar Senha</label>
                                <input type="password" name="password"
                                    class="w-full text-xl font-bold bg-slate-50 border-4 border-slate-200 rounded-2xl px-8 py-6 outline-none transition-all">
                                <div id="error-password" class="hidden mt-2 text-red-600 font-bold"></div>
                            </div>
                            <div>
                                <label class="block text-xl font-black text-slate-700 mb-3">Repetir Senha</label>
                                <input type="password" name="password_confirmation"
                                    class="w-full text-xl font-bold bg-slate-50 border-4 border-slate-200 rounded-2xl px-8 py-6 outline-none transition-all">
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-16 pt-10 border-t-4 border-slate-50">
                        <button type="button" id="prevBtn"
                            class="hidden text-slate-400 font-black text-xl hover:text-slate-800 p-4 transition-all">
                            ← VOLTAR
                        </button>

                        <button type="button" id="nextBtn"
                            class="ml-auto bg-blue-600 hover:bg-blue-700 text-white font-black text-2xl px-14 py-7 rounded-3xl shadow-2xl hover:shadow-blue-200 transition-all active:scale-95">
                            PRÓXIMO PASSO →
                        </button>

                        <button type="submit" id="submitBtn"
                            class="hidden ml-auto bg-green-600 hover:bg-green-700 text-white font-black text-2xl px-14 py-7 rounded-3xl shadow-2xl hover:shadow-green-200 transition-all active:scale-95">
                            FINALIZAR CADASTRO ✔
                        </button>
                    </div>
                </form>
            </div>

            <div class="mt-12 text-center p-10 bg-white rounded-[40px] border-4 border-blue-100 shadow-lg">
                <p class="text-slate-500 text-xl font-bold mb-2">Está com alguma dificuldade?</p>
                <p class="text-blue-600 text-3xl font-black italic">Ligue: (00) 0000-0000</p>
            </div>
        </div>
    </section>

    <script src="https://unpkg.com/imask"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('registerForm');
            const steps = document.querySelectorAll('.form-step');
            let currentStep = 0;

            // 1. APLICAR MÁSCARAS
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

            // 2. NAVEGAÇÃO DOS PASSOS
            const updateWizard = () => {
                steps.forEach((s, i) => s.classList.toggle('hidden', i !== currentStep));

                for (let i = 1; i <= 3; i++) {
                    document.getElementById(`dot-${i}`).classList.toggle('step-active', (i - 1) <= currentStep);
                    if (i < 3) document.getElementById(`line-${i}`).classList.toggle('step-line-active', (i -
                        1) < currentStep);
                }

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

            // 3. CONSULTA CPF (MODERNA)
            document.getElementById('cpf_cnpj').addEventListener('blur', async (e) => {
                const val = e.target.value;
                if (val.length < 14) return;

                try {
                    const res = await fetch(`/api/check-client?cpf_cnpj=${encodeURIComponent(val)}`);
                    const json = await res.json();

                    if (json.exists) {
                        Swal.fire({
                            title: 'Já encontramos você!',
                            text: 'Preenchemos alguns dados automaticamente para facilitar.',
                            icon: 'info',
                            confirmButtonText: 'Ótimo!'
                        });
                        document.getElementById('name').value = json.data.name;
                        document.getElementById('email').value = json.data.email;
                        if (json.data.address) {
                            document.getElementById('street').value = json.data.address.street;
                            document.getElementById('district').value = json.data.address.district;
                            document.getElementById('city').value = json.data.address.city;
                            document.getElementById('postal_code').value = json.data.address
                            .postal_code;
                        }
                    }
                } catch (err) {
                    console.log("Erro na busca de CPF");
                }
            });

            // 4. ENVIO DO FORMULÁRIO COM MODAL DE SUCESSO
            form.addEventListener('submit', async (e) => {
                e.preventDefault();

                // Reset de erros
                document.querySelectorAll('[id^="error-"]').forEach(d => d.classList.add('hidden'));
                document.querySelectorAll('input').forEach(i => i.classList.replace('border-red-500',
                    'border-slate-200'));

                const submitBtn = document.getElementById('submitBtn');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = "Salvando...";
                submitBtn.disabled = true;

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
                        // Erros de Validação
                        Object.keys(data.errors).forEach(key => {
                            const errDiv = document.getElementById(`error-${key}`);
                            const input = document.getElementsByName(key)[0];
                            if (errDiv) {
                                errDiv.textContent = data.errors[key][0];
                                errDiv.classList.remove('hidden');
                                errDiv.classList.add('animate-shake');
                            }
                            if (input) input.classList.replace('border-slate-200',
                                'border-red-500');
                        });

                        // Se erro for no Passo 1, volta pra lá automaticamente
                        if (data.errors.cpf_cnpj || data.errors.name || data.errors.email) {
                            currentStep = 0;
                            updateWizard();
                        }
                    } else if (data.success) {
                        // MODAL DE SUCESSO MODERNA
                        Swal.fire({
                            title: '<span style="color:#059669">TUDO PRONTO!</span>',
                            html: `
                            <div class="p-4 text-center">
                                <p class="text-xl font-bold text-slate-700 mb-2">Cadastro concluído com sucesso!</p>
                                <p class="text-lg text-slate-500">Estamos preparando seu acesso, aguarde um instante...</p>
                                <div class="w-full bg-slate-100 h-3 rounded-full mt-6 overflow-hidden">
                                    <div id="l-bar" class="bg-green-500 h-full w-0 transition-all duration-[3000ms] ease-linear"></div>
                                </div>
                            </div>
                        `,
                            icon: 'success',
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            timer: 3500,
                            didOpen: () => {
                                setTimeout(() => {
                                    document.getElementById('l-bar').style.width =
                                        '100%';
                                }, 100);
                            },
                            willClose: () => {
                                window.location.href = data.redirect;
                            }
                        });
                    }
                } catch (err) {
                    Swal.fire('Ops!', 'Verifique sua internet e tente novamente.', 'error');
                } finally {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            });
        });
    </script>
@endsection
