<table class="table" style="table-layout: fixed; width: 100%;">
    <colgroup>
        <col style="width: 148px">
        <col style="width: 344px">
        <col style="width: 153px">
        <col style="width: 128px">
        <col style="width: 196px">
        <col style="width: 128px">
    </colgroup>
    <thead>
        <tr>
            <th class="table-header" colspan="6"><span style="color:#FFF">Serviço / Vendedor</span></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="table-0pky"><span style="font-weight:bold">Evento</span></td>
            <td class="table-0pky" colspan="3">{{ $order->event->name }}</td>
            <td class="table-0pky"></td>
            <td class="table-0pky"></td>
        </tr>
        <tr>
            <td class="table-0pky"><span style="font-weight:bold">Vendedor</span></td>
            <td class="table-0pky" colspan="3">{{ $order->seller->name }}</td>
            <td class="table-fymr">Cód.</td>
            <td class="table-0pky">{{ $order->seller->id }}</td>
        </tr>
        <tr>
            <td class="table-fymr">Cidade/UF</td>
            <td class="table-0pky" colspan="3">
                {{ $order->seller->address->city }} / {{ $order->seller->address->state }}
            </td>
            <td class="table-fymr">Tel.<br></td>
            <td class="table-0pky">{{ $order->seller->whatsapp }}</td>
        </tr>
        <tr>
            <td class="table-fymr">Obs</td>
            <td class="table-0pky" colspan="5">
                {{ $order->service_note }}
            </td>
        </tr>
        <tr>
            <td class="table-header-text" colspan="6">Comprador</td>
        </tr>
        <tr>
            <td class="table-fymr">Comprador</td>
            <td class="table-0pky"colspan="3">
                {{ $order->buyer->name }}
            </td>
            <td class="table-fymr">Cód.</td>
            <td class="table-0pky">{{ $order->buyer->id }}</td>
        </tr>
        <tr>
            <td class="table-fymr">Cidade/UF</td>
            <td class="table-0pky" colspan="3">
                {{ $order->buyer->address->city }} / {{ $order->buyer->address->state }}
            </td>
            <td class="table-fymr">Tel.</td>
            <td class="table-0pky">
                {{ $order->buyer->whatsapp }}
            </td>
        </tr>
        <tr>
            <td class="table-header-text" colspan="6">Negócio</td>
        </tr>
        <tr>
            <td class="table-fymr">Remate</td>
            <td colspan="5" class="table-0pky">{{ $order->event->name }}</td>
        </tr>
        <tr>
            <td class="table-fymr">Animal</td>
            <td class="table-0pky" colspan="3">
                {{ $order->animal->name }}
            </td>
            <td class="table-fymr">Lote</td>
            <td class="table-0pky">
                {{ $order->batch }}
            </td>
        </tr>
        <tr>
            <td class="table-fymr">Parcela</td>
            {{-- <td class="table-0pky">
                {{ 'R$ ' . number_format($order->gross_value / $order->multiplier, 2, ',', '.') }}
            </td> --}}
            @include('reports.partials.td-money', [
                'money_value' => $order->gross_value / $order->multiplier,
            ])

            <td class="table-fymr">Multiplicador</td>
            <td class="table-0pky">
                {{ $order->multiplier }}
            </td>
            <td class="table-fymr">Valor Bruto</td>
            {{-- <td class="table-llyw">
                <strong>
                    {{ 'R$ ' . number_format($order->gross_value, 2, ',', '.') }}
                </strong>
            </td> --}}
            @include('reports.partials.td-money', [
                'money_value' => $order->gross_value,
                'td_css' => 'font-weight: bold; background-color: #c0c0c0;',
            ])

        </tr>
        <tr>
            <td class="table-fymr">Prazo Escolhido</td>
            <td class="table-0pky">
                {{ $order->paymentWay->name }}
            </td>
            <td class="table-fymr">Desconto Obtido</td>
            <td class="table-0pky">
                {{ $order->discount_percentage }}%
            </td>
            <td class="table-fymr">Valor Líquido</td>
            {{-- <td class="table-llyw">
                <strong>
                    {{ 'R$ ' . number_format($netValue, 2, ',', '.') }}
                </strong>
            </td> --}}
            @include('reports.partials.td-money', [
                'money_value' => $netValue,
                'td_css' => 'font-weight: bold; background-color: #c0c0c0;',
            ])
        </tr>
        <tr>
            <td class="table-fymr">Obs</td>
            <td class="table-0pky" colspan="5">
                {{ $order->business_note }}
            </td>
        </tr>
        <tr>
            <td class="table-header-text" colspan="6">Parcelas</td>
        </tr>
    </tbody>
</table>

<div class="container">
    @include('reports.partials.parcels')
</div>
