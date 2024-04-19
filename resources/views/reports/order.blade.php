@extends('reports.page')

@section('header')
    @include('reports.header-os')
@endsection

@section('content')
    <style type="text/css">
        .parcels-table {
            border-collapse: collapse;
            width: 100%;
        }

        .parcels-table tr:nth-child(even) {
            background-color: #f0f0f0;
        }

        .parcels-table td {
            border-width: 1px;
            font-size: 9px;
            overflow: hidden;
            padding: 3px 3px;
            word-break: normal;
        }

        .parcels-table th {
            border-width: 1px;
            font-size: 9px;
            font-weight: normal;
            overflow: hidden;
            padding: 3px 3px;
            word-break: normal;
            text-align: left;
            background-color: #333333;
            color: #f0f0f0;
        }


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
    </style>

    <div class="background">
        @include('reports.partials.order-main-content')

        @include('reports.partials.order-buyer-content')

        <div class="break"></div>

        @include('reports.partials.order-seller-content')

        @include('reports.partials.order-entry-data-content')

    </div>
@endsection

@section('footer')
    @include('reports.footer')
@endsection
