@if ($state['name'] ?? false)
    <div class="flex items-center gap-4 mb-4">
        <img src="{{ $state['photo'] ?? 'https://via.placeholder.com/100' }}" alt="{{ $state['name'] ?? '' }}"
            class="w-24 h-24 object-cover rounded">
        <div>
            <h2 class="font-bold text-lg">{{ $state['name'] }}</h2>
        </div>
    </div>
@endif
