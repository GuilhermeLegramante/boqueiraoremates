<div>
    <p class="text-lg font-medium text-indigo-600 dark:text-indigo-400 flex items-center gap-2">
        {{ $getRecord()->name }}
    </p>

    <br>

    <p class="text-lg font-medium text-indigo-600 dark:text-indigo-400 flex items-center gap-2">
        <x-heroicon-o-calendar class="w-5 h-5 text-indigo-500 dark:text-indigo-300" />
        {{ \Carbon\Carbon::parse($getRecord()->start_date)->format('d/m/Y \à\s H:i') }}hs
    </p>

    <div
        class="bg-indigo-50 dark:bg-indigo-900/40 dark:border-indigo-800 rounded-xl p-4 text-sm text-gray-700 dark:text-gray-200">
        <span class="font-semibold text-indigo-700 dark:text-indigo-300">Pré-lance online:</span>
        de <span
            class="font-medium">{{ \Carbon\Carbon::parse($getRecord()->pre_start_date)->format('d/m/Y \à\s H:i') }}hs</span>
        até <span
            class="font-medium">{{ \Carbon\Carbon::parse($getRecord()->pre_finish_date)->format('d/m/Y \à\s H:i') }}hs</span>
    </div>

</div>
