@php
    $selectedEventId = session('selected_event_id', '');
    $selectedLotId = session('selected_lot_id', '');
    $selectedClientId = session('selected_client_id', '');
    $selectedStatusId = session('selected_status_id', '');
@endphp

<form method="POST" action="{{ route('filament.filters.update') }}"
    class="flex flex-wrap items-end gap-4 p-4 bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
    @csrf

    {{-- Evento --}}
    <div class="flex flex-col w-full sm:w-48">
        <label class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Evento</label>
        <select name="selected_event_id"
            class="filament-forms-select w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200"
            onchange="this.form.submit()">
            <option value="">Selecione um evento</option>
            @foreach ($events as $id => $name)
                <option value="{{ $id }}" @selected((string) $selectedEventId === (string) $id)>{{ $name }}</option>
            @endforeach
        </select>
    </div>

    {{-- Lote --}}
    <div class="flex flex-col w-full sm:w-48">
        <label class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Lote</label>
        <select name="selected_lot_id"
            class="filament-forms-select w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200"
            onchange="this.form.submit()">
            <option value="">Todos os lotes</option>
            @foreach ($lots as $lot)
                @if (!$selectedEventId || $lot->event_id == $selectedEventId)
                    <option value="{{ $lot->id }}" @selected($selectedLotId == $lot->id)>
                        {{ $lot->lot_number ?? ($lot->name ?? 'Lote ' . $lot->id) }}
                    </option>
                @endif
            @endforeach
        </select>
    </div>

    {{-- Cliente --}}
    <div class="flex flex-col w-full sm:w-48">
        <label class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Cliente</label>
        <select name="selected_client_id"
            class="filament-forms-select w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200"
            onchange="this.form.submit()">
            <option value="">Todos os clientes</option>
            @foreach ($users as $user)
                @php
                    $hasBidInEvent = !$selectedEventId || $user->bids->where('event_id', $selectedEventId)->count() > 0;
                @endphp
                @if ($hasBidInEvent)
                    <option value="{{ $user->id }}" @selected($selectedClientId == $user->id)>{{ $user->name }}</option>
                @endif
            @endforeach
        </select>
    </div>



    {{-- Status --}}
    <div class="flex flex-col w-full sm:w-48">
        <label class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Status</label>
        <select name="selected_status_id"
            class="filament-forms-select w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200"
            onchange="this.form.submit()">
            <option value="">Todos os status</option>
            @foreach ($statusOptions as $status)
                @php
                    $text = match ($status) {
                        0 => 'Pendente',
                        1 => 'Aprovado',
                        2 => 'Reprovado',
                    };
                @endphp
                <option value="{{ $status }}" @selected((string) $selectedStatusId === (string) $status)>{{ $text }}</option>
            @endforeach
        </select>
    </div>

    {{-- Limpar filtros --}}
    <div class="flex items-end">
        <button type="button" onclick="window.location.href='{{ route('filament.filters.update') }}?clear=1'"
            class="px-4 py-2 rounded-lg text-sm font-semibold text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 border border-gray-300 dark:border-gray-600 transition-colors duration-150">
            Limpar filtros
        </button>
    </div>
</form>
