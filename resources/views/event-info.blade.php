<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    {{-- Coluna 1: Nome, data e pré-lance + contador --}}
    <div class="p-6 rounded-2xl shadow bg-white border border-gray-100 dark:bg-gray-800 dark:border-gray-700 space-y-4">
        <h2 class="text-2xl font-extrabold text-gray-800 dark:text-gray-100 leading-tight">
            {{ $getRecord()->name }}
        </h2>

        <p class="text-lg font-medium text-indigo-600 dark:text-indigo-400 flex items-center gap-2">
            <x-heroicon-o-calendar class="w-5 h-5 text-indigo-500 dark:text-indigo-300" />
            {{ \Carbon\Carbon::parse($getRecord()->start_date)->format('d/m/Y \à\s H:i') }}hs
        </p>

        <div x-data="{
            targetDate: new Date('{{ \Carbon\Carbon::parse($getRecord()->start_date)->format('Y-m-d H:i:s') }}').getTime(),
            days: 0,
            hours: 0,
            minutes: 0,
            seconds: 0,
            startCountdown() {
                setInterval(() => {
                    const now = new Date().getTime();
                    const distance = this.targetDate - now;
                    if (distance > 0) {
                        this.days = Math.floor(distance / (1000 * 60 * 60 * 24));
                        this.hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        this.minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        this.seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    } else {
                        this.days = this.hours = this.minutes = this.seconds = 0;
                    }
                }, 1000);
            }
        }" x-init="startCountdown()"
            class="text-gray-700 dark:text-gray-200 font-semibold text-sm bg-indigo-50 dark:bg-indigo-900/40 p-3 rounded-xl border border-indigo-100 dark:border-indigo-800">
            <span x-text="days"></span>d :
            <span x-text="hours"></span>h :
            <span x-text="minutes"></span>m :
            <span x-text="seconds"></span>s até o evento
        </div>

        <div
            class="bg-indigo-50 border border-indigo-100 dark:bg-indigo-900/40 dark:border-indigo-800 rounded-xl p-4 text-sm text-gray-700 dark:text-gray-200">
            <span class="font-semibold text-indigo-700 dark:text-indigo-300">Pré-lance online:</span>
            de <span
                class="font-medium">{{ \Carbon\Carbon::parse($getRecord()->pre_start_date)->format('d/m/Y \à\s H:i') }}hs</span>
            até <span
                class="font-medium">{{ \Carbon\Carbon::parse($getRecord()->pre_finish_date)->format('d/m/Y \à\s H:i') }}hs</span>
        </div>
    </div>

    {{-- Coluna 2: Descrição + Regulamento --}}
    <div class="p-6 rounded-2xl shadow bg-white border border-gray-100 dark:bg-gray-800 dark:border-gray-700 space-y-4">
        <div class="text-gray-700 dark:text-gray-200 leading-relaxed">
            {!! nl2br(e($getRecord()->note)) !!}
        </div>

        @if ($getRecord()->regulation)
            <div class="pt-4">
                <a href="{{ asset('storage/' . $getRecord()->regulation) }}" target="_blank"
                    class="inline-flex items-center gap-2">
                    <x-heroicon-o-document-text class="w-5 h-5 text-indigo-700 dark:text-indigo-300" />
                    <span class="font-semibold text-indigo-700 dark:text-indigo-300">
                        Regulamento (PDF)
                    </span>
                </a>
            </div>
        @else
            <p class="text-sm text-gray-500 dark:text-gray-400 italic">
                Nenhum regulamento enviado.
            </p>
        @endif
    </div>
</div>
