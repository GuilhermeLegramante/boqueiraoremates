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
                    @if ($animal->photo_full)
                        <div class="relative w-full flex flex-col items-center">
                            <!-- Imagem do animal com link -->
                            <a href="{{ $animal->video_link ?? '#' }}" target="_blank" class="w-full">
                                <img src="{{ asset('storage/' . $animal->photo_full) }}" alt="{{ $animal->name }}"
                                    class="w-full rounded-lg shadow-lg object-cover hover:opacity-90 transition">
                            </a>

                            <!-- Plaquinha pendurada -->
                            <a href="{{ $animal->video_link ?? '#' }}" target="_blank"
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
                    <p><b>Nº / Lote:</b> {{ $animal->lot_number ?? '-' }}</p>
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
                        <p class="text-white text-justify">{{ $animal->note }}</p>
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
                            <form action="{{ route('bids.store') }}" method="POST" class="space-y-4" id="bidForm">
                                @csrf

                                <input type="hidden" name="event_id" value="{{ $event->id }}">
                                <input type="hidden" name="animal_event_id" value="{{ $animal->pivot->id }}">

                                <div class="relative">
                                    <!-- Input com R$ -->
                                    <span
                                        class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-700 z-10">R$</span>
                                    <input type="text" name="amount" id="bidInput"
                                        class="w-full pl-10 px-4 py-2 rounded-lg text-black border" placeholder="0,00" required>
                                </div>
                                <!-- Erro abaixo, fora do relative -->
                                <p id="bidError" class="text-red-600 text-sm mt-1 hidden">
                                    O valor do lance não pode ser menor que o lance mínimo (R$
                                    {{ number_format($animal->next_bid, 2, ',', '.') }}).
                                </p>



                                <button type="submit"
                                    class="w-full bg-green-600 text-white font-bold px-6 py-3 rounded-lg shadow hover:bg-green-500 transition">
                                    Confirmar Lance
                                </button>
                            </form>

                            <!-- Modal -->
                            <div id="confirmModal"
                                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
                                <div class="w-full max-w-md rounded-2xl shadow-2xl border border-green-700 overflow-hidden">
                                    <!-- Cabeçalho -->
                                    <div class="bg-green-800 text-white px-6 py-4">
                                        <h3 class="text-xl font-bold">Confirmação de Lance</h3>
                                    </div>

                                    <!-- Corpo -->
                                    <div class="bg-white px-6 py-6 text-green-900">
                                        <div class="space-y-2 mb-4">
                                            <p>Usuário: <span class="font-semibold">{{ Auth::user()->name }}</span></p>
                                            <p>Evento: <span class="font-semibold">{{ $event->name }}</span></p>
                                            <p>Animal: <span class="font-semibold">{{ $animal->name }}</span></p>
                                            <p>Valor do Lance:
                                                <span class="font-semibold" id="modalBidValue">
                                                    R$ {{ number_format($animal->next_bid, 2, ',', '.') }}
                                                </span>
                                            </p>
                                        </div>

                                        <p class="text-sm mb-6">
                                            Ao confirmar, o lance será registrado e passará por validação pela mesa, conforme
                                            regulamento do evento.
                                        </p>

                                        <div class="flex justify-end gap-4">
                                            <button id="cancelBtn"
                                                class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition">Cancelar</button>
                                            <button id="confirmBtn"
                                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-500 transition">Confirmar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <script>
                                const bidInput = document.getElementById('bidInput');
                                const form = document.getElementById('bidForm');
                                const modal = document.getElementById('confirmModal');
                                const cancelBtn = document.getElementById('cancelBtn');
                                const confirmBtn = document.getElementById('confirmBtn');
                                const bidError = document.getElementById('bidError');

                                // Valor mínimo vindo do backend
                                const minBid = {{ $animal->next_bid }};

                                // Função para formatar valor em reais
                                function formatBRL(value) {
                                    return value.toLocaleString('pt-BR', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    });
                                }

                                // Converte string pt-BR "1.234,56" para float 1234.56
                                function parseBRL(value) {
                                    return parseFloat(value.replace(/\./g, '').replace(',', '.'));
                                }

                                // Formatar enquanto digita
                                bidInput.addEventListener('input', () => {
                                    let value = bidInput.value.replace(/\D/g, '');

                                    if (!value) {
                                        bidInput.value = '';
                                        bidInput.classList.remove('border-red-600');
                                        bidError.classList.add('hidden');
                                        return;
                                    }

                                    value = (parseInt(value) / 100).toFixed(2);
                                    bidInput.value = formatBRL(parseFloat(value));

                                    // Verifica se é menor que o mínimo
                                    if (parseFloat(value) < minBid) {
                                        bidInput.classList.add('border-red-600');
                                        bidError.classList.remove('hidden');
                                    } else {
                                        bidInput.classList.remove('border-red-600');
                                        bidError.classList.add('hidden');
                                    }
                                });

                                // Intercepta submit e valida antes de abrir modal
                                form.addEventListener('submit', (e) => {
                                    e.preventDefault();

                                    const numericValue = parseBRL(bidInput.value);

                                    if (!numericValue || numericValue < minBid) {
                                        bidInput.classList.add('border-red-600');
                                        bidError.classList.remove('hidden');
                                        return; // Não abre a modal
                                    }

                                    // Atualiza valor na modal
                                    document.getElementById('modalBidValue').innerText = 'R$ ' + bidInput.value;

                                    modal.classList.remove('hidden');
                                });

                                // Confirmar envio
                                confirmBtn.addEventListener('click', () => {
                                    bidInput.value = parseBRL(bidInput.value); // Converte para float padrão antes de enviar
                                    form.submit();
                                });

                                // Cancelar modal
                                cancelBtn.addEventListener('click', () => {
                                    modal.classList.add('hidden');
                                });
                            </script>
                        @else
                            <p class="text-green-200">Você deve estar logado para dar lances.</p>
                            <a href="{{ route('login') }}" class="text-green-300 underline">Clique aqui para logar</a>
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
