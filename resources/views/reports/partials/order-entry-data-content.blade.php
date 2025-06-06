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
            <th class="table-header" colspan="6"><span style="color:#FFF">Documentação - envio para assinaturas
                    (Comprador/Vendedor/Testemunhas)</span>
            </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="width: 10%;" class="table-0pky">
                <strong>Contratos:</strong> {{ $order->entry_contracts ? 'OK' : ' ' }}
            </td>
            <td style="width: 10%;" class="table-0pky">
                <strong>NP:</strong> {{ $order->entry_promissory ? 'OK' : ' ' }}
            </td>
            <td style="width: 15%;" class="table-0pky">
                <strong>Cópia do Reg.:</strong> {{ $order->entry_register_copy ? 'OK' : ' ' }}
            </td>
            <td class="table-0pky" style="width: 20%;">
                <strong>Pcla 01 do Negócio:</strong>
                @if ($order->entry_first_parcel_business == 'ticket')
                    BOLETO
                @endif
                @if ($order->entry_first_parcel_business == 'deposit')
                    DEPÓSITO
                @endif
                @if ($order->entry_first_parcel_business == 'transfer')
                    TRANSFERÊNCIA
                @endif
                @if ($order->entry_first_parcel_business == 'pix')
                    PIX
                @endif
                @if ($order->entry_first_parcel_business == 'dinheiro')
                    DINHEIRO
                @endif
                @if ($order->entry_first_parcel_business == 'cheque')
                    CHEQUE
                @endif
                @if ($order->entry_first_parcel_business == 'cartao')
                    CARTÃO
                @endif
            </td>
            <td class="table-0pky" style="width: 20%;">
                <strong>Pcla 01 da Comissão:</strong>
                @if ($order->entry_first_parcel_comission == 'ticket')
                    BOLETO
                @endif
                @if ($order->entry_first_parcel_comission == 'deposit')
                    DEPÓSITO
                @endif
                @if ($order->entry_first_parcel_comission == 'transfer')
                    TRANSFERÊNCIA
                @endif
                @if ($order->entry_first_parcel_comission == 'pix')
                    PIX
                @endif
                @if ($order->entry_first_parcel_comission == 'dinheiro')
                    DINHEIRO
                @endif
                @if ($order->entry_first_parcel_comission == 'cheque')
                    CHEQUE
                @endif
                @if ($order->entry_first_parcel_comission == 'cartao')
                    CARTÃO
                @endif
            </td>
        </tr>
        <tr>
            <td colspan="2" class="table-0pky"><strong>Data de envio:</strong>
                @isset($order->entry_contract_return_date)
                    {{ date('d/m/Y', strtotime($order->entry_contract_return_date)) }}
                @endisset
            </td>
            <td colspan="2" class="table-0pky"><strong>Forma de envio:</strong>
                @isset($order->entrySendingDocsMethod)
                    {{ $order->entrySendingDocsMethod->name }}
                @endisset
            </td>
            <td colspan="2" class="table-0pky"><strong>Assinatura do Comprador:</strong>
                @isset($order->entry_contract_return_date)
                    {{ date('d/m/Y', strtotime($order->entry_contract_return_date)) }}
                @endisset
            </td>
        </tr>
        <tr>
            <td colspan="3" class="table-0pky"><strong>Assinatura do Vendedor:</strong>
                @isset($order->entry_seller_signature_date)
                    {{ date('d/m/Y', strtotime($order->entry_seller_signature_date)) }}
                @endisset
            </td>
            <td colspan="2" class="table-0pky"><strong>Assinatura da Testemunha:</strong>
                @isset($order->entry_witness_signature_date)
                    {{ date('d/m/Y', strtotime($order->entry_witness_signature_date)) }}
                @endisset
            </td>
        </tr>
        <tr>
            <td style="width: 20%;" class="table-0pky">
                <strong>Liberado para exame:</strong> {{ $order->able_to_exam ? 'SIM' : ' ' }}
            </td>
            <td colspan="5" class="table-0pky">
                <strong>Data da liberação:</strong>
                @isset($order->able_to_exam_date)
                    {{ date('d/m/Y', strtotime($order->able_to_exam_date)) }}
                @endisset
            </td>
        </tr>
        <tr>
            <td class="table-fymr">Obs</td>
            <td class="table-0pky" colspan="5">
                {{ $order->entry_documentation_note }}
            </td>
        </tr>
    </tbody>
</table>
