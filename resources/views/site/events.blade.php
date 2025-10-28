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
                        @if ($event->is_permanent)
                            VENDA PERMANENTE
                        @else
                            Data / Horário: {{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y - H:i') }}hs
                        @endif
                    </span>
                </div>

            </div>

            <!-- Conteúdo principal -->
            <div class="bg-[#4D6766] p-4 grid grid-cols-1 md:grid-cols-5 gap-4 items-center text-white">
                <!-- Imagem do evento -->
                {{-- <div class="aspect-[4/3] w-44 rounded-lg overflow-hidden bg-black border border-gray-800">
                    <a href="{{ route('events.show', $event) }}">
                        <img src="{{ asset('storage/' . $event->banner_min) }}" alt="Logo do lote"
                            class="object-cover w-full h-full object-center">
                    </a>
                </div> --}}

                <div
                    class="w-72 aspect-[1000/720] rounded-2xl shadow-xl border-4 border-white/20 bg-black overflow-hidden">
                    <a href="{{ route('events.show', $event) }}">
                        <img src="{{ asset('storage/' . $event->banner_min) }}" alt="Logo do lote"
                            class="object-cover w-full h-full object-center">
                    </a>
                </div>



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
