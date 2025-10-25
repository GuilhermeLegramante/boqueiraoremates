@extends('site.master')

@section('title', $event->name . ' - Detalhes')

@section('content')
    @include('site.banners')


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
    <section class="py-16 px-6 bg-gradient-to-b from-[#003333] to-[#001a1a]">
        <div class="container mx-auto">
            <h2 class="text-3xl font-bold mb-10 text-center text-white tracking-wide">
                Lotes do Evento
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                @foreach ($event->animals as $animal)
                    <div
                        class="bg-[#4D6766] rounded-2xl overflow-hidden shadow-lg transform transition duration-300 hover:-translate-y-2 hover:shadow-2xl">
                        <a href="{{ route('animals.show', [$event->id, $animal->id]) }}" class="block relative">
                            <div class="w-full bg-[#4D6766] flex items-center justify-center">
                                <img src="{{ asset('storage/' . $animal->pivot->photo) }}" alt="{{ $animal->pivot->name }}"
                                    class="w-full h-auto object-contain transition duration-300 hover:scale-105 rounded-t-2xl shadow">
                            </div>

                            @php
                                $status = $animal->pivot->status ?? null;

                                $statusColors = [
                                    'disponivel' => 'bg-green-600',
                                    'reservado' => 'bg-yellow-500',
                                    'vendido' => 'bg-red-600',
                                ];
                            @endphp

                            <span
                                class="absolute bottom-2 right-2 px-3 py-1 text-xs font-bold text-white uppercase rounded-lg shadow {{ $statusColors[$status] ?? 'bg-gray-600' }}">
                                {{ $status }}
                            </span>
                        </a>
                        <div class="p-5">
                            <h3 class="font-bold text-xl text-white mb-2">{{ $animal->pivot->name }}</h3>
                            <p class="text-gray-200 text-sm mb-3">
                                Lote: <span class="text-lg font-semibold">{{ $animal->pivot->lot_number }}</span>
                            </p>
                            <p class="text-white font-bold text-lg">
                                R$ {{ number_format($animal->pivot->min_value, 2, ',', '.') }}
                            </p>
                            <a href="{{ route('animals.show', [$event->id, $animal->id]) }}"
                                class="mt-4 inline-block w-full text-center bg-[#003333] text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:bg-[#005050] transition">
                                Ver Detalhes
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
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

@endsection
