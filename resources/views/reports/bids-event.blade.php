<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style type="text/css">
        @page {
            margin: 1.5cm;
        }

        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }

        /* Estilos da Tabela Padrão Boqueirão */
        .table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-top: 10px;
        }

        .table tr:nth-child(even) {
            background-color: #f0f0f0;
        }

        .table td {
            border: 1px solid #ccc;
            font-size: 9px;
            padding: 5px;
            word-break: normal;
        }

        .table th {
            border: 1px solid #333;
            font-size: 9px;
            padding: 5px;
            background-color: #333333;
            color: #ffffff;
            font-weight: bold;
            text-align: center;
        }

        /* Cabeçalho Fixo */
        .header-container {
            width: 100%;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        /* Marca d'água / Background */
        .background-wrapper {
            position: relative;
            width: 100%;
        }

        .watermark {
            position: absolute;
            top: 20%;
            left: 20%;
            width: 60%;
            opacity: 0.06;
            z-index: -1;
        }

        /* Utilitários */
        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .upper {
            text-transform: uppercase;
        }

        .bold {
            font-weight: bold;
        }

        /* Rodapé */
        .footer {
            position: fixed;
            bottom: -10px;
            width: 100%;
            font-size: 8px;
            text-align: center;
            border-top: 0.5px solid #ccc;
            padding-top: 5px;
        }
    </style>
</head>

<body>

    <div class="header-container">
        <table class="header-table">
            <tr>
                <td style="width: 15%; border: none;">
                    <img src="{{ public_path('img/logo.png') }}" style="width: 80px;">
                </td>
                <td style="width: 70%; text-align: center; border: none;">
                    <span style="font-size: 18px; font-weight: bold;">{{ $title }}</span><br>
                    <span style="font-size: 14px; font-weight: bold;">{{ $event->name }}</span><br>
                    <span style="font-size: 9px;">Boqueirão Remates e Negócios Rurais</span>
                </td>
                <td style="width: 15%; text-align: right; border: none;">
                    <span style="font-size: 8px;">
                        <strong>Emitido em:</strong><br>
                        {{ date('d/m/Y') }} às {{ date('H:i:s') }}
                    </span>
                </td>
            </tr>
        </table>
    </div>

    <div class="background-wrapper">
        <img src="{{ public_path('img/logo.png') }}" class="watermark">

        <table class="table">
            <thead>
                <tr>
                    <th style="width: 8%;">CÓDIGO</th>
                    <th style="width: 15%;">DATA/HORA</th>
                    <th>NOME DO CLIENTE</th>
                    <th style="width: 8%;">LOTE</th>
                    <th style="width: 18%;">VALOR DO LANCE</th>
                </tr>
            </thead>
            <tbody>
                @php $totalGeral = 0; @endphp
                @foreach ($bids as $bid)
                    <tr>
                        <td class="text-center">#{{ str_pad($bid->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td class="text-center">{{ $bid->created_at->format('d/m/Y H:i') }}</td>
                        <td class="upper">{{ $bid->user->name }}</td>
                        <td class="text-center">{{ $bid->lot_number }}</td>
                        <td class="text-right">
                            <table style="width: 100%; border: none;">
                                <tr style="background: transparent;">
                                    <td style="border: none; padding: 0; width: 20px;">R$</td>
                                    <td style="border: none; padding: 0; text-align: right;">
                                        {{ number_format($bid->amount, 2, ',', '.') }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    @php $totalGeral += $bid->amount; @endphp
                @endforeach
            </tbody>
        </table>

        <br>

        <table class="table" style="width: 40%; margin-left: auto;">
            <tr>
                <td class="bold" style="background-color: #f2f2f2;">QUANTIDADE DE LANCES</td>
                <td class="text-center">{{ $bids->count() }}</td>
            </tr>
            <tr>
                <td class="bold" style="background-color: #f2f2f2;">VALOR TOTAL ACUMULADO</td>
                <td class="text-right bold">R$ {{ number_format($totalGeral, 2, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Boqueirão Remates - Sistema de Gestão de Leilões - www.boqueiraoremates.com.br
    </div>

</body>

</html>
