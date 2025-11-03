@props([
    'eventsQuery' => null, // Query builder para eventos (Event::query())
    'lotsQuery' => null, // Query builder para lotes (AnimalEvent::query())
    'usersQuery' => null, // Query builder para clientes (User::query())
    'showPublished' => true, // Filtrar apenas eventos publicados
    'statusOptions' => [0, 1, 2], // Status que aparecem no select
])

@php
    use App\Models\AnimalEvent;
    use App\Models\User;

    // Eventos
    $eventsQuery = $eventsQuery ?? \App\Models\Event::query();
    if ($showPublished) {
        $eventsQuery->where('published', true);
    }
    $events = $eventsQuery->pluck('name', 'id');

    // Seleções da sessão
    $selectedEvent = session('selected_event_id');
    $selectedLot = session('selected_lot_id');
    $selectedClient = session('selected_client_id');
    $selectedStatus = session('selected_status_id');

    // Lotes dinâmicos
    $lotsQuery = $lotsQuery ?? AnimalEvent::query();
    $lots = $selectedEvent ? $lotsQuery->where('event_id', $selectedEvent)->pluck('lot_number', 'id') : collect();

    // Clientes dinâmicos
    $usersQuery = $usersQuery ?? User::query();
    $clients = $usersQuery
        ->whereHas('bids', function ($q) use ($selectedEvent) {
            if ($selectedEvent) {
                $q->where('event_id', $selectedEvent);
            }
        })
        ->pluck('name', 'id');
@endphp

<div class="flex flex-col items-center justify-center gap-3 mt-4 p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
    <form method="POST" action="{{ route('filament.set-bid-filters') }}"
        class="flex flex-wrap items-center justify-center gap-3 w-full">
        @csrf

        {{-- Evento --}}
        <select name="event_id"
            class="filament-forms-select w-full sm:w-64 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200"
            x-data x-init="new TomSelect($el, { searchField: ['text'], placeholder: 'Selecione um evento...' })" onchange="this.form.submit()">

            {{-- Nenhum evento selecionado por padrão --}}
            <option value="">Selecione um evento...</option>

            {{-- Lista de eventos --}}
            @foreach ($events as $id => $name)
                <option value="{{ $id }}" @selected($selectedEvent !== null && $selectedEvent == $id)>{{ $name }}</option>
            @endforeach
        </select>


        {{-- Status --}}
        <select name="status_id"
            class="filament-forms-select w-full sm:w-48 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200"
            x-data x-init="new TomSelect($el, { placeholder: 'Todos os status' })" onchange="this.form.submit()">

            {{-- Todos --}}
            <option value="" @selected(is_null($selectedStatus))>Todos os status</option>

            {{-- Status --}}
            @foreach ($statusOptions as $status)
                @php
                    $text = match ($status) {
                        0 => 'Pendente',
                        1 => 'Aprovado',
                        2 => 'Reprovado',
                    };
                @endphp
                <option value="{{ $status }}" @selected($selectedStatus !== null && $selectedStatus == $status)>{{ $text }}</option>
            @endforeach
        </select>


        {{-- Lote --}}
        <select name="lot_id"
            class="filament-forms-select w-full sm:w-48 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200"
            x-data x-init="new TomSelect($el, { searchField: ['text'], placeholder: 'Todos os lotes' })" onchange="this.form.submit()" @disabled(!$selectedEvent)>
            <option value="">Todos os lotes</option>
            @foreach ($lots as $id => $lot)
                <option value="{{ $id }}" @selected($selectedLot == $id)>Lote {{ $lot }}</option>
            @endforeach
        </select>

        {{-- Cliente --}}
        <select name="client_id"
            class="filament-forms-select w-full sm:w-56 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200"
            x-data x-init="new TomSelect($el, { searchField: ['text'], placeholder: 'Todos os clientes' })" onchange="this.form.submit()">
            <option value="">Todos os clientes</option>
            @foreach ($clients as $id => $name)
                <option value="{{ $id }}" @selected($selectedClient == $id)>{{ $name }}</option>
            @endforeach
        </select>

        {{-- Limpar filtros --}}
        <button type="submit" name="clear_filters" value="1"
            class="px-3 py-2 text-sm rounded-lg border border-gray-300 hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-gray-700 dark:text-gray-200 whitespace-nowrap">
            Limpar filtros
        </button>
    </form>
</div>
