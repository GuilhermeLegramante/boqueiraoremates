@php
    use App\Models\Event;
    use App\Models\AnimalEvent;
    use App\Models\User;

    $events = Event::where('published', true)->pluck('name', 'id');
    $selectedEvent = session('selected_event_id');
    $lots = $selectedEvent ? AnimalEvent::where('event_id', $selectedEvent)->pluck('lot_number', 'id') : collect();

    $clients = User::whereHas('bids', function ($q) use ($selectedEvent) {
        if ($selectedEvent) {
            $q->where('event_id', $selectedEvent);
        }
    })->pluck('name', 'id');
@endphp

<div class="flex flex-col items-center justify-center gap-3 py-4">
    <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 text-center">
        Filtrar Lances Aprovados - Evento ATIVO
    </h2>

    <form method="POST" action="{{ route('filament.set-bid-filters') }}"
        class="flex flex-col sm:flex-row items-center justify-center gap-3 w-full">
        @csrf

        {{-- Evento --}}
        <select name="event_id"
            class="filament-forms-select w-full sm:w-64 text-sm rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200"
            x-data x-init="new TomSelect($el, { searchField: ['text'], placeholder: 'Selecione um evento...' })" onchange="this.form.submit()">
            <option value="">Selecione um evento...</option>
            @foreach ($events as $id => $name)
                <option value="{{ $id }}" @selected($selectedEvent == $id)>{{ $name }}</option>
            @endforeach
        </select>

        {{-- Lote --}}
        <select name="lot_id"
            class="filament-forms-select w-full sm:w-48 text-sm rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200"
            x-data x-init="new TomSelect($el, { searchField: ['text'], placeholder: 'Todos os lotes' })" onchange="this.form.submit()" @disabled(!$selectedEvent)>
            <option value="">Todos os lotes</option>
            @foreach ($lots as $id => $lot)
                <option value="{{ $id }}" @selected(session('selected_lot_id') == $id)>Lote {{ $lot }}</option>
            @endforeach
        </select>

        {{-- Cliente --}}
        <select name="client_id"
            class="filament-forms-select w-full sm:w-56 text-sm rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200"
            x-data x-init="new TomSelect($el, { searchField: ['text'], placeholder: 'Todos os clientes' })" onchange="this.form.submit()">
            <option value="">Todos os clientes</option>
            @foreach ($clients as $id => $name)
                <option value="{{ $id }}" @selected(session('selected_client_id') == $id)>{{ $name }}</option>
            @endforeach
        </select>

        {{-- Limpar filtros --}}
        <button type="submit" name="clear_filters" value="1"
            class="px-3 py-1.5 text-sm rounded-lg border border-gray-300 hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-gray-700 dark:text-gray-200 transition">
            Limpar filtros
        </button>
    </form>
</div>
