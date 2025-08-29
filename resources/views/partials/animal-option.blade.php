<div class="flex items-center space-x-2">
    <img src="{{ $animal->photo ? asset('storage/' . $animal->photo) : '/images/no-photo.png' }}"
        class="w-6 h-6 rounded-full object-cover">
    <span>{{ $animal->name }}</span>
</div>
