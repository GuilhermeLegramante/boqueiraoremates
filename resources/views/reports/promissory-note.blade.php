<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Nota Promissória - Única</title>
    <style type="text/css">
        @page {
            margin: 5mm;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            font-size: 10px;
        }

        body {
            margin: 0;
            padding: 0;
        }

        .page-container {
            border: 1px solid #000;
            padding: 10px;
        }

        /* Header */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        .header-table td {
            vertical-align: middle;
        }

        .event-logo {
            max-width: 180px;
            max-height: 60px;
        }

        .header-title-container {
            text-align: right;
        }

        .promissory-title {
            font-size: 16px;
            font-weight: bold;
            font-style: italic;
            text-decoration: underline;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .header-info {
            font-size: 10px;
            line-height: 1.3;
        }

        .header-info span.label {
            font-weight: bold;
        }

        /* Section Banners */
        .section-header {
            background-color: #000;
            color: #fff;
            text-align: center;
            font-weight: bold;
            font-size: 10px;
            padding: 2px 0;
            margin-top: 6px;
            margin-bottom: 4px;
            text-transform: UPPERCASE;
        }

        /* Data Tables */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }

        .info-table td {
            padding: 2px 4px;
            font-size: 9.5px;
            vertical-align: top;
        }

        .label {
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* Parcels Container */
        .parcels-wrapper {
            width: 100%;
            margin-top: 6px;
            margin-bottom: 8px;
        }

        .parcels-columns-table {
            width: 100%;
            border-collapse: collapse;
        }

        .parcels-columns-table>tbody>tr>td {
            vertical-align: top;
            padding: 0 4px;
        }

        .parcels-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
        }

        .parcels-table th {
            border: 1px solid #000;
            background-color: #f0f0f0;
            padding: 2px;
            font-size: 8.5px;
            text-align: center;
        }

        .parcels-table td {
            border: 1px solid #000;
            padding: 2px;
            font-size: 8.5px;
            text-align: center;
        }

        /* Footer Clauses & Signatures */
        .clause-text {
            font-size: 8.5px;
            line-height: 1.35;
            text-align: justify;
            margin-bottom: 5px;
        }

        .city-date {
            text-align: center;
            font-size: 10px;
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        .signature-container {
            width: 60%;
            margin: 0 auto;
            text-align: center;
        }

        .signature-box {
            margin-bottom: 15px;
        }

        .signature-line {
            border-top: 1px solid #000;
            width: 100%;
            margin: 0 auto 3px auto;
        }

        .signature-name {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .signature-doc {
            font-size: 8.5px;
            text-transform: uppercase;
        }
    </style>
</head>

<body>
    <div class="page-container">
        {{-- CABEÇALHO --}}
        <table class="header-table">
            <tr>
                <td style="width: 40%;">
                    @if (!empty($eventBanner) && file_exists($eventBanner))
                        @php
                            $type = pathinfo($eventBanner, PATHINFO_EXTENSION);
                            $data = file_get_contents($eventBanner);
                            $base64Banner = 'data:image/' . $type . ';base64,' . base64_encode($data);
                        @endphp
                        <img src="{{ $base64Banner }}" class="event-logo">
                    @endif
                </td>
                <td style="width: 60%;" class="header-title-container">
                    <div class="promissory-title">NOTA PROMISSÓRIA - ÚNICA</div>
                    <div class="header-info">
                        <div><span class="label">Nota do:</span>
                            {{ mb_strtoupper($seller->establishment ?? ($event->name ?? ''), 'UTF-8') }}</div>
                        <div><span class="label">NP Nº:</span> {{ $order->number }}</div>
                        <div><span class="label">Escritório/Leiloeiro:</span> {{ $event->auctioneer ?? '' }}</div>
                    </div>
    </div>
    </td>
    </tr>
    </table>

    {{-- VENDEDOR --}}
    <div class="section-header">VENDEDOR</div>
    <table class="info-table">
        <tr>
            <td style="width: 60%;"><span class="label">Nome:</span> {{ $seller->name }}</td>
            <td style="width: 40%;"><span class="label">CPF/CNPJ:</span> {{ $seller->cpf_cnpj }}</td>
        </tr>
        <tr>
            <td><span class="label">Ender.:</span> {{ $seller->address->street ?? '' }}</td>
            <td><span class="label">Bairro:</span> {{ $seller->address->district ?? '' }}</td>
        </tr>
        <tr>
            <td><span class="label">Cidade/UF:</span> {{ $seller->address->city ?? '' }} -
                {{ $seller->address->state ?? '' }}</td>
            <td><span class="label">Cep:</span> {{ $seller->address->postal_code ?? '' }}</td>
        </tr>
        <tr>
            <td><span class="label">E-mail:</span> {{ $seller->email ?? '' }}</td>
            <td><span class="label">Contato:</span> {{ $seller->phone ?? '' }}</td>
        </tr>
    </table>

    {{-- COMPRADOR --}}
    <div class="section-header">COMPRADOR</div>
    <table class="info-table">
        <tr>
            <td style="width: 60%;"><span class="label">Nome:</span> {{ $buyer->name }}</td>
            <td style="width: 40%;"><span class="label">CPF/CNPJ:</span> {{ $buyer->cpf_cnpj }}</td>
        </tr>
        <tr>
            <td><span class="label">Ender.:</span> {{ $buyer->address->street ?? '' }}</td>
            <td><span class="label">Bairro:</span> {{ $buyer->address->district ?? '' }}</td>
        </tr>
        <tr>
            <td><span class="label">Cidade/UF:</span> {{ $buyer->address->city ?? '' }} -
                {{ $buyer->address->state ?? '' }}</td>
            <td><span class="label">Cep:</span> {{ $buyer->address->postal_code ?? '' }}</td>
        </tr>
        <tr>
            <td><span class="label">E-mail:</span> {{ $buyer->email ?? '' }}</td>
            <td><span class="label">Contato:</span> {{ $buyer->phone ?? '' }}</td>
        </tr>
    </table>

    {{-- ESPECIFICAÇÃO DOS PRODUTOS / LOTES --}}
    <div class="section-header">ESPECIFICAÇÃO DOS PRODUTOS/LOTES</div>
    @php
        $objetoTexto = match ($order->sale_type) {
            'cota' => 'Cota ' .
                ($order->sale_type_percentage ? $order->sale_type_percentage . '%' : '') .
                ' do animal equino com as informações a seguir descritas:',
            'direito_de_uso' => 'Direito de uso (' .
                ($order->sale_type_percentage ? $order->sale_type_percentage . '%' : '') .
                ') do animal equino com as informações a seguir descritas:',
            'cobertura' => 'Cobertura (' .
                ($order->sale_type_quantity ? $order->sale_type_quantity . ' unidades' : '') .
                ') do animal equino com as informações a seguir descritas:',
            default => '01 Animal Equino, com as informações a seguir descritas:',
        };

        $breedName = mb_strtoupper($animal->breed->name ?? '', 'UTF-8');
        $isQuartoDeMilha = $breedName === 'QUARTO DE MILHA';
    @endphp

    <div style="text-align: center; font-weight: bold; font-size: 9.5px; margin: 3px 0;">
        {{ $objetoTexto }}
    </div>

    <table class="info-table">
        <tr>
            <td><span class="label">Nº Lote(s):</span>
                {{ $order->animalEvent->lot_number ?? ($order->batch ?? '') }}</td>
            <td colspan="2"><span class="label">Nome(s):</span>
                {{ $order->animalEvent->name ?? ($animal->name ?? '') }}</td>
        </tr>
        <tr>
            <td><span class="label">Raça:</span> {{ $animal->breed->name ?? '' }}</td>
            @if ($isQuartoDeMilha)
                <td><span class="label">Registro:</span> {{ $animal->register ?? '' }}</td>
                <td>
                    <span class="label">Grau de Sangue:</span>
                    @if ($animal->blood_level === 'pure')
                        Puro
                    @elseif ($animal->blood_level === 'mixed')
                        Mestiço {{ $animal->blood_percentual ? '(' . $animal->blood_percentual . '%)' : '' }}
                    @endif
                </td>
            @else
                <td><span class="label">RP(s):</span> {{ $animal->rb ?? '' }}</td>
                <td><span class="label">SBB(s):</span> {{ $animal->sbb ?? '' }}</td>
            @endif
        </tr>
        <tr>
            <td><span class="label">Gênero:</span>
                {{ $animal->gender === 'male' ? 'MACHO' : ($animal->gender === 'female' ? 'FÊMEA' : '') }}</td>
            <td colspan="2"><span class="label">Pelagem (ns):</span> {{ $animal->coat->name ?? '' }}</td>
        </tr>
    </table>

    {{-- ACERTO FINANCEIRO --}}
    <div class="section-header">ACERTO FINANCEIRO</div>
    <table class="info-table">
        <tr>
            <td><span class="label">Dta da Compra:</span>
                {{ \Carbon\Carbon::parse($order->base_date)->format('d/m/Y') }}</td>
            <td><span class="label">Cond.:</span> {{ $order->paymentWay->name ?? '' }}</td>
            <td class="text-right"><span class="label">/{{ count($order->parcels ?? []) }} PARCELAS</span></td>
        </tr>
        <tr>
            <td><span class="label">Vlr Bruto:</span> R$ {{ number_format($order->gross_value, 2, ',', '.') }}
            </td>
            <td><span class="label">Desc.:</span>
                {{ number_format($order->discount_percentage ?? 0, 2, ',', '.') }}%</td>
            <td class="text-right"><span class="label">Vlr Líquido:</span> R$
                {{ number_format($order->net_value ?? $order->gross_value, 2, ',', '.') }}</td>
        </tr>
        @if (($order->first_parcel_value ?? 0) > 0)
            <tr>
                <td colspan="3"><span class="label">Entrada de:</span> R$
                    {{ number_format($order->first_parcel_value, 2, ',', '.') }} (+) as parcelas e seus respectivos
                    vencimentos, descritos a seguir:</td>
            </tr>
        @endif
    </table>

    {{-- TABELA DE PARCELAS --}}
    <div class="parcels-wrapper">
        <table class="parcels-columns-table">
            <tr>
                @php
                    $parcelsArray = is_array($order->parcels) ? $order->parcels : $order->parcels->toArray();
                    $totalParcels = count($parcelsArray);
                    // Define quantas colunas exibir com base na quantidade de parcelas (1 a 4 colunas)
                    $numColumns = max(1, min(4, ceil($totalParcels / 15)));
                @endphp

                @for ($col = 0; $col < $numColumns; $col++)
                    <td style="width: {{ 100 / $numColumns }}%;">
                        <table class="parcels-table">
                            <thead>
                                <tr>
                                    <th>Ord.</th>
                                    <th>Dt. Venc.</th>
                                    <th>Valor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = $col * 15; $i < ($col + 1) * 15; $i++)
                                    <tr>
                                        @if (isset($parcelsArray[$i]))
                                            <td>{{ data_get($parcelsArray[$i], 'number', $i + 1) }}
                                                /{{ $totalParcels }}</td>
                                            <td>{{ date('d/m/Y', strtotime(data_get($parcelsArray[$i], 'date'))) }}
                                            </td>
                                            <td class="text-right">R$
                                                {{ number_format(data_get($parcelsArray[$i], 'value', 0), 2, ',', '.') }}
                                            </td>
                                        @else
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        @endif
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </td>
                @endfor
            </tr>
        </table>
    </div>

    {{-- CLAÚSULAS --}}
    <p class="clause-text">
        Esta nota promissória pode ser paga a qualquer momento, antes do dia do vencimento, ao todo ou em parte, sem
        prêmio ou penalização.
    </p>
    <p class="clause-text">
        Se o credor sair vitorioso em uma ação judicial para cobrar esta nota, o devedor pagará os custos de
        tribunal, custos de agencia de cobrança e honorários advocatícios no valor estabelecido pelo tribunal.
    </p>
    <p class="clause-text">
        Fica eleito o Foro da Comarca de Uruguaiana (RS) para dirimir qualquer questão atinente ao presente
        contrato.
    </p>
    <p class="clause-text">
        Com as assinaturas, as partes ficam de acordo com as disposições gerais que se encontram acima descritas,
        fazendo parte integral do presente instrumento.
    </p>

    {{-- CIDADE E DATA --}}
    <div class="city-date">
        {{ $seller->address->city ?? 'Uruguaiana' }} - {{ $seller->address->state ?? 'RS' }},
        {{ \Carbon\Carbon::parse($contractDate)->locale('pt_BR')->translatedFormat('d \d\e F \d\e Y') }} </div>

    {{-- ASSINATURAS --}}
    <div class="signature-container">
        <div class="signature-box">
            <div class="signature-line"></div>
            <div class="signature-name">{{ $buyer->name }}</div>
            <div class="signature-doc">CPF/CNPJ: {{ $buyer->cpf_cnpj }}</div>
        </div>

        <div class="signature-box" style="margin-top: 25px;">
            <div class="signature-line"></div>
            <div class="signature-name">{{ $event->witness_1_name ?? 'TESTEMUNHA 1' }}</div>
            <div class="signature-doc">TESTEMUNHA</div>
        </div>

        <div class="signature-box" style="margin-top: 25px;">
            <div class="signature-line"></div>
            <div class="signature-name">{{ $event->witness_2_name ?? 'TESTEMUNHA 2' }}</div>
            <div class="signature-doc">TESTEMUNHA</div>
        </div>
    </div>
    </div>
    @include('reports.footer')
</body>

</html>
