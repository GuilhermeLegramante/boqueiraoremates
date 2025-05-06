@extends('reports.page')

@section('header')
    @include('reports.header-seller-statement')
@endsection

@section('content')
    <style type="text/css">
        .table {
            border-collapse: collapse;
            border-spacing: 0;
        }

        .table tr:nth-child(even) {
            background-color: #f0f0f0;
        }

        .table td {
            /* border-color: black; */
            /* border-style: solid; */
            border-width: 1px;
            font-size: 8px;
            overflow: hidden;
            padding: 3px 3px;
            word-break: normal;
        }

        .table th {
            /* border-color: black; */
            /* border-style: solid; */
            border-width: 1px;
            font-size: 8px;
            font-weight: normal;
            overflow: hidden;
            padding: 3px 3px;
            word-break: normal;
        }

        .table .table-header {
            background-color: black;
            border-color: inherit;
            font-weight: bold;
            text-align: center;
            vertical-align: top
        }

        .table .table-llyw {
            background-color: #c0c0c0;
            border-color: inherit;
            text-align: left;
            vertical-align: top
        }

        .table .table-header-text {
            background-color: #3333332d;
            /* border-color: inherit; */
            color: #ffffff;
            font-weight: bold;
            text-align: center;
            vertical-align: top
        }

        .table .table-0pky {
            /* border-color: inherit; */
            text-align: left;
            vertical-align: top
        }

        .table .table-fymr {
            /* border-color: inherit; */
            font-weight: bold;
            text-align: left;
            vertical-align: top
        }

        .container {
            display: table;
            width: 100%;
            /* height: 100%; */
        }

        .section {
            display: table-cell;
            width: 25%;
        }

        .background {
            background-image: url('https://boqueiraoremates.com/v2/public/img/logo.png');
            /* background-size: ; */
            background-position: center;
            /* position: relative; */
            height: 100%;
            background-repeat: no-repeat;
            opacity: 0.06;

        }

        .break {
            page-break-before: always;
        }

        fieldset {
            overflow: hidden
        }

        .some-class {
            float: left;
            clear: none;
        }

        label {
            float: left;
            clear: none;
            display: block;
            padding: 0px 1em 0px 8px;
        }

        input[type=radio],
        input.radio {
            float: left;
            clear: none;
            margin: 2px 0 0 2px;
        }

        .summary {
            margin-top: 20px;
        }

        .text-white {
            color: #f0f0f0;
        }

        .text-left {
            text-align: left
        }
    </style>

    <div class="background">

        <table style="margin-bottom: -1%;">
            <tr>
                <th>
                    <h2>Dados do Vendedor</h2>
                </th>
            </tr>
        </table>
        <table class="table" style="width: 100%; font-size: 14px;">
            <tbody>
                <tr>
                    <td style="width: 10%; text-align: left;"><strong>Nome:</strong></td>
                    <td>{{ $seller->name ?? '-' }}</td>

                    <td style="width: 20%; text-align: left;"><strong>Estabelecimento:</strong></td>
                    <td>{{ $seller->establishment ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="text-align: left;"><strong>Cidade:</strong></td>
                    <td>
                        @if (isset($seller->address->city) && isset($seller->address->state))
                            {{ $seller->address->city }} - {{ $seller->address->state }}
                        @else
                            -
                        @endif
                    </td>

                    <td style="text-align: left;"><strong>Multiplicador:</strong></td>
                    <td>{{ $event->multiplier ?? '-' }}</td>
                </tr>
                {{-- <tr>
                    <td style="text-align: left;"><strong>Parcelas:</strong></td>
                    <td colspan="3">
                        {{ $event->parcels ?? '-' }}
                    </td>
                </tr> --}}
            </tbody>
        </table>
        <br>

        @php
            $grossValueTotal = $orders->sum('gross_value');
            $receivedTotal = $orders->sum('receipt'); // ajuste aqui se tiver um campo como $order->received_value
        @endphp

        <table style="margin-bottom: -1%;">
            <tr>
                <th>
                    <h2>Proventos 01</h2>
                </th>
            </tr>
        </table>
        <table class="table" style="table-layout: fixed; width: 100%; font-si">
            <thead>
                <tr>
                    <th class="table-header text-white" style="width: 3%;">OS</th>
                    <th class="table-header text-white" style="width: 3%;">Lote</th>
                    <th class="table-header text-white" style="width: 19%;">Animal</th>
                    <th class="table-header text-white" style="width: 19%;">Comprador</th>
                    <th class="table-header text-white" style="width: 19%;">Cidade</th>
                    <th class="table-header text-white" style="width: 8%;">Parcela</th>
                    <th class="table-header text-white" style="width: 8%;">Faturamento</th>
                    <th class="table-header text-white" style="width: 8%;">Condição</th>
                    <th class="table-header text-white" style="width: 8%;">Recebido</th>
                    <th class="table-header text-white" style="width: 19%;">Informação</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        @php $hasBuyer = $order->buyer && $order->buyer->name; @endphp

                        <td style="text-align: center;">{{ $order->number ?? '-' }}</td>
                        <td style="text-align: center;">{{ $order->batch ?? '-' }}</td>
                        <td>{{ strtoupper($order->animal->name) }}</td>

                        <td>
                            @if ($hasBuyer)
                                {{ strtoupper($order->buyer->name) }}
                            @else
                                {!! '<span style="color: red; font-weight: bold; font-family: DejaVu Sans;">SEM VENDA</span>' !!}
                            @endif
                        </td>

                        <td style="text-align: left;">
                            @if (isset($order->buyer->address->city) && isset($order->buyer->address->state))
                                {{ strtoupper($order->buyer->address->city . ' - ' . $order->buyer->address->state) }}
                            @else
                                <span style="color: red;"> -</span>
                            @endif
                        </td>

                        @include('reports.partials.td-money', [
                            'money_value' => $order->multiplier > 0 ? $order->gross_value / $order->multiplier : 0,
                            'td_css' => $hasBuyer ? '' : 'color: red;',
                        ])

                        @include('reports.partials.td-money', [
                            'money_value' => $order->gross_value,
                            'td_css' => $hasBuyer ? '' : 'color: red;',
                        ])

                        <td style="text-align: center; {{ !$hasBuyer ? 'color: red;' : '' }}">
                            {{ $order->paymentWay->name }}
                        </td>

                        @include('reports.partials.td-money', [
                            'money_value' => $order->receipt,
                            'td_css' => $hasBuyer ? '' : 'color: red;',
                        ])

                        <td style="text-align: left; {{ !$hasBuyer ? 'color: red;' : '' }}">
                            {{ $order->map_note }}
                        </td>
                    </tr>
                @endforeach
            </tbody>

            <tfoot>
                <tr style="font-weight: bold; background-color: #f0f0f0;">
                    <td colspan="6"></td>
                    {{-- <td style="text-align: right;">
                        R$ {{ number_format($grossValueTotal, 2, ',', '.') }}
                    </td> --}}
                    @include('reports.partials.td-money', ['money_value' => $grossValueTotal])

                    <td></td>
                    {{-- <td style="text-align: right;">
                        R$ {{ number_format($receivedTotal, 2, ',', '.') }}
                    </td> --}}
                    @include('reports.partials.td-money', ['money_value' => $receivedTotal])

                    <td style="text-align: center;">TOTAL</td>
                </tr>
            </tfoot>
        </table>

        <table style="margin-bottom: -1%;">
            <tr>
                <th>
                    <h2>Proventos 02</h2>
                </th>
            </tr>
        </table>
        <table class="table" style="table-layout: fixed; width: 100%; font-size: 14px;">
            <thead>
                <tr>
                    <th class="table-header text-white">Descrição</th>
                    <th class="table-header text-white" style="width: 10%; text-align: right;">Valor</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($earnings as $earning)
                    <tr>
                        <td style="text-align: right;">{{ $earning->description }}</td>
                        {{-- <td style="text-align: right;">
                            R$ {{ number_format($earning->amount, 2, ',', '.') }}
                        </td> --}}
                        @include('reports.partials.td-money', ['money_value' => $earning->amount])
                    </tr>
                @endforeach
                <tr style="font-weight: bold; background-color: #f0f0f0;">
                    <td class="text-end">Total de Proventos</td>
                    {{-- <td style="text-align: right;">
                        R$ {{ number_format($earnings->sum('amount'), 2, ',', '.') }}
                    </td> --}}
                    @include('reports.partials.td-money', ['money_value' => $earnings->sum('amount')])
                </tr>
            </tbody>
        </table>

        <table style="margin-bottom: -1%;">
            <tr>
                <th>
                    <h2>Descontos</h2>
                </th>
            </tr>
        </table>
        <table class="table" style="table-layout: fixed; width: 100%; font-size: 14px;">
            <thead>
                <tr>
                    <th class="table-header text-white">Descrição</th>
                    <th class="table-header text-white" style="width: 10%; text-align: right;">Valor</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($discounts as $discount)
                    <tr>
                        <td style="text-align: right;">{{ $discount->description }}</td>
                        {{-- <td style="text-align: right;">
                            R$ {{ number_format($discount->amount, 2, ',', '.') }}
                        </td> --}}
                        @include('reports.partials.td-money', ['money_value' => $discount->amount])
                    </tr>
                @endforeach
                <tr style="font-weight: bold; background-color: #f0f0f0;">
                    <td class="text-end">Total de Descontos</td>
                    {{-- <td style="text-align: right;">
                        R$ {{ number_format($discounts->sum('amount'), 2, ',', '.') }}
                    </td> --}}
                    @include('reports.partials.td-money', ['money_value' => $discounts->sum('amount')])
                </tr>
            </tbody>
        </table>


        {{-- @if (count($orders) >= 30)
            <div class="break"></div>
        @endif --}}
        <br>
        @php
            $totalOrders = $orders->sum('receipt');
            $totalEarnings = $earnings->sum('amount');
            $totalDiscounts = $discounts->sum('amount');

            $totalEarnings = $totalOrders + $totalEarnings;
            $balance = $totalEarnings - $totalDiscounts;
        @endphp

        {{-- <table class="table" style="table-layout: fixed; width: 100%;">
            <thead>
                <tr>
                    <th colspan="3" class="table-header text-white">RESUMO</th>
                </tr>
                <tr>
                    <td style="text-align: right;"><strong>TOTAL DE PROVENTOS</strong></td>
                    <td style="text-align: right;"><strong>TOTAL DE DESCONTOS</strong></td>
                    <td style="text-align: right;">
                        <strong>
                            {{ $balance < 0 ? 'VALOR A RECEBER' : 'VALOR A ENVIAR' }}
                        </strong>
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    @include('reports.partials.td-money', ['money_value' => $totalEarnings])
                    @include('reports.partials.td-money', ['money_value' => $totalDiscounts])
                    @include('reports.partials.td-money', ['money_value' => abs($balance)])
                </tr>
            </tbody>
        </table> --}}


        <table class="table" style="table-layout: fixed; width: 100%;">
            <thead>
                <tr>
                    <th colspan="2" class="table-header text-white">RESUMO</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: right; width: 90%;"><strong>TOTAL DE PROVENTOS (01 + 02)</strong></td>
                    @include('reports.partials.td-money', ['money_value' => $totalEarnings])
                </tr>
                <tr>
                    <td style="text-align: right; width: 90%; color: red;"><strong>TOTAL DE DESCONTOS</strong></td>
                    @include('reports.partials.td-money', [
                        'money_value' => $totalDiscounts,
                        'td_css' => 'color: red;',
                    ])
                </tr>
                <tr>
                    <td style="text-align: right; width: 90%;"><strong>
                            {{ $balance < 0 ? 'VALOR A RECEBER' : 'VALOR A ENVIAR' }}
                        </strong>
                    </td>
                    @include('reports.partials.td-money', ['money_value' => abs($balance)])

                </tr>
            </tbody>
        </table>

        <br><br>
    @endsection

    @section('footer')
        @include('reports.footer')
    @endsection
