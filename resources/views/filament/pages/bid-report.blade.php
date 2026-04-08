<x-filament-panels::page>
    <x-filament-panels::form wire:submit="submit">
        {{ $this->form }}
    </x-filament-panels::form>

    <hr class="my-6 border-gray-200">

    @if ($this->data['event_id'] ?? false)

        @if ($this->winner)
            <div
                class="mb-6 p-6 bg-gradient-to-r from-yellow-100 to-yellow-200 border-2 border-yellow-400 rounded-2xl shadow-sm animate-bounce-short">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-yellow-400 rounded-full">
                        <x-heroicon-m-trophy class="w-8 h-8 text-white" />
                    </div>
                    <div>
                        <p class="text-sm font-bold text-yellow-800 uppercase tracking-wider">Ganhador do Sorteio</p>
                        <h3 class="text-2xl font-black text-gray-900 uppercase">
                            {{ $this->winner->user->name }}
                        </h3>
                        <p class="text-sm text-yellow-900">
                            Lote: <strong>{{ $this->winner->lot_number }}</strong> |
                            Lance: <strong>R$ {{ number_format($this->winner->amount, 2, ',', '.') }}</strong>
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold">Lances Aprovados: {{ $this->bids->count() }}</h2>

            <x-filament::button tag="a"
                href="{{ route('report.bids.pdf', [
                    'eventId' => $this->data['event_id'],
                    'selectedBids' => $this->selectedBids,
                ]) }}"
                target="_blank" icon="heroicon-m-printer" color="success">
                Gerar Relatório PDF
            </x-filament::button>
        </div>

        <div class="overflow-x-auto bg-white border border-gray-300 rounded-xl">
            <table class="w-full text-left divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 w-10">
                            <x-heroicon-m-check-circle class="w-5 h-5 text-gray-400" />
                        </th>
                        <th class="px-4 py-2 font-bold text-sm text-gray-700">Código</th>
                        <th class="px-4 py-2 font-bold text-sm text-gray-700">Data/Hora</th>
                        <th class="px-4 py-2 font-bold text-sm text-gray-700">Cliente</th>
                        <th class="px-4 py-2 font-bold text-sm text-gray-700">Lote</th>
                        <th class="px-4 py-2 font-bold text-sm text-gray-700 text-right">Valor</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($this->bids as $bid)
                        <tr
                            class="hover:bg-gray-50 transition {{ $this->winner && $this->winner->id === $bid->id ? 'bg-yellow-50 border-l-4 border-yellow-400' : '' }}">
                            <td class="px-4 py-2 text-center">
                                <input type="checkbox" wire:model.live="selectedBids" value="{{ $bid->id }}"
                                    class="w-4 h-4 text-warning-600 border-gray-300 rounded focus:ring-warning-500">
                            </td>

                            <td class="px-4 py-2 text-sm text-gray-600 font-mono">
                                #{{ str_pad($bid->id, 5, '0', STR_PAD_LEFT) }}
                            </td>

                            <td class="px-4 py-2 text-sm text-gray-600">
                                {{ $bid->created_at->format('d/m/Y H:i') }}
                            </td>

                            <td class="px-4 py-2 text-sm font-medium text-gray-900 uppercase">
                                {{ $bid->user->name }}
                            </td>

                            <td class="px-4 py-2 text-sm text-gray-600">
                                {{ $bid->lot_number }}
                            </td>

                            <td class="px-4 py-2 text-sm font-bold text-gray-900 text-right">
                                R$ {{ number_format($bid->amount, 2, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500 italic">
                                Nenhum lance encontrado para os critérios selecionados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if ($this->bids->count() > 0)
                    <tfoot class="bg-gray-50 font-bold">
                        <tr>
                            <td colspan="5" class="px-4 py-2 text-right text-gray-700 uppercase">Total Acumulado:
                            </td>
                            <td class="px-4 py-2 text-right text-green-600">
                                R$ {{ number_format($this->bids->sum('amount'), 2, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>

        <div class="mt-2 text-xs text-gray-500 italic">
            * {{ count($this->selectedBids) }} lances marcados para participar do sorteio e aparecer no PDF.
        </div>
    @endif
</x-filament-panels::page>
