<div class="flex flex-col items-center p-2 border rounded-lg bg-white shadow-sm dark:bg-gray-800"
    style="position: relative;">
    @php
        $animal = $getState()['record']; // modelo Animal com pivot
        $status = strtolower($animal->pivot->status ?? 'disponivel');

        // cores padrão (modo claro)
        $bgColor = '#e5e7eb'; // cinza claro
        $textColor = '#1f2937'; // texto escuro

        // cores específicas por status (modo claro)
        if ($status === 'disponivel') {
            $bgColor = '#d1fae5';
            $textColor = '#166534';
        }
        if ($status === 'vendido') {
            $bgColor = '#fee2e2';
            $textColor = '#b91c1c';
        }
        if ($status === 'reservado') {
            $bgColor = '#fef9c3';
            $textColor = '#78350f';
        }

        // cores alternativas para dark mode
        $bgColorDark = '#16653480'; // verde escuro translúcido
        $textColorDark = '#d1fae5'; // verde claro
        if ($status === 'vendido') {
            $bgColorDark = '#b91c1c80';
            $textColorDark = '#fee2e2';
        }
        if ($status === 'reservado') {
            $bgColorDark = '#78350f80';
            $textColorDark = '#fef9c3';
        }
    @endphp

    @if ($animal->pivot->photo)
        <img src="{{ $animal->pivot->photo }}" alt="{{ $getState()['name'] }}"
            class="w-32 h-auto max-h-32 rounded border dark:border-gray-700">
    @endif

    <span class="mt-2 text-center text-lg font-bold text-gray-800 dark:text-gray-100">
        {{ $getState()['name'] }}
    </span>

    <span class="mt-1 text-center text-sm text-gray-600 dark:text-gray-300">
        Lote: {{ $animal->pivot->lot_number ?? '-' }}
    </span>

    <span class="mt-1 text-center text-sm text-gray-600 dark:text-gray-300">
        <strong>R$
            {{ isset($animal->pivot->min_value) ? number_format($animal->pivot->min_value, 2, ',', '.') : '-' }}</strong>
    </span>

    <span class="mt-1 text-center text-sm text-gray-600 dark:text-gray-300">
        Valor Alvo: R$
        {{ isset($animal->pivot->target_value) ? number_format($animal->pivot->target_value, 2, ',', '.') : '-' }}
    </span>

    <span
        style="
        margin-top: 0.5rem;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 600;
        background-color: {{ $bgColor }};
        color: {{ $textColor }};
        display: inline-block;
    "
        onload="
        const span = this;
        const darkStyle = document.createElement('style');
        darkStyle.innerHTML = `
            @media (prefers-color-scheme: dark) {
                span { background-color: {{ $bgColorDark }} !important; color: {{ $textColorDark }} !important; }
            }
        `;
        document.head.appendChild(darkStyle);
    ">
        {{ ucfirst($status) }}
    </span>
    
</div>
