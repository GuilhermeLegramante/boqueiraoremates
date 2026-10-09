<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'RELATÓRIO' }}</title>
    <style>
        @page {
            margin: 8mm;
            font-family: 'Gill Sans', 'DejaVu Sans', Calibri, sans-serif;
            font-size: 9px;
        }

        body {
            margin: 0;
            padding: 0;
            color: #000;
            line-height: 1.2;
        }

        /* Estrutura de Páginas */
        .page-section {
            width: 100%;
        }

        .page-break {
            page-break-before: always;
            break-before: page;
        }

        .no-break {
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }

        /* Utilitários e Tabelas */
        .w-100 {
            width: 100%;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-justify {
            text-align: justify;
        }

        .font-bold {
            font-weight: bold;
        }

        .text-uppercase {
            text-transform: uppercase;
        }

        .label {
            font-weight: bold;
        }

        .section-header {
            background-color: #000;
            color: #fff;
            text-align: center;
            font-weight: bold;
            font-size: 10px;
            padding: 2px 0;
            margin: 4px 0 3px 0;
            text-transform: uppercase;
        }

        .data-table,
        .info-table {
            width: 100% !important;
            box-sizing: border-box;
            border-collapse: collapse;
            margin-bottom: 4px;
        }

        .data-table td,
        .info-table td {
            border: 1px solid #000;
            padding: 2px 4px;
            font-size: 9px;
            vertical-align: top;
        }

        /* Estilos do Contrato */
        .contract {
            font-size: 10px;
            line-height: 1.35;
        }

        .contract-header {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        .contract-header td {
            vertical-align: middle;
        }

        .event-logo {
            max-width: 220px;
            max-height: 70px;
        }

        .boqueirao-logo {
            max-width: 180px;
            max-height: 70px;
        }

        .event-title-center {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            color: #000;
            margin-bottom: 3px;
        }

        .contract-city {
            text-align: center;
            font-size: 15px;
            margin-top: 2px;
        }

        .title-row-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .title-row-table td {
            vertical-align: bottom;
        }

        .contract-title {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            margin-top: 8px;
            margin-bottom: 12px;
        }

        .section-title {
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .contract-text {
            text-align: justify;
            font-size: 9.5px;
            line-height: 1.4;
            margin: 0 0 7px 0;
        }

        .payment-text {
            text-align: justify;
            font-size: 9.5px;
            line-height: 1.4;
            margin: 6px 0 8px 0;
            font-weight: bold;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .signature-table td {
            width: 50%;
            text-align: center;
            padding: 15px 15px 0 15px;
        }

        .signature-line {
            border-top: 1px solid #000;
            width: 85%;
            margin: 0 auto 4px auto;
        }

        .signature-name {
            font-size: 9px;
            font-weight: bold;
        }

        .signature-role {
            font-size: 9px;
        }

        .contract-page {
            border: 1px solid #000;
            padding: 12px;
        }

        /* Estilos da Promissória */
        .promissory-container {
            border: 1px solid #000;
            padding: 8px;
        }

        .promissory-header {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }

        .promissory-header td {
            vertical-align: middle;
        }

        .promissory-title {
            font-size: 15px;
            font-weight: bold;
            font-style: italic;
            text-decoration: underline;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .parcels-wrapper {
            width: 100%;
            margin-top: 4px;
            margin-bottom: 6px;
        }

        .parcels-columns-table {
            width: 100%;
            border-collapse: collapse;
        }

        .parcels-columns-table>tbody>tr>td {
            vertical-align: top;
            padding: 0 2px;
        }

        .parcels-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
        }

        .parcels-table th,
        .parcels-table td {
            border: 1px solid #000;
            padding: 1.5px 2px;
            font-size: 8px;
            text-align: center;
        }

        .parcels-table th {
            background-color: #f0f0f0;
        }

        .clause-text {
            font-size: 8px;
            line-height: 1.25;
            text-align: justify;
            margin-bottom: 3px;
        }

        .city-date {
            text-align: right;
            font-size: 9.5px;
            font-weight: bold;
            margin-top: 6px;
            margin-bottom: 10px;
        }

        .promissory-signatures {
            width: 60%;
            margin: 0 auto;
            text-align: center;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .promissory-signature-box {
            margin-bottom: 10px;
        }

        /* Estilos do Regulamento */
        .table-header {
            width: 100%;
            margin-bottom: 5px;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
        }

        .header-title {
            text-align: center;
            font-weight: bold;
        }

        .header-title h2 {
            margin: 0;
            font-size: 14px;
            text-transform: uppercase;
        }

        .header-title h3 {
            margin: 2px 0 0 0;
            font-size: 12px;
            text-transform: uppercase;
        }

        .rules-list {
            width: 100%;
            border-collapse: collapse;
        }

        .rules-list td {
            vertical-align: top;
            padding: 1.5px 0;
            text-align: justify;
        }

        .rules-list td.num {
            width: 30px;
            font-weight: bold;
            white-space: nowrap;
        }

        .highlight-red {
            color: #d32f2f;
            font-weight: bold;
        }

        .decl-box {
            margin-top: 10px;
            border-top: 1px dashed #000;
            padding-top: 8px;
            font-size: 9.5px;
            line-height: 1.3;
        }

        .fill-line {
            display: inline-block;
            border-bottom: 1px solid #000;
            font-weight: bold;
            padding: 0 4px;
            text-align: center;
        }

        .signatures-container {
            width: 100%;
            margin-top: 20px;
            text-align: center;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .signature-block {
            width: 320px;
            margin: 0 auto 12px auto;
            text-align: center;
        }

        .signature-line-item {
            border-top: 1px solid #000;
            padding-top: 3px;
            font-size: 9px;
            font-weight: bold;
            line-height: 1.2;
            text-transform: uppercase;
        }

        /* Marca d'água para Pré-visualização */
        .watermark {
            position: fixed;
            top: 35%;
            left: 5%;
            width: 90%;
            text-align: center;
            font-size: 52px;
            font-weight: bold;
            color: rgba(200, 0, 0, 0.18);
            text-transform: uppercase;
            transform: rotate(-35deg);
            transform-origin: center center;
            z-index: 9999;
            pointer-events: none;
            letter-spacing: 4px;
        }
    </style>
</head>

<body>

    @if (!empty($isPreview) && $isPreview)
        <div class="watermark">PRÉ-VISUALIZAÇÃO</div>
    @endif

    @yield('content')

    {{-- @include('reports.footer') --}}
</body>

</html>
