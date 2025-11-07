@extends('site.master')

@section('title', $animal->pivot->name . ' - Detalhes do Lote')

@section('content')
    <div class="hidden md:block">
        @include('site.banners')
    </div>
    <!-- Breadcrumbs -->
    <section class="py-4 px-4 bg-[#003333] text-white">
        <div class="container mx-auto text-sm">
            <nav class="flex items-center space-x-2" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="text-green-300 hover:underline">Início</a>
                <span class="text-white/50">/</span>
                <a href="{{ route('events.show', $event->id) }}"
                    class="text-green-300 hover:underline">{{ $event->name }}</a>
                <span class="text-white/50">/</span>
                <span class="text-white">{{ $animal->pivot->name }}</span>
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
                            {{-- <a href="{{ $animal->pivot->video_link ?? '#' }}" target="_blank" class="w-full">
                                <img src="{{ asset('storage/' . $animal->pivot->photo_full) }}"
                                    alt="{{ $animal->pivot->name }}"
                                    class="w-full rounded-lg shadow-lg object-cover hover:opacity-90 transition">
                            </a> --}}
                            @php
                                $videoUrl = $animal->pivot->video_link ?? '#';

                                // Converte qualquer link para o formato watch?v=
                                if (str_contains($videoUrl, 'youtu.be/')) {
                                    $videoId = last(explode('/', $videoUrl));
                                    $videoUrl = 'https://www.youtube.com/watch?v=' . $videoId;
                                } elseif (str_contains($videoUrl, 'youtube.com/embed/')) {
                                    $videoUrl = str_replace('embed/', 'watch?v=', $videoUrl);
                                }
                            @endphp

                            <a href="{{ $videoUrl }}" target="_blank" class="block relative w-full">
                                <img src="{{ asset('storage/' . $animal->pivot->photo_full) }}"
                                    alt="{{ $animal->pivot->name }}"
                                    class="w-full rounded-lg shadow-lg object-cover hover:opacity-90 transition">

                                @php
                                    $status = $animal->pivot->status ?? null;
                                    $statusColors = [
                                        'disponivel' => 'bg-green-700/90',
                                        'reservado' => 'bg-yellow-600/90',
                                        'vendido' => 'bg-red-700/90',
                                    ];
                                @endphp

                                <!-- CARIMBO MAIOR CENTRALIZADO -->
                                <span
                                    class="absolute left-4 bottom-4 flex flex-col items-center justify-center text-center
               px-8 py-4 text-2xl font-extrabold uppercase tracking-widest rounded-lg shadow-2xl ring-4 ring-white/70 text-white
               transform -rotate-6 origin-left select-none
               {{ $statusColors[$status] ?? 'bg-gray-700/90' }}">
                                    <span class="text-sm font-semibold opacity-80 leading-none">Lote</span>
                                    <span class="leading-tight">{{ $status }}</span>
                                </span>
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
                    <h1 class="text-3xl font-bold">{{ $animal->pivot->name }}</h1>
                    <p><b>N° Lote:</b> {{ $animal->pivot->lot_number ?? '-' }}</p>
                    <p><b>Gênero:</b>
                        {{ $animal->gender === 'male' ? 'MACHO' : ($animal->gender === 'female' ? 'FÊMEA' : '-') }}</p>
                    <p><b>Situação:</b> {{ $animal->pivot->situation ?? '-' }}</p>
                    <p><b>RP:</b> {{ $animal->rb ?? '-' }}</p>
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

                    <div class="mt-3 text-white">
                        <h3 class="text-lg font-semibold">Comentários</h3>
                        <div>
                            {!! str_replace('<p>', '<p style="text-align: justify;">', $animal->pivot->note) !!}
                        </div>
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
                                @if ($animal->pivot->target_value && $animal->pivot->target_value != '0.00')
                                    <p class="text-xl font-bold text-green-300">
                                        R$ {{ number_format($animal->pivot->target_value, 2, ',', '.') }}
                                    </p>
                                @else
                                    <p class="text-xl font-bold text-green-300">Consulte o regulamento</p>
                                @endif
                            </div>
                        </div>

                        @if ($animal->pivot->status === 'disponivel')
                            @auth
                                @php
                                    $client = Auth::user()->client;
                                    // dd($client);
                                @endphp

                                @if ($client && $client->situation === 'able')
                                    {{-- ✅ FORMULÁRIO DE LANCE --}}
                                    <form action="{{ route('bids.store') }}" method="POST" class="space-y-4" id="bidForm">
                                        @csrf
                                        <input type="hidden" name="event_id" value="{{ $event->id }}">
                                        <input type="hidden" name="animal_event_id" value="{{ $animal->pivot->id }}">

                                        <div class="relative">
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

                                    @include('site.animals.bid-modal')
                                @else
                                    {{-- ⚠️ CLIENTE NÃO HABILITADO --}}
                                    {{-- <div
                                        class="bg-yellow-200 text-yellow-900 p-4 rounded-lg border border-yellow-300 shadow-sm">
                                        <p class="font-semibold">⚠️ Sua conta precisa estar habilitada para dar lances.</p>
                                    </div> --}}
                                    <div
                                        class="bg-yellow-200 text-yellow-900 p-4 rounded-lg border border-yellow-300 shadow-sm">
                                        <p class="font-semibold">⚠️ Para dar lance, você precisa estar logado.</p>
                                        <p class="text-sm mt-2"> Ainda não possui cadastro? <a
                                                href="{{ route('filament.admin.auth.register') }}"
                                                class="inline-block mt-2 px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-black font-semibold rounded-lg shadow transition">
                                                Criar cadastro </a> </p>
                                    </div>
                                @endif
                            @endauth

                            @guest
                                {{-- ⚠️ NÃO LOGADO --}}
                                <div class="bg-yellow-200 text-yellow-900 p-4 rounded-lg border border-yellow-300 shadow-sm">
                                    <p class="font-semibold">⚠️ Você deve estar logado para dar lances.</p>
                                    <a href="{{ route('login') }}"
                                        class="inline-block mt-2 px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-black font-semibold rounded-lg shadow transition">
                                        Clique aqui para logar
                                    </a>
                                </div>
                            @endguest
                        @else
                            {{-- ⚠️ LOTE INDISPONÍVEL --}}
                            <div class="bg-gray-700 text-gray-300 p-4 rounded-lg text-center">
                                <p class="font-semibold text-lg">⚠️ Este lote não está disponível para lances.</p>
                            </div>
                        @endif

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
                    <a href="{{ route('animals.show', [$event->id, $previousAnimal->pivot->id]) }}"
                        class="inline-block bg-green-800 text-white px-4 py-2 rounded-lg shadow hover:bg-green-700 transition">
                        &laquo; Lote Anterior
                    </a>
                @else
                    <span></span>
                @endif

                @if ($nextAnimal)
                    <a href="{{ route('animals.show', [$event->id, $nextAnimal->pivot->id]) }}"
                        class="inline-block bg-green-800 text-white px-4 py-2 rounded-lg shadow hover:bg-green-700 transition">
                        Próximo Lote &raquo;
                    </a>
                @endif
            </div>
        </div>

        <!-- Modal de Confirmação de Lance Recebido -->
        @if (session('bid_success'))
            <div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-2xl shadow-2xl border border-green-700 max-w-md w-full overflow-hidden">
                    <div class="bg-green-800 text-white px-6 py-4">
                        <h3 class="text-xl font-bold">✅ Lance Recebido</h3>
                    </div>
                    <div class="px-6 py-6 text-green-900 text-center leading-relaxed">
                        <p class="text-lg font-semibold mb-4">Lance recebido no lote!</p>
                        <p class="text-sm mb-4">
                            Após a liberação por parte da mesa operadora, o lote se atualizará automaticamente no site.
                        </p>
                        <p class="text-sm mb-6">
                            Se seu lance for coberto, nossa equipe lhe informará.
                        </p>
                        <p class="text-sm font-semibold text-green-700">Muito Obrigado!!</p>

                        <button id="closeSuccessBtn"
                            class="mt-6 px-6 py-2 bg-green-700 text-white rounded-lg hover:bg-green-600 transition">
                            Fechar
                        </button>
                    </div>
                </div>
            </div>
        @endif


    </section>

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

    <script>
        // Fecha a modal de sucesso (Lance Recebido)
        const closeBtn = document.getElementById('closeSuccessBtn');
        const successModal = document.getElementById('successModal');

        if (closeBtn && successModal) {
            closeBtn.addEventListener('click', () => {
                successModal.classList.add('hidden');
            });
        }
    </script>

@endsection
