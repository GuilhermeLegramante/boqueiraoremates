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
                    <th class="table-header text-white" style="width: 5%;">Lote</th>
                    <th class="table-header text-white">Animal</th>
                    <th class="table-header text-white">Vendedor</th>
                    <th class="table-header text-white">Comprador</th>
                    <th class="table-header text-white">Cidade</th>
                    <th class="table-header text-white">Parcela (R$)</th>
                    <th class="table-header text-white">Faturamento (R$)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($animals as $animal)
                    <tr>
                        <td style="text-align: center;">{{ $animal->orders->first()->batch ?? '-' }}</td>
                        <td>{{ $animal->name }}</td>
                        <td>{{ $animal->orders->first()->seller->name ?? 'SEM VENDA' }}</td>
                        <td>{{ $animal->orders->first()->buyer->name ?? 'SEM VENDA' }}</td>
                        <td style="text-align: center;">
                            @if (isset($animal->orders->first()->buyer->address->city) && isset($animal->orders->first()->buyer->address->state))
                                {{ $animal->orders->first()->buyer->address->city . ' - ' . $animal->orders->first()->buyer->address->state }}
                            @else
                                {{ ' -' }}
                            @endif
                        </td>
                        <td style="text-align: right;">
                            {{ number_format($animal->orders->first()->parcel_value ?? 0, 2, ',', '.') }}</td>
                        <td style="text-align: right;">{{ number_format($animal->total_gross_value ?? 0, 2, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
                <!-- Resumo dentro do Tbody para evitar problemas no PDF -->
                <tr>
                    <td colspan="7" style="border-top: 2px solid #000; padding-top: 10px; text-align: center;">
                        <strong>RESUMO</strong>
                    </td>
                </tr>
                <tr>
                    <td colspan="4"><strong>Lotes Vendidos:</strong></td>
                    <td colspan="3"> {{ $totalOrders }}</td>
                </tr>
                <tr>
                    <td colspan="4"><strong>Média Geral (Todos os Animais):</strong></td>
                    <td colspan="3"> R$ {{ number_format($avgGeneral, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="4"><strong>Média de Faturamento (Machos):</strong></td>
                    <td colspan="3"> R$ {{ number_format($avgMaleRevenue, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="4"><strong>Média de Faturamento (Fêmeas):</strong></td>
                    <td colspan="3"> R$ {{ number_format($avgFemaleRevenue, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="4"><strong>Faturamento Total:</strong></td>
                    <td colspan="3"> R$ {{ number_format($totalRevenue, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="4"><strong>Média Geral por Lote:</strong></td>
                    <td colspan="3"> R$ {{ number_format($avgRevenuePerBatch, 2, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    @endsection

    @section('footer')
        @include('reports.footer')
    @endsection
