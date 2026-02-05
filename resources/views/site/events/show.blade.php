@extends('site.master')

@section('title', $event->name . ' - Detalhes')

@section('content')
    <div class="hidden md:block">
        @include('site.banners')
    </div>

    @include('site.events.header')

    <!-- Breadcrumbs para página do evento -->
    <section class="py-4 px-4 bg-[#003333] text-white">
        <div class="container mx-auto text-sm">
            <nav class="flex items-center space-x-2" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="text-green-300 hover:underline">Todos os Eventos</a>
                <span class="text-white/50">/</span>
                <span class="text-white">{{ $event->name }}</span>
            </nav>
        </div>
    </section>


    <!-- Galeria de animais -->
    <section x-data="{
        search: '',
        filterCards() {
            const query = this.search.toLowerCase().trim();
            const cards = $refs.cards.querySelectorAll('[data-animal]');
            let visibleCount = 0;
    
            cards.forEach(card => {
                const name = card.dataset.name;
                const isVisible = query === '' || name.includes(query);
                card.classList.toggle('hidden', !isVisible);
                if (isVisible) visibleCount++;
            });
    
            this.filteredCount = visibleCount;
            this.noResults = visibleCount === 0;
        },
        filteredCount: {{ $event->animals->count() }},
        noResults: false,
    }" x-init="filterCards" class="py-16 px-6 bg-gradient-to-b from-[#003333] to-[#001a1a]">
        <div class="container mx-auto">

            <h2 class="text-3xl font-bold mb-6 text-center text-white tracking-wide">
                Lotes do Evento
            </h2>

            <!-- Campo de busca -->
            <div class="flex justify-center mb-8">
                <input type="text" x-model="search" @input="filterCards" placeholder="🔍 Pesquisar por nome..."
                    class="w-full max-w-md px-4 py-2 rounded-lg text-gray-800 focus:ring-2 focus:ring-green-500 focus:outline-none" />
            </div>

            <!-- Contador dinâmico -->
            <p class="text-center text-gray-300 mb-10">
                <span x-text="filteredCount"></span> lote(s) encontrado(s)
            </p>

            <!-- Mensagem de nenhum resultado -->
            <template x-if="noResults">
                <p class="text-red-400 text-sm mt-2 font-semibold text-center">
                    Nenhum lote encontrado com esse nome.
                </p>
            </template>
        </div>

        @if ($event->show_lots)
            @if ($event->animals->count() > 0)
                <div x-ref="cards" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                    @foreach ($event->animals as $animal)
                        @if ($animal->pivot->visible)
                            <div data-animal data-name="{{ strtolower($animal->pivot->name) }}"
                                class="bg-[#4D6766] rounded-2xl overflow-hidden shadow-lg transform transition duration-300 hover:-translate-y-2 hover:shadow-2xl">
                                <a href="{{ route('animals.show', [$event->id, $animal->pivot->id]) }}"
                                    class="block relative">

                                    <div class="w-full bg-[#4D6766] flex items-center justify-center">
                                        <img src="{{ asset('storage/' . $animal->pivot->photo) }}"
                                            alt="{{ $animal->pivot->name }}"
                                            class="w-full h-auto object-contain transition duration-300 hover:scale-105 rounded-t-2xl shadow">
                                    </div>

                                    @php
                                        $status = $animal->pivot->status ?? null;
                                        $statusColors = [
                                            'disponivel' => 'bg-green-700/90',
                                            'reservado' => 'bg-yellow-600/90',
                                            'vendido' => 'bg-red-700/90',
                                        ];
                                    @endphp

                                    @if ($status === 'vendido')
                                        <img src="{{ asset('img/carimbo_logo_10_anos.png') }}" alt="Lote Vendido"
                                            class="absolute left-4 bottom-3
                                            w-32 md:w-36
                                            transform -rotate-6 translate-y-1
                                            drop-shadow-2xl
                                            select-none pointer-events-none">

                                        {{-- 🟢 LOGO OPCIONAL --}}
                                        <img src="{{ asset('img/logo_header_10_anos.png') }}" alt="Logo"
                                            class="absolute right-3 bottom-3
                                        h-10 md:h-12
                                        opacity-70 hover:opacity-90 transition-opacity duration-300
                                        select-none pointer-events-none">
                                    @else
                                        {{-- 🟢 Badge pequeno à esquerda --}}
                                        <span
                                            class="absolute left-3 bottom-3 px-3 py-1 text-xs font-bold text-white uppercase
                                            rounded-lg shadow
                                            {{ $statusColors[$status] ?? 'bg-gray-600/90' }}">
                                            {{ $status === 'disponivel' ? 'DISPONÍVEL' : $status }}
                                        </span>
                                    @endif



                                </a>


                                <div class="p-5">
                                    <h3 class="font-bold text-xl text-white mb-2">
                                        Lote: {{ $animal->pivot->lot_number }}
                                    </h3>

                                    <h3 class="font-bold text-xl text-white mb-2 min-h-[3rem]">
                                        {{ $animal->pivot->name }}
                                    </h3>

                                    @if ($event->closed)
                                        <div
                                            class="text-center text-gray-300 font-bold text-md mb-4 min-h-[60px] flex items-center justify-center">
                                            PRÉ-LANCE ENCERRADO
                                        </div>
                                        @if (floatval($animal->current_bid) > 0 && floatval($animal->pivot->target_value) > 0)
                                            <div
                                                class="grid grid-cols-[140px_1fr] items-center gap-2 text-gray-200 font-extrabold text-md mb-4 min-h-[60px]">
                                                <span>Lance Atual:</span>
                                                <span
                                                    class="inline-block bg-green-600 text-white px-3 py-1 rounded-lg shadow text-right min-w-[110px]">
                                                    R$ {{ number_format(floatval($animal->current_bid), 2, ',', '.') }}
                                                </span>
                                            </div>
                                        @endif
                                    @else
                                        <div
                                            class="grid grid-cols-[140px_1fr] items-center gap-2 text-gray-200 font-extrabold text-md mb-4 min-h-[60px]">
                                            
                                            @if (floatval($animal->current_bid) > 0 )
                                                <span>Lance Atual:</span>
                                                <span
                                                    class="inline-block bg-green-600 text-white px-3 py-1 rounded-lg shadow text-right min-w-[110px]">
                                                    R$ {{ number_format(floatval($animal->current_bid), 2, ',', '.') }}
                                                </span>
                                            @else
                                                <span>&nbsp;</span>
                                                <span
                                                    class="inline-block px-3 py-1 rounded-lg text-transparent min-w-[110px] select-none">
                                                    R$ 0,00
                                                </span>
                                            @endif

                                            @if (floatval($animal->pivot->target_value) > 0)
                                                <span>Lance-alvo:</span>
                                                <span
                                                    class="inline-block bg-yellow-500 text-black px-3 py-1 rounded-lg shadow text-right min-w-[110px]">
                                                    R$ {{ number_format($animal->pivot->target_value, 2, ',', '.') }}
                                                </span>
                                            @else
                                                <span>&nbsp;</span>
                                                <span
                                                    class="inline-block px-3 py-1 rounded-lg text-transparent min-w-[110px] select-none">
                                                    R$ 0,00
                                                </span>
                                            @endif
                                            {{-- @endif --}}
                                        </div>
                                    @endif

                                    @if (!$event->closed)
                                        <a href="{{ route('animals.show', [$event->id, $animal->pivot->id]) }}"
                                            class="mt-4 inline-block w-full text-center bg-[#003333] text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:bg-[#005050] transition">
                                            Ver Detalhes
                                            @if ($status != 'vendido')
                                                / Dar Lance
                                            @endif
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @else
                <p class="text-center text-gray-300 text-lg mt-10">
                    Este evento ainda não possui lotes cadastrados.
                </p>
            @endif
        @else
            <p class="text-center text-gray-300 text-lg mt-10">
                Os lotes deste evento ainda não estão disponíveis para exibição.
            </p>
        @endif
    </section>

    <!-- Breadcrumbs para página do evento -->
    <section class="py-4 px-4 bg-[#003333] text-white">
        <div class="container mx-auto text-sm">
            <nav class="flex items-center space-x-2" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="text-green-300 hover:underline">Todos os Eventos</a>
                <span class="text-white/50">/</span>
                <span class="text-white">{{ $event->name }}</span>
            </nav>
        </div>
    </section>

@endsection
