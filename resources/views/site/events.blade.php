<section id="proximos-remates" class="py-20 text-white" style="background:#003333;">
    <div class="max-w-6xl mx-auto">
        <img src="{{ asset('img/proximos.png') }}" alt="Próximos Remates" class="my-6">
    </div>

    @foreach ($events as $event)
        <div class="max-w-6xl mx-auto my-6 bg-gray-800 rounded-lg overflow-hidden shadow-lg">

            <!-- Cabeçalho do evento -->
            <div
                class="relative rounded-2xl overflow-hidden bg-[url('{{ asset('img/wood_texture.png') }}')] bg-cover bg-center p-2 flex justify-between items-center text-white max-h-20">
                <div class="absolute inset-0 bg-black/30"></div>
                <div class="relative flex justify-between items-center w-full">
                    <h1 class="text-lg md:text-xl font-bold drop-shadow-lg truncate">{{ $event->name }}</h1>
                    <span class="text-sm md:text-lg font-bold drop-shadow-lg truncate">
                        Data / Horário: {{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y - H:i') }}hs
                    </span>
                </div>
            </div>

            <!-- Conteúdo principal -->
            <div class="bg-[#4D6766] flex flex-col md:flex-row items-start gap-4 p-4 md:p-6 text-white">

                <!-- Banner do evento -->
                <a href="{{ route('events.show', $event) }}" class="flex-shrink-0">
                    <img src="{{ asset('storage/' . $event->banner_min) }}" alt="Banner do evento"
                        class="w-60 h-60 object-contain rounded-xl shadow-md hover:scale-105 transition-transform duration-300">
                </a>

                <!-- Descrição -->
                <div class="flex-1 text-justify flex items-center">
                    <p class="text-sm md:text-base leading-relaxed">
                        {{ $event->note }}
                    </p>
                </div>

                <!-- Pré-lance e Regulamento lado a lado -->
                <div class="flex flex-col md:flex-row gap-4 flex-shrink-0">
                    <!-- Pré-lance -->
                    <a href="{{ route('events.show', $event) }}">
                        <img src="{{ asset('img/prelance.png') }}" alt="Pré-lance"
                            class="w-60 h-60 object-contain rounded-xl shadow-md hover:scale-105 transition-transform duration-300">
                    </a>

                    <!-- Regulamento -->
                    @if ($event->regulation)
                        <a href="{{ asset('storage/' . $event->regulation) }}" target="_blank">
                            <img src="{{ asset('img/regulamento.png') }}" alt="Regulamento"
                                class="w-60 h-60 object-contain rounded-xl shadow-md hover:scale-105 transition-transform duration-300">
                        </a>
                    @endif
                </div>

            </div>
        </div>
    @endforeach
</section>
