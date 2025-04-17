@extends('reports.page')

@section('header')
    @include('reports.header-sales-map')
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
            font-size: 9px;
            overflow: hidden;
            padding: 3px 3px;
            word-break: normal;
        }

        .table th {
            /* border-color: black; */
            /* border-style: solid; */
            border-width: 1px;
            font-size: 9px;
            font-weight: normal;
            overflow: hidden;
            padding: 3px 3px;
            word-break: normal;
        }

        .table .table-header {
            background-color: #333333;
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
            background-color: #333333;
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
    </style>

    <div class="background">
        <br>
        <br>
        <br>

        <table class="table" style="table-layout: fixed; width: 100%;">
            <thead>
                <tr>
                    <th class="table-header text-white" style="width: 3%;">OS</th>
                    <th class="table-header text-white" style="width: 3%;">Lote</th>
                    <th class="table-header text-white">Animal</th>
                    <th class="table-header text-white">Vendedor</th>
                    <th class="table-header text-white">Comprador</th>
                    <th class="table-header text-white" style="width: 18%;">Cidade</th>
                    <th class="table-header text-white" style="width: 8%;">Parcela</th>
                    <th class="table-header text-white" style="width: 8%;">Faturamento</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td style="text-align: center;">{{ $order->number ?? '-' }}</td>
                        <td style="text-align: center;">{{ $order->batch ?? '-' }}</td>
                        <td>{{ strtoupper($order->animal->name) }}</td>
                        <td>{{ strtoupper($order->seller->name ?? 'SEM VENDA') }}</td>
                        <td>{{ strtoupper($order->buyer->name) ?? 'SEM VENDA' }}</td>
                        <td style="text-align: left;">
                            @if (isset($order->buyer->address->city) && isset($order->buyer->address->state))
                                {{ strtoupper($order->buyer->address->city . ' - ' . $order->buyer->address->state) }}
                            @else
                                {{ ' -' }}
                            @endif
                        </td>
                        <td style="text-align: center;">
                            {{ 'R$ ' .
                                ($order->multiplier > 0 ? number_format($order->gross_value / $order->multiplier, 2, ',', '.') : '0,00') }}
                        </td>
                        <td style="text-align: center;">R$ {{ number_format($order->gross_value ?? 0, 2, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if (count($orders) >= 30)
            <div class="break"></div>
        @endif
        <br>

        <table class="table" style="table-layout: fixed; width: 100%;">
            <thead>
                <tr>
                    <th colspan="6" class="table-header text-white">RESUMO</th>
                </tr>
                <tr>
                    <th class="table-header text-white">Faturamento Total</th>
                    <th class="table-header text-white">Lotes Vendidos</th>
                    <th class="table-header text-white">Lotes Sem Venda</th>
                    <th class="table-header text-white">Média Geral por Lote</th>
                    <th class="table-header text-white">Média das Fêmeas</th>
                    <th class="table-header text-white">Média dos Machos</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center"> R$ {{ number_format($totalRevenue, 2, ',', '.') }}</td>
                    <td style="text-align: center"> {{ $totalSaleOrders }}</td>
                    <td style="text-align: center"> {{ $totalOrders - $totalSaleOrders }}</td>
                    <td style="text-align: center"> R$ {{ number_format($avgRevenuePerBatch, 2, ',', '.') }}</td>
                    <td style="text-align: center"> R$ {{ number_format($avgFemaleRevenue, 2, ',', '.') }}</td>
                    <td style="text-align: center"> R$ {{ number_format($avgMaleRevenue, 2, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
        <br><br>
    @endsection

    @section('footer')
        @include('reports.footer')
    @endsection
