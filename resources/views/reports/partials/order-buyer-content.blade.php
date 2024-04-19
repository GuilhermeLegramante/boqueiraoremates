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
            <th class="table-header" colspan="6"><span style="color:#FFF">Faturamento pelo Comprador</span></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="table-0pky"><span style="font-weight:bold">Comissão</span></td>
            <td class="table-0pky">{{ $order->buyer_commission }}%</td>
            <td class="table-0pky"><span style="font-weight:bold">Faturamento Boqueirão</span></td>
            </td>
            <td class="table-0pky">
                {{ 'R$ ' . number_format($buyerComissionValue, 2, ',', '.') }}
            </td>
            <td class="table-0pky">
                <span style="font-weight:bold">N° de parcelas</span>
            </td>
            <td class="table-0pky">
                {{ $order->buyer_commission_installments_number }}
            </td>
        </tr>
    </tbody>
</table>

<div class="container">
    @include('reports.partials.buyer-parcels')
</div>