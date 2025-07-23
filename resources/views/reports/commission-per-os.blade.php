@extends('reports.page')

@section('header')
    @include('reports.header-landscape')
@endsection

@section('content')
    <style type="text/css">
        .table {
            border-collapse: collapse;
            border-spacing: 0;
            width: 100%;
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

        .background {
            background-image: url('https://sistema.boqueiraoremates.com/img/logo.png');
            /* background-size: ; */
            background-position: center;
            /* position: relative; */
            height: 100%;
            background-repeat: no-repeat;
            opacity: 0.06;

        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .text-bold {
            font-weight: bold;
        }

        .table-footer-text {
            background-color: #333333;
            color: #ffffff;
            font-weight: bold;
            text-align: right;
            vertical-align: top
        }
    </style>

    <div class="background">
        <table class="table">
            <thead>
                <tr class="table-header-text">
                    <th class="table-header">
                        N°
                    </th>
                    <th class="table-header">
                        Data da Negociação
                    </th>
                    <th class="table-header">
                        Evento
                    </th>
                    <th class="table-header">
                        Vendedor
                    </th>
                    <th class="table-header">
                        Comprador
                    </th>
                    <th class="table-header">
                        Animal
                    </th>
                    <th class="table-header">
                        Base de Cálculo
                    </th>
                    <th class="table-header">
                        % Compr.
                    </th>
                    <th class="table-header">
                        % Vend.
                    </th>
                    <th class="table-header">
                        Comissão Comprador
                    </th>
                    <th class="table-header">
                        Comissão Vendedor
                    </th>
                    <th class="table-header">
                        Comissão Total
                    </th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalCommissionBuyer = 0;
                    $totalCommissionSeller = 0;
                @endphp
                @foreach ($orders as $order)
                    @php
                        $totalCommissionBuyer += ($order->gross_value * $order->buyer_commission) / 100;
                        $totalCommissionSeller += ($order->gross_value * $order->seller_commission) / 100;
                    @endphp
                    <tr>
                        <td class="text-center">
                            {{ $order->number }}
                        </td>
                        <td class="text-center">
                            {{ date('d/m/Y', strtotime($order->base_date)) }}
                        </td>
                        <td>
                            {{ $order->event->name }}
                        </td>
                        <td>
                            {{ $order->seller->name }}
                        </td>
                        <td>
                            {{ $order->buyer->name }}
                        </td>
                        <td>
                            {{ $order->animal->name }}
                        </td>
                        <td class="text-right">
                            R$ {{ number_format($order->gross_value, 2, ',', '.') }}
                        </td>
                        <td class="text-center">
                            {{ $order->buyer_commission }}
                        </td>
                        <td class="text-center">
                            {{ $order->seller_commission }}
                        </td>
                        <td class="text-right">
                            R$ {{ number_format(($order->gross_value * $order->buyer_commission) / 100, 2, ',', '.') }}
                        </td>
                        <td class="text-right">
                            R$ {{ number_format(($order->gross_value * $order->seller_commission) / 100, 2, ',', '.') }}
                        </td>
                        <td class="text-right">
                            R$
                            {{ number_format(($order->gross_value * $order->buyer_commission) / 100 + ($order->gross_value * $order->seller_commission) / 100, 2, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="9" class="table-footer-text">
                        Total
                    </th>
                    <th class="table-footer-text">
                        R$ {{ number_format($totalCommissionBuyer, 2, ',', '.') }}
                    </th>
                    <th class="table-footer-text">
                        R$ {{ number_format($totalCommissionSeller, 2, ',', '.') }}
                    </th>
                    <th class="table-footer-text">
                        R$ {{ number_format($totalCommissionBuyer + $totalCommissionSeller, 2, ',', '.') }}
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>
@endsection

@section('footer')
    @include('reports.footer')
@endsection
