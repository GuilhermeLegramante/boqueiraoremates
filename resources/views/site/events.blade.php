<section id="proximos-remates" class="py-20 text-white" style="background:#003333;">
    <div class="max-w-6xl mx-auto">
        <img src="{{ asset('img/proximos.png') }}" alt="Próximos Remates" class="my-6">
    </div>
    @foreach ($events as $event)
        <div class="max-w-6xl mx-auto my-6 bg-gray-800 rounded-lg overflow-hidden shadow-lg">
            <div
                class="relative rounded-2xl overflow-hidden bg-[url('{{ asset('img/wood_texture.png') }}')] bg-cover bg-center p-2 md:p-3 flex flex-col md:flex-row justify-between items-center text-white">

                <!-- Sobreposição para contraste -->
                <div class="absolute inset-0 bg-black/40"></div>

                <!-- Conteúdo -->
                <div @if ($event->is_permanent) id="vendas-permanentes" @endif
                    class="relative flex flex-col md:flex-row justify-between items-center w-full text-center md:text-left gap-1 md:gap-3">
                    <h1 class="text-lg sm:text-xl md:text-2xl font-bold drop-shadow-lg leading-tight">
                        {{ $event->name }}
                    </h1>
                    <span class="text-sm sm:text-base md:text-lg font-semibold drop-shadow-lg whitespace-nowrap">
                        @if ($event->is_permanent)
                            VENDA PERMANENTE
                        @else
                            {{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y - H:i') }}hs
                        @endif
                    </span>
                </div>
            </div>

            <!-- Conteúdo principal -->
            <div class="bg-[#4D6766] p-4 grid grid-cols-1 md:grid-cols-[auto_1fr_auto] gap-4 items-center text-white">
                <!-- Imagem do evento -->
                <div class="flex justify-center md:justify-start">
                    <div
                        class="w-48 sm:w-56 md:w-64 aspect-[1000/720] rounded-2xl shadow-xl border-4 border-white/20 bg-black overflow-hidden">
                        <a href="{{ route('events.show', $event) }}">
                            <img src="{{ asset('storage/' . $event->banner_min) }}" alt="Logo do lote"
                                class="object-contain w-full h-full object-center">
                        </a>
                    </div>
                </div>

                <!-- Descrição -->
                <div class="text-justify">
                    <p class="text-sm md:text-base leading-relaxed">
                        {{ $event->note }}
                    </p>
                </div>

                <!-- Pré-lance -->
                <div class="flex justify-center md:justify-end">
                    <a href="{{ route('events.show', $event) }}">
                        <img src="{{ asset('img/prelance.png') }}" alt="Pré-lance"
                            class="w-32 h-32 md:w-36 md:h-36 object-cover rounded">
                    </a>
                </div>
            </div>
        </div>
    @endforeach

</section>
