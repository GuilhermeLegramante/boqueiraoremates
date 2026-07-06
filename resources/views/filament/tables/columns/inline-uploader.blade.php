<div class="flex items-center justify-center w-full" @click.stop x-data="{ isUploading: false, progress: 0 }"
    x-on:livewire-upload-start="isUploading = true" x-on:livewire-upload-finish="isUploading = false"
    x-on:livewire-upload-error="isUploading = false" x-on:livewire-upload-progress="progress = $event.detail.progress">

    <label
        class="group relative flex h-24 w-24 cursor-pointer flex-col items-center justify-center rounded-lg border border-dashed border-gray-300 bg-gray-50 shadow-sm transition-all hover:border-primary-500 hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-900 dark:hover:border-primary-400 dark:hover:bg-gray-800">

        @if ($getState())
            <div class="absolute inset-0 overflow-hidden rounded-lg">
                <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($getState()) }}"
                    class="h-full w-full object-cover transition-opacity duration-200 group-hover:opacity-30">
            </div>
        @endif

        <div
            class="z-10 flex flex-col items-center justify-center text-gray-400 group-hover:text-primary-500 dark:group-hover:text-primary-400">
            <svg class="h-6 w-6 transition-transform group-hover:scale-110" xmlns="http://www.w3.org/2000/svg"
                fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
        </div>

        <input type="file" wire:model="mountedTableActionsData.{{ $getRecord()->id }}.{{ $getName() }}"
            class="hidden" accept="image/*" />

        <div x-show="isUploading" x-transition
            class="absolute inset-0 z-20 flex flex-col items-center justify-center rounded-lg bg-gray-900/70 p-1 backdrop-blur-sm"
            style="display: none;">
            <span class="mb-1 text-xs font-bold text-white" x-text="progress + '%'">0%</span>
            <div class="w-4/5 bg-gray-700 h-1 rounded-full overflow-hidden">
                <div class="bg-primary-500 h-1 transition-all duration-150 ease-out"
                    x-bind:style="'width: ' + progress + '%'"></div>
            </div>
        </div>
    </label>
</div>
