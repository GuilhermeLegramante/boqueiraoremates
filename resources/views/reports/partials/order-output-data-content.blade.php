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
            <th class="table-header" colspan="6"><span style="color:#FFF">Documentação - Saída (envio para o
                    Vendedor)</span>
            </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="width: 10%;" class="table-0pky">
                <strong>Contratos:</strong> {{ $order->output_contracts ? 'OK' : ' ' }}
            </td>
            <td style="width: 10%;" class="table-0pky">
                <strong>NP:</strong> {{ $order->output_promissory ? 'OK' : ' ' }}
            </td>
            <td style="width: 15%;" class="table-0pky">
                <strong>Cópia do Reg.:</strong> {{ $order->output_register_copy ? 'OK' : ' ' }}
            </td>
            <td class="table-0pky" colspan="2" style="width: 20%;">
                <strong>Pcla 01 do Negócio:</strong>
                @if ($order->output_first_parcel_business == 'ticket')
                    BOLETO
                @endif
                @if ($order->output_first_parcel_business == 'deposit')
                    DEPÓSITO
                @endif
            </td>
        </tr>
        <tr>
            <td colspan="2" class="table-0pky"><strong>Data de envio:</strong>
                {{ date('d/m/Y', strtotime($order->output_sending_documentation_date)) }}
            </td>
            <td colspan="2" class="table-0pky"><strong>Forma de envio:</strong>
                @isset($order->outputSendingDocsMethod)
                    {{ $order->outputSendingDocsMethod->name }}
                @endisset
            </td>
            <td colspan="2" class="table-0pky"><strong>Assinatura do Comprador:</strong>
                @isset($order->output_contract_return_date)
                    {{ date('d/m/Y', strtotime($order->output_contract_return_date)) }}
                @endisset
            </td>
        </tr>
        <tr>
            <td class="table-fymr">Obs</td>
            <td class="table-0pky" colspan="5">
                {{ $order->output_documentation_note }}
            </td>
        </tr>
    </tbody>
</table>
