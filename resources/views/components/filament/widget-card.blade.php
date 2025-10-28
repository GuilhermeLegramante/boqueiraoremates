@if ($url)
    <a href="{{ $url }}" class="block group">
@endif

<div
    class="rounded-xl shadow-xl p-6 text-center transition-colors duration-300 {{ $color == 'danger' ? 'bg-red-600 hover:bg-red-700' : ($color == 'success' ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-600 hover:bg-gray-700') }}">
    <div class="flex justify-center mb-2">
        <x-dynamic-component :component="$icon" class="w-10 h-10 text-white" />
    </div>
    <h2 class="text-white font-bold text-lg">{{ $label }}</h2>
    <p class="text-white text-4xl font-extrabold mt-2">{{ $count }}</p>
</div>

@if ($url)
    </a>
@endif
