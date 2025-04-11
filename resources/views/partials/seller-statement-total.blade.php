@php
    $proventos = collect($state['proventos_adicionais'] ?? [])->sum('valor');
    $descontos = collect($state['descontos_adicionais'] ?? [])->sum('valor');
    $saldo = $proventos - $descontos;
@endphp

<div class="grid grid-cols-3 gap-4">
    <div>
        <p class="text-sm text-gray-600">Total de Proventos</p>
        <p class="text-lg font-bold text-green-600">R$ {{ number_format($proventos, 2, ',', '.') }}</p>
    </div>
    <div>
        <p class="text-sm text-gray-600">Total de Descontos</p>
        <p class="text-lg font-bold text-red-600">R$ {{ number_format($descontos, 2, ',', '.') }}</p>
    </div>
    <div>
        <p class="text-sm text-gray-600">Saldo Final</p>
        <p class="text-lg font-bold {{ $saldo >= 0 ? 'text-green-600' : 'text-red-600' }}">
            R$ {{ number_format($saldo, 2, ',', '.') }}
        </p>
    </div>
</div>
