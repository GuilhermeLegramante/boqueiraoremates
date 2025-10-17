<section class="bg-[#003333] text-white py-12 px-6">
    <div class="container mx-auto grid grid-cols-1 lg:grid-cols-3 gap-10 items-center">

        <!-- Banner -->
        <div class="flex justify-center lg:justify-start">
            <img src="{{ asset('storage/' . $event->banner_min) }}" alt="Banner do evento"
                class="w-72 h-56 object-cover rounded-2xl shadow-xl border-4 border-white/20">
        </div>

        <!-- Informações principais -->
        <div class="space-y-3 text-center lg:text-left">
            <h2 class="text-3xl font-extrabold tracking-wide">{{ $event->name }}</h2>
            <p class="text-lg font-medium text-yellow-300">
                {{ $event->start_date->format('d/m/Y H:i') }}
            </p>
            <div class="bg-white/10 rounded-xl p-3 text-sm">
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
            </div>
        </div>

        <!-- Observações -->
        <div class="bg-white/10 rounded-xl p-5 text-sm md:text-base leading-relaxed shadow-md">
            <p>{{ $event->note }}</p>
        </div>
    </div>

    <!-- Botões (só aparecem se houver imagem) -->
    <div class="mt-8 flex flex-wrap justify-center gap-6 lg:col-span-3">
        @if ($event->regulation_image_path)
            <button @click="showRegulation = true"
                class="flex items-center gap-2 bg-yellow-400 text-[#003333] font-semibold px-5 py-3 rounded-xl shadow-md hover:bg-yellow-300 transition-all">
                📄 Ver Regulamento
            </button>
        @endif

        @if ($event->benefits_image_path)
            <button @click="showBenefits = true"
                class="flex items-center gap-2 bg-emerald-500 text-white font-semibold px-5 py-3 rounded-xl shadow-md hover:bg-emerald-400 transition-all">
                🎁 Ver Benefícios
            </button>
        @endif
    </div>

    <!-- Modal Regulamento -->
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

    <!-- Modal Benefícios -->
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
