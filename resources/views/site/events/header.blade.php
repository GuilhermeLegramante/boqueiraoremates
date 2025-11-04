<section class="bg-[#003333] text-white py-12 px-6">
    <div class="container mx-auto grid grid-cols-1 lg:grid-cols-3 gap-10 items-start">

        <!-- Banner -->
        <div
            class="w-96 aspect-[1000/720] rounded-2xl shadow-xl border-4 border-white/20 bg-black overflow-hidden mx-auto lg:mx-0">
            <img src="{{ asset('storage/' . $event->banner_min) }}" alt="Banner do evento"
                class="w-full h-full object-contain object-center">
        </div>


        <!-- Informações principais + Badges -->
        <div class="space-y-3 text-center lg:text-left">
            <h2 class="text-3xl font-extrabold tracking-wide">{{ $event->name }}</h2>

            @if (!$event->is_permanent)
                <p class="text-lg font-medium text-yellow-300">
                    {{ $event->start_date->format('d/m/Y H:i') }}
                </p>
            @endif

            <div class="bg-white/10 rounded-xl p-3 text-sm">
                @if ($event->is_permanent)
                    <p>VENDA PERMANENTE</p>
                @else
                    <p>
                        Pré-lance online: <br>
                        <span class="font-semibold text-yellow-200">
                            {{ \Carbon\Carbon::parse($event->pre_start_date)->format('d/m/Y H:i') }}
                        </span>
                        até
                        <span class="font-semibold text-yellow-200">
                            {{ \Carbon\Carbon::parse($event->pre_finish_date)->format('d/m/Y H:i') }}
                        </span>
                    </p>
                @endif
            </div>

            <!-- Badges -->
            <!-- Badges -->
            <div
                class="mt-3 flex flex-wrap justify-center md:justify-start gap-2
            md:max-h-[4.5rem] md:overflow-hidden">
                @if ($event->regulation_image_path)
                    <span id="openRegulation"
                        class="flex-shrink-0 cursor-pointer inline-flex items-center gap-1 bg-yellow-200 text-[#003333] text-xs sm:text-sm font-semibold px-3 py-1.5 rounded-full shadow-sm hover:bg-yellow-300 transition-all whitespace-nowrap">
                        Condições de Pgto
                    </span>
                @endif

                @if ($event->benefits_image_path)
                    <span id="openBenefits"
                        class="flex-shrink-0 cursor-pointer inline-flex items-center gap-1 bg-emerald-200 text-[#003333] text-xs sm:text-sm font-semibold px-3 py-1.5 rounded-full shadow-sm hover:bg-emerald-300 transition-all whitespace-nowrap">
                        Benefícios do Pré-lance
                    </span>
                @endif

                @if ($event->regulation)
                    <a href="{{ asset('storage/' . $event->regulation) }}" target="_blank"
                        class="flex-shrink-0 inline-flex items-center gap-1 bg-blue-200 text-[#003333] text-xs sm:text-sm font-semibold px-3 py-1.5 rounded-full shadow-sm hover:bg-blue-300 transition-all whitespace-nowrap">
                        Regulamento Completo
                    </a>
                @endif
            </div>

        </div>

        <!-- Observações -->
        <div class="bg-white/10 rounded-xl p-5 text-sm md:text-base leading-relaxed shadow-md mt-6 lg:mt-0"
            style="text-align: justify;">
            <p>{{ $event->note }}</p>
        </div>


    </div>

    <!-- Modais permanecem iguais -->
    <div id="modalRegulation"
        class="hidden fixed inset-0 bg-black/70 flex items-center justify-center z-50 p-6 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden max-w-3xl w-full animate-fadeIn">
            <div class="flex justify-between items-center p-4 bg-[#003333] text-white">
                <h3 class="text-lg font-bold">📄 Regulamento do Evento</h3>
                <button class="text-white text-2xl leading-none" id="closeRegulation">&times;</button>
            </div>
            <div class="p-4 bg-gray-100 flex justify-center">
                <img src="{{ asset('storage/' . $event->regulation_image_path) }}" alt="Imagem do Regulamento"
                    class="rounded-xl max-h-[80vh] object-contain shadow-md">
            </div>
        </div>
    </div>

    <div id="modalBenefits"
        class="hidden fixed inset-0 bg-black/70 flex items-center justify-center z-50 p-6 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden max-w-3xl w-full animate-fadeIn">
            <div class="flex justify-between items-center p-4 bg-[#003333] text-white">
                <h3 class="text-lg font-bold">🎁 Benefícios do Evento</h3>
                <button class="text-white text-2xl leading-none" id="closeBenefits">&times;</button>
            </div>
            <div class="p-4 bg-gray-100 flex justify-center">
                <img src="{{ asset('storage/' . $event->benefits_image_path) }}" alt="Imagem de Benefícios"
                    class="rounded-xl max-h-[80vh] object-contain shadow-md">
            </div>
        </div>
    </div>
</section>


<!-- JS -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modalRegulation = document.getElementById('modalRegulation');
        const modalBenefits = document.getElementById('modalBenefits');

        // Botões abrir
        document.getElementById('openRegulation').addEventListener('click', () => {
            modalRegulation.classList.remove('hidden');
        });

        document.getElementById('openBenefits').addEventListener('click', () => {
            modalBenefits.classList.remove('hidden');
        });

        // Botões fechar
        document.getElementById('closeRegulation').addEventListener('click', () => {
            modalRegulation.classList.add('hidden');
        });

        document.getElementById('closeBenefits').addEventListener('click', () => {
            modalBenefits.classList.add('hidden');
        });

        // Fechar clicando fora
        [modalRegulation, modalBenefits].forEach(modal => {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) modal.classList.add('hidden');
            });
        });
    });
</script>

<!-- Animação opcional -->
<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
    }
</style>
