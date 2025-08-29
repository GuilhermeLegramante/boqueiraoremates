@if ($getRecord()?->banner)
    <img src="{{ asset('storage/' . $getRecord()->banner) }}" alt="Banner do Evento"
        class="w-full h-64 object-cover rounded-lg shadow mb-4">
@endif
