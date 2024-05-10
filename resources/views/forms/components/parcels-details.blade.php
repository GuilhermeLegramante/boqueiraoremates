<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }">

        @if (!$this->showParcelsEdition)
            <div class="flex justify-center">
                <p class="fi-section-header-description text-sm text-gray-500 dark:text-gray-400">
                    <x-filament::button wire:click="enableParcelsEdition()">
                        Habilitar Parcelamento
                    </x-filament::button>
                </p>
            </div>
        @endif

        @if ($this->showParcelsEdition)
            <div class="flex justify-center">
                <p class="fi-section-header-description text-sm text-gray-500 dark:text-gray-400">
                    <x-filament::button wire:click="resolveParcels()">
                        Gerar Parcelas
                    </x-filament::button>
                    <x-filament::button color="gray" wire:click="hideParcels()">
                        Esconder Grade de Parcelas
                    </x-filament::button>
                </p>
            </div>
        @endif

        <br>

        @if ($this->showParcels)
            <div x-bind:aria-expanded="(!isCollapsed).toString()"
                x-bind:class="{ 'invisible h-0 overflow-y-hidden border-none': isCollapsed }"
                class="fi-section-content-ctn border-t border-gray-200 dark:border-white/10" aria-expanded="true">
                <div class="fi-section-content p-6">
                    <div style="--cols-default: repeat(1, minmax(0, 1fr)); --cols-lg: repeat(2, minmax(0, 1fr));"
                        class="grid grid-cols-[--cols-default] lg:grid-cols-[--cols-lg] fi-fo-component-ctn gap-6">

                        <div style="--col-span-default: 1 / -1;" class="col-[--col-span-default]">
                            <table
                                class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5">
                                <thead class="divide-y divide-gray-200 dark:divide-white/5">
                                    <tr class="bg-gray-50 dark:bg-white/5">
                                        <th class="fi-ta-header-cell px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6 fi-table-header-cell-status"
                                            style=";">
                                            <span
                                                class="group flex w-full items-center gap-x-1 whitespace-nowrap justify-start">
                                                <span
                                                    class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">
                                                    Parcela
                                                </span>
                                            </span>
                                        </th>

                                        <th colspan="3"
                                            class="fi-ta-header-cell px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6 fi-table-header-cell-status"
                                            style=";">
                                            <span
                                                class="group flex w-full items-center gap-x-1 whitespace-nowrap justify-start">
                                                <span
                                                    class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">
                                                    Data de Vencimento
                                                </span>
                                            </span>
                                        </th>

                                        <th></th>


                                        <th class="fi-ta-header-cell px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6 fi-table-header-cell-status"
                                            style=";">
                                            <span
                                                class="group flex w-full items-center gap-x-1 whitespace-nowrap justify-start">
                                                <span
                                                    class="fi-ta-header-cell-label text-sm font-semibold text-gray-950 dark:text-white">
                                                    Valor
                                                </span>
                                            </span>
                                        </th>

                                        <th></th>

                                    </tr>
                                </thead>


                                <tbody class="divide-y divide-gray-200 whitespace-nowrap dark:divide-white/5">

                                    @foreach ($this->parcels as $key => $parcel)
                                        <tr class="fi-ta-row [@media(hover:hover)]:transition [@media(hover:hover)]:duration-75 hover:bg-gray-50 dark:hover:bg-white/5"
                                            wire:key="EeyC234bYe73viiJ8x1V.table.records.3">

                                            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3 fi-table-cell-number"
                                                wire:key="EeyC234bYe73viiJ8x1V.table.record.3.column.number">
                                                <div class="fi-ta-col-wrp">
                                                    <div class="fi-ta-text grid w-full gap-y-1 px-3 py-4">
                                                        <div class="flex ">
                                                            <div class="flex max-w-max" style="">
                                                                <div
                                                                    class="fi-ta-text-item inline-flex items-center gap-1.5  ">
                                                                    <span
                                                                        class="fi-ta-text-item-label text-sm leading-6 text-gray-950 dark:text-white  "
                                                                        style="">
                                                                        {{ $parcel['ord'] }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <td colspan="3"
                                                class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3 fi-table-cell-status"
                                                wire:key="EeyC234bYe73viiJ8x1V.table.record.3.column.status">
                                                <div class="fi-ta-col-wrp">
                                                    <div class="fi-ta-text grid w-full gap-y-1 px-3 py-4">
                                                        <div class="flex gap-1.5 flex-wrap ">
                                                            <div class="flex w-max" style="">
                                                                <span
                                                                    class="fi-ta-text-item-label text-sm leading-6 text-gray-950 dark:text-white  "
                                                                    style="">
                                                                    {{ $parcel['date'] }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <td></td>


                                            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3 fi-table-cell-total-price"
                                                wire:key="EeyC234bYe73viiJ8x1V.table.record.3.column.total_price">
                                                <div class="fi-ta-col-wrp">
                                                    <div class="fi-ta-text grid w-full gap-y-1 px-3 py-4">
                                                        <div class="flex ">
                                                            <div class="flex max-w-max" style="">
                                                                <div
                                                                    class="fi-ta-text-item inline-flex items-center gap-1.5  ">

                                                                    <input
                                                                        class="fi-input block w-full border-none py-1.5 text-base text-gray-950 transition duration-75 placeholder:text-gray-400 focus:ring-0 disabled:text-gray-500 disabled:[-webkit-text-fill-color:theme(colors.gray.500)] disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.400)] sm:text-sm sm:leading-6 dark:text-white dark:placeholder:text-gray-500 dark:disabled:text-gray-400 dark:disabled:[-webkit-text-fill-color:theme(colors.gray.400)] dark:disabled:placeholder:[-webkit-text-fill-color:theme(colors.gray.500)] bg-white/0 ps-3 pe-3"
                                                                        inputmode="decimal" step='0.01'
                                                                        type="number"
                                                                        wire:model='values.{{ $key }}'>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <td></td>
                                        </tr>
                                    @endforeach

                                    <tr
                                        class="fi-ta-row [@media(hover:hover)]:transition [@media(hover:hover)]:duration-75 fi-ta-summary-row bg-gray-50 dark:bg-white/5">
                                        <td></td>

                                        <td colspan="2">
                                            <x-filament::button wire:click="checkParcelValues()">
                                                Validar Valores
                                            </x-filament::button>
                                        </td>

                                        <td></td>

                                        <td
                                            class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3 text-start">

                                        </td>
                                        <td
                                            class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3 text-start">
                                            <div class="fi-ta-text-summary grid gap-y-1 px-3 py-4">
                                                <span class="text-sm font-medium text-gray-950 dark:text-white">
                                                    Total das Parcelas
                                                </span>
                                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                                    R$ {{ number_format($this->sum, 2, ',', '.') }}
                                                </span>
                                            </div>
                                        </td>
                                        <td
                                            class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3 text-start">
                                            <div class="fi-ta-text-summary grid gap-y-1 px-3 py-4">
                                                <span class="text-sm font-medium text-gray-950 dark:text-white">
                                                    Valor Bruto
                                                </span>
                                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                                    R$
                                                    {{ number_format($this->form->getState()['gross_value'], 2, ',', '.') }}
                                                </span>
                                            </div>
                                        </td>
                                        <td
                                            class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3 text-start">
                                        </td>

                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-dynamic-component>
