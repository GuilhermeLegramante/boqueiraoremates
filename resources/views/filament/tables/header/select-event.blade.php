<div class="flex flex-col items-center justify-center gap-3 py-4">
    <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">
        Filtrar por evento ativo
    </h2>

    <form method="POST" action="{{ route('filament.set-event-filter') }}" class="flex items-center gap-2">
        @csrf
        <select name="event_id" class="filament-forms-select w-72 text-sm rounded-lg border-gray-300 dark:border-gray-600"
            onchange="this.form.submit()">
            <option value="">Selecione um evento...</option>
            @foreach ($events as $id => $name)
                <option value="{{ $id }}" @selected($selected == $id)>
                    {{ $name }}
                </option>
            @endforeach
        </select>
    </form>
</div>
