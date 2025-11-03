@php
    $namespace = "App\\Filament\\Resources\\{$resource}";
@endphp

<form method="POST" action="{{ route('filament.filters.update') }}"
    class="flex flex-wrap items-end gap-4 p-4 bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
    @csrf
    <input type="hidden" name="resource" value="{{ $resource }}">

    {{-- Evento --}}
    <div class="flex flex-col w-full sm:w-48">
        <label class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Evento</label>
        <select id="eventSelect" name="selected_event_id"
            class="filament-forms-select w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 focus:ring-primary-500 focus:border-primary-500"
            x-data x-init="new TomSelect($el, { placeholder: 'Selecione um evento', plugins: ['clear_button'], allowEmptyOption: true })" onchange="updateLots(this.value)">
            <option value="">Selecione um evento</option>
            @foreach ($eventsQuery->pluck('name', 'id') as $id => $name)
                <option value="{{ $id }}" @selected(session("{$namespace}.selected_event_id") == $id)>
                    {{ $name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Lote --}}
    <div class="flex flex-col w-full sm:w-48">
        <label class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Lote</label>
        <select id="lotSelect" name="selected_lot_id"
            class="filament-forms-select w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 focus:ring-primary-500 focus:border-primary-500"
            x-data x-init="window.lotSelect = new TomSelect($el, { placeholder: 'Todos os lotes', plugins: ['clear_button'], allowEmptyOption: true })" onchange="this.form.submit()">
            <option value="">Todos os lotes</option>
            @foreach ($lotsQuery->pluck('name', 'id') as $id => $name)
                <option value="{{ $id }}" @selected(session("{$namespace}.selected_lot_id") == $id)>
                    {{ $name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Cliente --}}
    <div class="flex flex-col w-full sm:w-48">
        <label class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Cliente</label>
        <select name="selected_client_id"
            class="filament-forms-select w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 focus:ring-primary-500 focus:border-primary-500"
            x-data x-init="new TomSelect($el, { placeholder: 'Todos os clientes', plugins: ['clear_button'], allowEmptyOption: true })" onchange="this.form.submit()">
            <option value="">Todos os clientes</option>
            @foreach ($usersQuery->pluck('name', 'id') as $id => $name)
                <option value="{{ $id }}" @selected(session("{$namespace}.selected_client_id") == $id)>
                    {{ $name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Status --}}
    @if (!empty($statusOptions))
        <div class="flex flex-col w-full sm:w-48">
            <label class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Status</label>
            <select name="selected_status_id"
                class="filament-forms-select w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 focus:ring-primary-500 focus:border-primary-500"
                x-data x-init="new TomSelect($el, { placeholder: 'Todos os status', plugins: ['clear_button'], allowEmptyOption: true })" onchange="this.form.submit()">
                <option value="">Todos os status</option>
                @foreach ($statusOptions as $status)
                    @php
                        $text = match ($status) {
                            0 => 'Pendente',
                            1 => 'Aprovado',
                            2 => 'Reprovado',
                        };
                    @endphp
                    <option value="{{ $status }}" @selected(session("{$namespace}.selected_status_id") == (string) $status)>
                        {{ $text }}
                    </option>
                @endforeach
            </select>
        </div>
    @endif

    {{-- Limpar filtros --}}
    <div class="flex items-end">
        <button type="button"
            onclick="window.location.href='{{ route('filament.filters.update') }}?clear={{ $resource }}'"
            class="px-4 py-2 rounded-lg text-sm font-semibold text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 border border-gray-300 dark:border-gray-600 transition-colors duration-150">
            Limpar filtros
        </button>
    </div>
</form>

{{-- Script AJAX para atualizar lotes dinamicamente --}}
<script>
    function updateLots(eventId) {
        lotSelect.clearOptions();
        lotSelect.addOption({
            value: '',
            text: 'Todos os lotes'
        });

        if (!eventId) {
            document.querySelector('form').submit();
            return;
        }

        fetch(`/filament/filters/lots/${eventId}`)
            .then(res => res.json())
            .then(data => {
                data.forEach(lot => {
                    lotSelect.addOption({
                        value: lot.id,
                        text: lot.name
                    });
                });
                lotSelect.refreshOptions(false);
            })
            .finally(() => document.querySelector('form').submit());
    }
</script>
