<div class="flex items-center justify-center w-full" @click.stop x-data="{ isUploading: false, progress: 0 }"
    x-on:livewire-upload-start="isUploading = true" x-on:livewire-upload-finish="isUploading = false"
    x-on:livewire-upload-error="isUploading = false" x-on:livewire-upload-progress="progress = $event.detail.progress">

    <label
        class="group relative flex h-24 w-24 cursor-pointer flex-col items-center justify-center rounded-lg border border-dashed border-gray-300 bg-gray-50 p-2 shadow-sm transition-all hover:border-primary-500 hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-900 dark:hover:border-primary-400 dark:hover:bg-gray-800">

        @if ($getState())
            {{-- Exibição quando o PDF já foi enviado --}}
            <div class="z-10 flex flex-col items-center justify-center text-center">
                {{-- Ícone de documento PDF --}}
                <svg class="h-8 w-8 text-red-500 transition-transform group-hover:scale-110"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
                <span class="mt-1 text-[10px] font-medium text-gray-600 dark:text-gray-400">PDF Anexado</span>
            </div>
        @else
            {{-- Exibição quando está vazio --}}
            <div
                class="z-10 flex flex-col items-center justify-center text-gray-400 group-hover:text-primary-500 dark:group-hover:text-primary-400">
                <svg class="h-6 w-6 transition-transform group-hover:scale-110" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span class="mt-1 text-[10px] text-gray-400">Enviar PDF</span>
            </div>
        @endif

        {{-- Input restrito para arquivos PDF (.pdf ou application/pdf) --}}
        <input type="file" wire:model="mountedTableActionsData.{{ $getRecord()->id }}.{{ $getName() }}"
            class="hidden" accept=".pdf,application/pdf" />

        {{-- Indicador de Progresso do Upload --}}
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
