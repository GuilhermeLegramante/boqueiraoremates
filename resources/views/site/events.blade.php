<section id="proximos-remates" class="py-20 text-white" style="background:#003333;">
    <div class="max-w-6xl mx-auto">
        <img src="{{ asset('img/proximos.png') }}" alt="Próximos Remates" class="my-6">
    </div>
    @foreach ($events as $event)
        <div class="max-w-6xl mx-auto my-6 bg-gray-800 rounded-lg overflow-hidden shadow-lg">
            <div
                class="relative rounded-2xl overflow-hidden bg-[url('{{ asset('img/wood_texture.png') }}')] bg-cover bg-center p-1 md:p-2 flex flex-col md:flex-row justify-between items-center text-white max-h-20">

                <!-- Sobreposição para aumentar contraste -->
                <div class="absolute inset-0 bg-black/30"></div>

                <!-- Conteúdo da div -->
                <div class="relative flex flex-col md:flex-row justify-between items-center w-full">
                    <h1 class="text-lg md:text-xl font-bold drop-shadow-lg truncate">
                        {{ $event->name }}
                    </h1>
                    <span class="text-sm md:text-lg font-bold mt-1 md:mt-0 drop-shadow-lg truncate">
                        Data / Horário: {{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y - H:i') }}hs
                    </span>
                </div>

            </div>

            <!-- Conteúdo principal -->
            <div class="bg-[#4D6766] p-4 grid grid-cols-1 md:grid-cols-5 gap-4 items-center text-white">
                <!-- Imagem do evento -->
                <a href="{{ route('events.show', $event) }}">
                    <img src="{{ asset('storage/' . $event->banner_min) }}" alt="Logo do lote"
                        class="w-44 h-36 object-cover rounded">
                </a>

                <!-- Descrição -->
                <div class="md:col-span-3 text-justify">
                    <p class="text-sm md:text-base leading-relaxed">
                        {{ $event->note }}
                    </p>
                </div>

                <!-- Pré-lance -->
                <a href="{{ route('events.show', $event) }}">
                    <img src="{{ asset('img/prelance.png') }}" alt="Pré-lance" class="w-36 h-36 object-cover rounded">
                </a>
            </div>
        </div>
    @endforeach

</section>
