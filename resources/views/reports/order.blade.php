@extends('reports.page')

@section('header')
    @include('reports.header')
@endsection

@section('content')
    <h1 style="margin-left: 1%; font-size: 23px;">ORDEM DE SERVIÇO {{ $order->number }} /
        {{ date('Y', strtotime($order->created_at)) }}</h1>
    <p style="margin-left: 1%; margin-top: -1%"><strong>Cadastrado em:
        </strong>{{ date('d/m/Y', strtotime($order->created_at)) }} <strong> Atualizado em:
        </strong>{{ date('d/m/Y \à\s H:i:s', strtotime($order->updated_at)) }}</p>
    <br>
    <h1 style="margin-left: 1%;">Serviço</h1>
    <table>
        <tbody>
            <tr class="bg-light" style="font-size: 13px;">
                <td class="collumn-left"><strong>Evento:</strong> {{ $order->event->name }}</td>
                <td class="collumn-right"><strong>Vendedor:</strong> {{ $order->seller->name }}</td>
            </tr>
        </tbody>
    </table>
    <br>
    <h1 style="margin-left: 1%;">Negócio</h1>
    <table>
        <tbody>
            <tr class="bg-light" style="font-size: 13px;">
                <td class="collumn-left"><strong>Comprador:</strong> {{ $order->buyer->name }}</td>
                <td class="collumn-right"><strong>Animal:</strong> {{ $order->animal->name }}</td>
            </tr>
            <tr class="" style="font-size: 13px;">
                <td class="collumn-left"><strong>Valor da Parcela:</strong> {{ $order->parcel_value }}</td>
                <td class="collumn-right"><strong>Dia do Vencimento:</strong> {{ $order->due_day }}</td>
            </tr>
            <tr class="bg-light" style="font-size: 13px;">
                <td class="collumn-left"><strong>Multiplicador:</strong> {{ $order->multiplier }}</td>
                <td class="collumn-right"><strong>Valor Bruto:</strong>
                    {{ 'R$ ' . number_format($order->gross_value, 2, ',', '.') }}</td>
            </tr>
            <tr class="" style="font-size: 13px;">
                <td class="collumn-left"><strong>Forma de Pagamento:</strong> {{ $order->paymentWay->name }}</td>
                <td class="collumn-right"><strong>Percentual de Desconto:</strong> {{ $order->discount_percentage }}</td>
            </tr>
            <tr class="bg-light" style="font-size: 13px;">
                <td class="collumn-left"><strong>Valor Líquido:</strong>
                    {{ 'R$ ' . number_format($netValue, 2, ',', '.') }}</td>
                <td class="collumn-right"></td>
            </tr>
        </tbody>
    </table>
    <br>
    <h1 style="margin-left: 1%;">Faturamento pelo Comprador</h1>
    <table>
        <tbody>
            <tr class="bg-light" style="font-size: 13px;">
                <td class="collumn-left"><strong>Comissão:</strong> {{ $order->buyer_commission }}%</td>
                <td class="collumn-right"><strong>Valor:</strong>
                    {{ 'R$ ' . number_format($buyerComissionValue, 2, ',', '.') }}</td>
            </tr>
            <tr class="" style="font-size: 13px;">
                <td class="collumn-left"><strong>Dia do Vencimento:</strong> {{ $order->buyer_due_day }}</td>
                <td class="collumn-right"><strong>Quantidade de Parcelas:</strong>
                    {{ $order->buyer_commission_installments_number }}</td>
            </tr>
        </tbody>
    </table>
    <br>
    <h1 style="margin-left: 1%;">Faturamento pelo Vendedor</h1>
    <table>
        <tbody>
            <tr class="bg-light" style="font-size: 13px;">
                <td class="collumn-left"><strong>Comissão:</strong> {{ $order->seller_commission }}%</td>
                <td class="collumn-right"><strong>Valor:</strong>
                    {{ 'R$ ' . number_format($sellerComissionValue, 2, ',', '.') }}</td>
            </tr>
            <tr class="" style="font-size: 13px;">
                <td class="collumn-left"><strong>Dia do Vencimento:</strong> {{ $order->seller_due_day }}</td>
                <td class="collumn-right"><strong>Quantidade de Parcelas:</strong>
                    {{ $order->seller_commission_installments_number }}</td>
            </tr>
        </tbody>
    </table>
    <br>
    <h1 style="margin-left: 1%;">Documentação - Entrada (Envio e Recebimento do Comprador)</h1>
    <table>
        <tbody>
            <tr class="bg-light" style="font-size: 13px;">
                <td class="collumn-left"><strong>Contratos:</strong> {{ $order->entry_contracts ? 'SIM' : 'NÃO' }}</td>
                <td class="collumn-right"><strong>NP:</strong> {{ $order->entry_promissory ? 'SIM' : 'NÃO' }}</td>
            </tr>
            <tr class="" style="font-size: 13px;">
                <td class="collumn-left"><strong>Cópia do Registro:</strong>
                    {{ $order->entry_register_copy ? 'SIM' : 'NÃO' }}</td>
                <td class="collumn-right"><strong>Parcela 01 do Negócio:</strong>
                    @if ($order->entry_first_parcel_business == 'ticket')
                        BOLETO
                    @endif
                    @if ($order->entry_first_parcel_business == 'deposit')
                        DEPÓSITO
                    @endif
                </td>
            </tr>
            <tr class="bg-light" style="font-size: 13px;">
                <td class="collumn-left"><strong>Parcela 01 da Comissão:</strong>
                    @if ($order->entry_first_parcel_comission == 'ticket')
                        BOLETO
                    @endif
                    @if ($order->entry_first_parcel_comission == 'deposit')
                        DEPÓSITO
                    @endif
                </td>
                <td class="collumn-right"><strong>Data de envio:</strong>
                    {{ date('d/m/Y', strtotime($order->entry_buyer_sending_documentation_date)) }}
                </td>
            </tr>
            <tr class="" style="font-size: 13px;">
                <td class="collumn-left"><strong>Forma de Envio:</strong>
                    {{ $order->entry_buyer_sending_documentation_way }}
                    @if ($order->entry_buyer_sending_documentation_way == 'email')
                        E-MAIL
                    @endif
                    @if ($order->entry_buyer_sending_documentation_way == 'whatsapp')
                        WHATSAPP
                    @endif
                    @if ($order->entry_buyer_sending_documentation_way == 'material')
                        FÍSICO
                    @endif
                </td>
                <td class="collumn-right"><strong>Retorno do Contrato:</strong>
                    {{ date('d/m/Y', strtotime($order->entry_contract_return_date)) }}
                </td>
            </tr>
        </tbody>
    </table>
    <br>
    <h1 style="margin-left: 1%;">Documentação - Saída (Envio para o Vendedor)</h1>
    <table>
        <tbody>
            <tr class="bg-light" style="font-size: 13px;">
                <td class="collumn-left"><strong>Contratos:</strong> {{ $order->output_contracts ? 'SIM' : 'NÃO' }}</td>
                <td class="collumn-right"><strong>NP:</strong> {{ $order->output_promissory ? 'SIM' : 'NÃO' }}</td>
            </tr>
            <tr class="" style="font-size: 13px;">
                <td class="collumn-left"><strong>Cópia do Registro:</strong>
                    {{ $order->output_register_copy ? 'SIM' : 'NÃO' }}</td>
                <td class="collumn-right"><strong>Data da Parcela 01:</strong>
                    {{ date('d/m/Y', strtotime($order->output_first_parcel_date)) }}
                </td>
            </tr>
            <tr class="bg-light" style="font-size: 13px;">
                <td class="collumn-left"><strong>Data de envio do processo físico:</strong>
                    {{ date('d/m/Y', strtotime($order->output_first_parcel_date)) }}
                </td>
                <td class="collumn-right"><strong>Forma de Envio:</strong>
                    {{ $order->entry_buyer_sending_documentation_way }}
                    @if ($order->entry_buyer_sending_documentation_way == 'email')
                        E-MAIL
                    @endif
                    @if ($order->entry_buyer_sending_documentation_way == 'whatsapp')
                        WHATSAPP
                    @endif
                    @if ($order->entry_buyer_sending_documentation_way == 'material')
                        FÍSICO
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
@endsection

@section('footer')
    @include('reports.footer')
@endsection
