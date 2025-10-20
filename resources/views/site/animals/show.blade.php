@extends('site.master')

@section('title', $animal->name . ' - Detalhes do Lote')

@section('content')
    @include('site.banners')

    @include('site.events.header')

    <!-- Breadcrumbs -->
    <section class="py-4 px-4 bg-[#003333] text-white">
        <div class="container mx-auto text-sm">
            <nav class="flex items-center space-x-2" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="text-green-300 hover:underline">Início</a>
                <span class="text-white/50">/</span>
                <a href="{{ route('events.show', $event->id) }}"
                    class="text-green-300 hover:underline">{{ $event->name }}</a>
                <span class="text-white/50">/</span>
                <span class="text-white">{{ $animal->name }}</span>
            </nav>
        </div>
    </section>


    <!-- Detalhes do animal + lance -->
    <section class="py-12 px-4 bg-[#003333] text-white">
        <div class="container mx-auto">
            <!-- Título da seção -->
            <h2 class="text-3xl font-bold text-center mb-10 border-b-2 border-green-300 inline-block px-4">
                Ficha Técnica do Lote
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mt-8 items-start">
                <!-- Foto / vídeo (à esquerda) -->
                <div class="relative flex flex-col items-center md:order-1">
                    @if ($animal->pivot->photo_full)
                        <div class="relative w-full flex flex-col items-center">
                            <!-- Imagem do animal com link -->
                            <a href="{{ $animal->pivot->video_link ?? '#' }}" target="_blank" class="w-full">
                                <img src="{{ asset('storage/' . $animal->pivot->photo_full) }}" alt="{{ $animal->name }}"
                                    class="w-full rounded-lg shadow-lg object-cover hover:opacity-90 transition">
                            </a>

                            <!-- Plaquinha pendurada -->
                            <a href="{{ $animal->pivot->video_link ?? '#' }}" target="_blank"
                                class="mt-1 inline-block hover:opacity-80 transition">
                                <img src="{{ asset('img/linkvideo01.png') }}" alt="Ver vídeo do lote"
                                    class="w-32 h-auto shadow-md">
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Informações e lance (à direita) -->
                <div class="space-y-4 md:order-2">
                    <!-- Dados técnicos -->
                    <h1 class="text-3xl font-bold">{{ $animal->name }}</h1>
                    <p><b>Nº / Lote:</b> {{ $animal->pivot->lot_number ?? '-' }}</p>
                    <p><b>Gênero:</b>
                        {{ $animal->gender === 'male' ? 'MACHO' : ($animal->gender === 'female' ? 'FÊMEA' : '-') }}</p>
                    <p><b>RP:</b> {{ $animal->rp ?? '-' }}</p>
                    <p><b>Nascimento:</b> {{ \Carbon\Carbon::parse($animal->birth_date)->format('d/m/Y') }}</p>
                    <p><b>Pelagem:</b> {{ $animal->coat->name ?? '-' }}</p>
                    <p><b>Pai:</b> {{ $animal->father ?? '-' }}</p>
                    <p><b>Mãe:</b> {{ $animal->mother ?? '-' }}</p>

                    @if ($animal->generation_link)
                        <p>
                            <b>Quinta geração:</b>
                            <a href="{{ $animal->generation_link }}" target="_blank"
                                class="text-green-300 underline">Clique aqui</a>
                        </p>
                    @endif

                    <div class="mt-3">
                        <h3 class="text-lg font-semibold">Comentários</h3>
                        <p class="text-white text-justify">{{ $animal->pivot->note }}</p>
                    </div>

                    <!-- Card de lance -->
                    <div class="bg-[#002222] p-6 rounded-xl shadow-lg mt-6">
                        <h3 class="text-xl font-bold mb-4">Dar lance</h3>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                            <div class="bg-[#003333] shadow-lg rounded-lg p-4">
                                <p class="text-green-300">Lance atual</p>
                                <p class="text-xl font-bold text-white">
                                    R$ {{ number_format($animal->current_bid ?? 0, 2, ',', '.') }}
                                </p>
                            </div>
                            <div class="bg-[#003333] shadow-lg rounded-lg p-4">
                                <p class="text-green-300">Próximo lance mínimo</p>
                                <p class="text-xl font-bold text-white">
                                    R$ {{ number_format($animal->next_bid ?? 0, 2, ',', '.') }}
                                </p>
                            </div>
                            <div class="bg-[#003333] shadow-lg rounded-lg p-4">
                                <p class="text-green-300">Lance alvo</p>
                                @if ($animal->target_value)
                                    <p class="text-xl font-bold text-green-300">
                                        R$ {{ number_format($animal->target_value, 2, ',', '.') }}
                                    </p>
                                @else
                                    <p class="text-xl font-bold text-green-300">Consulte o regulamento</p>
                                @endif
                            </div>
                        </div>

                        @auth
                            @php
                                $client = Auth::user()->client;
                            @endphp

                            @if ($client && $client->situation === 'able')
                                <form action="{{ route('bids.store') }}" method="POST" class="space-y-4" id="bidForm">
                                    @csrf

                                    <input type="hidden" name="event_id" value="{{ $event->id }}">
                                    <input type="hidden" name="animal_event_id" value="{{ $animal->pivot->id }}">

                                    <div class="relative">
                                        <!-- Input com R$ -->
                                        <span
                                            class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-700 z-10">R$</span>
                                        <input type="text" name="amount" id="bidInput"
                                            class="w-full pl-10 px-4 py-2 rounded-lg text-black border" placeholder="0,00"
                                            required>
                                    </div>
                                    <p id="bidError" class="text-red-600 text-sm mt-1 hidden">
                                        O valor do lance não pode ser menor que o lance mínimo (R$
                                        {{ number_format($animal->next_bid, 2, ',', '.') }}).
                                    </p>

                                    <button type="submit"
                                        class="w-full bg-green-600 text-white font-bold px-6 py-3 rounded-lg shadow hover:bg-green-500 transition">
                                        Confirmar Lance
                                    </button>
                                </form>

                                {{-- Mantém o modal e o script abaixo normalmente --}}
                                @include('site.animals.bid-modal')
                            @else
                                <div class="bg-yellow-200 text-yellow-900 p-4 rounded-lg">
                                    <p><strong>Seu cadastro ainda não está habilitado para dar lances.</strong></p>
                                    <p class="text-sm mt-1">Por favor, entre em contato com a administração para habilitar seu
                                        cliente.</p>
                                </div>
                            @endif
                        @else
                            <p class="text-green-200">Você deve estar logado para dar lances.</p>
                            <a href="{{ route('filament.admin.auth.login') }}" class="text-green-300 underline">Clique aqui para logar</a>
                        @endauth

                    </div>
                </div>
            </div>

            <!-- Navegação entre lotes -->
            @php
                $currentAnimalIndex = $event->animals->search(fn($a) => $a->id === $animal->id);
                $previousAnimal = $event->animals->get($currentAnimalIndex - 1);
                $nextAnimal = $event->animals->get($currentAnimalIndex + 1);
            @endphp
            <div class="mt-10 flex justify-between max-w-md mx-auto text-sm">
                @if ($previousAnimal)
                    <a href="{{ route('animals.show', [$event->id, $previousAnimal->id]) }}"
                        class="inline-block bg-green-800 text-white px-4 py-2 rounded-lg shadow hover:bg-green-700 transition">
                        &laquo; Lote Anterior
                    </a>
                @else
                    <span></span>
                @endif

                @if ($nextAnimal)
                    <a href="{{ route('animals.show', [$event->id, $nextAnimal->id]) }}"
                        class="inline-block bg-green-800 text-white px-4 py-2 rounded-lg shadow hover:bg-green-700 transition">
                        Próximo Lote &raquo;
                    </a>
                @endif
            </div>
        </div>
    </section>


@endsection
