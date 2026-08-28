<!DOCTYPE html>
<html lang="en">

@php
    $this_title = ucwords(strtolower($title));
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- <link rel="stylesheet" href="{{ asset('css/report.css') }}"> --}}
    <title>{{ $this_title }}</title>

    <style type="text/css">
        @page {
            margin: 5mm 5mm 5mm 5mm;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            font-size: 50%;
        }

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

        /* Título do evento no centro */
        .event-title-center {
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
            color: #000;
            margin-bottom: 3px;
        }

        .contract-buyer {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 4px;
        }

        .contract-city {
            text-align: center;
            font-size: 10px;
            margin-top: 2px;
        }

        /* Linha do título com número do pedido */
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

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        .data-table td {
            border: 1px solid #000;
            padding: 3px 4px;
            font-size: 9px;
            vertical-align: top;
        }

        .label {
            font-weight: bold;
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
            margin-top: 35px;
        }

        .signature-table td {
            width: 50%;
            text-align: center;
            padding: 30px 15px 0 15px;
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

        .spacer {
            height: 10px;
        }

        .contract-page {
            border: 1px solid #000;
            padding: 12px;
        }
    </style>
</head>

<body>
    <div class="contract contract-page">

        {{-- CABEÇALHO --}}
        <table class="contract-header">
            <tr>
                {{-- Logo da Esquerda (Evento/GAP) --}}
                <td style="width: 30%; text-align: left;">
                    @if (!empty($eventBanner) && file_exists($eventBanner))
                        @php
                            $type = pathinfo($eventBanner, PATHINFO_EXTENSION);
                            $data = file_get_contents($eventBanner);
                            $base64Banner = 'data:image/' . $type . ';base64,' . base64_encode($data);
                        @endphp
                        <img src="{{ $base64Banner }}" class="event-logo">
                    @endif
                </td>

                {{-- Nome do Evento e Cidade (Centro) --}}
                <td style="width: 40%; text-align: center;">
                    <div class="event-title-center">
                        {{ $seller->establishment }}
                    </div>
                    <div class="contract-city">
                        {{ $seller->address->city ?? '' }} - {{ $seller->address->state ?? '' }}
                    </div>
                </td>

                {{-- Logo da Direita (Boqueirão) --}}
                <td style="width: 30%; text-align: right;">
                    @if (!empty($boqueiraoLogo) && file_exists($boqueiraoLogo))
                        @php
                            $typeLogo = pathinfo($boqueiraoLogo, PATHINFO_EXTENSION);
                            $dataLogo = file_get_contents($boqueiraoLogo);
                            $base64Logo = 'data:image/' . $typeLogo . ';base64,' . base64_encode($dataLogo);
                        @endphp
                        <img src="{{ $base64Logo }}" class="boqueirao-logo">
                    @else
                        <img src="{{ public_path('img/logo_completa.png') }}" class="boqueirao-logo">
                    @endif
                </td>
            </tr>
        </table>

        {{-- TÍTULO DO CONTRATO E NÚMERO DA OS --}}
        <table class="title-row-table">
            <tr>
                <td style="width: 80%;">
                    <span class="contract-title">
                        NOTA DE LEILÃO E CONTRATO DE COMPRA COM RESERVA DE DOMÍNIO
                    </span>

                    {{-- Indicação da Via --}}
                    @if (isset($via))
                        <div
                            style="text-align: center; font-size: 10px; font-weight: bold; color: #555; margin-top: 2px;">
                            ({{ $via }}ª VIA)
                        </div>
                    @endif
                </td>

                <td style="width: 20%; text-align: right;">
                    <div class="contract-number">
                        Nº {{ $order->number }} / {{ $contractDate->format('Y') }}
                    </div>
                </td>
            </tr>
        </table>

        {{-- VENDEDOR --}}
        <div class="section-title">
            VENDEDOR
        </div>

        <table class="data-table">
            <tr>
                <td colspan="2">
                    <span class="label">Nome:</span>
                    {{ $seller->name }}
                </td>

                <td>
                    <span class="label">CNPJ/CPF:</span>
                    {{ $seller->cpf_cnpj }}
                </td>

                <td>
                    <span class="label">Telefone:</span>
                    {{ $seller->phone ?? '' }}
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <span class="label">E-mail:</span>
                    {{ $seller->email ?? '' }}
                </td>

                <td colspan="2">
                    <span class="label">Endereço:</span>
                    {{ $seller->address->street ?? '' }}
                </td>
            </tr>

            <tr>
                <td>
                    <span class="label">Bairro:</span>
                    {{ $seller->address->district ?? '' }}
                </td>

                <td>
                    <span class="label">Cidade/UF:</span>
                    {{ $seller->address->city ?? '' }} / {{ $seller->address->state ?? '' }}
                </td>

                <td colspan="2">
                    <span class="label">CEP:</span>
                    {{ $seller->address->postal_code ?? '' }}
                </td>
            </tr>
        </table>

        {{-- COMPRADOR --}}
        <div class="section-title">
            COMPRADOR
        </div>

        <table class="data-table">
            <tr>
                <td colspan="2">
                    <span class="label">Nome:</span>
                    {{ $buyer->name }}
                </td>

                <td>
                    <span class="label">CNPJ/CPF:</span>
                    {{ $buyer->cpf_cnpj }}
                </td>

                <td>
                    <span class="label">Telefone:</span>
                    {{ $buyer->phone ?? '' }}
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <span class="label">E-mail:</span>
                    {{ $buyer->email ?? '' }}
                </td>

                <td colspan="2">
                    <span class="label">Endereço:</span>
                    {{ $buyer->address->street ?? '' }}
                </td>
            </tr>

            <tr>
                <td>
                    <span class="label">Bairro:</span>
                    {{ $buyer->address->district ?? '' }}
                </td>

                <td>
                    <span class="label">Cidade/UF:</span>
                    {{ $buyer->address->city ?? '' }} / {{ $buyer->address->state ?? '' }}
                </td>

                <td colspan="2">
                    <span class="label">CEP:</span>
                    {{ $buyer->address->postal_code ?? '' }}
                </td>
            </tr>
        </table>

        {{-- OBJETO --}}
        <div class="section-title">
            OBJETO
        </div>

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
                default => 'Animal equino com as informações a seguir descritas:',
            };

            $breedName = mb_strtoupper($animal->breed->name ?? '', 'UTF-8');
            $isQuartoDeMilha = $breedName === 'QUARTO DE MILHA';
        @endphp

        <p class="contract-text" style="margin-bottom: 4px; font-weight: bold;">
            {{ $objetoTexto }}
        </p>

        <table class="data-table">
            <tr>
                <td>
                    <span class="label">Lote:</span>
                    {{ $order->animalEvent->lot_number ?? ($order->batch ?? '') }}
                </td>

                <td>
                    <span class="label">Raça:</span>
                    {{ $animal->breed->name ?? '' }}
                </td>

                <td>
                    <span class="label">Nome:</span>
                    {{ $order->animalEvent->name ?? ($animal->name ?? '') }}
                </td>

                @if ($isQuartoDeMilha)
                    <td>
                        <span class="label">Registro:</span>
                        {{ $animal->register ?? '' }}
                    </td>

                    <td>
                        <span class="label">Grau de Sangue:</span>
                        @if ($animal->blood_level === 'pure')
                            Puro
                        @elseif ($animal->blood_level === 'mixed')
                            Mestiço {{ $animal->blood_percentual ? '(' . $animal->blood_percentual . '%)' : '' }}
                        @endif
                    </td>
                @else
                    <td>
                        <span class="label">RP:</span>
                        {{ $animal->rb ?? '' }}
                    </td>
                @endif
            </tr>

            @if (!empty($order->sale_type))
                <tr>
                    <td colspan="{{ $isQuartoDeMilha ? 5 : 4 }}">
                        <span class="label">Tipo de Venda:</span>
                        @switch($order->sale_type)
                            @case('animal_inteiro')
                                O Animal
                            @break

                            @case('cota')
                                Cota ({{ $order->sale_type_percentage }}%)
                            @break

                            @case('direito_de_uso')
                                Direito de Uso ({{ $order->sale_type_percentage }}%)
                            @break

                            @case('cobertura')
                                Cobertura ({{ $order->sale_type_quantity }} unidades)
                            @break

                            @default
                                {{ ucfirst($order->sale_type) }}
                        @endswitch
                    </td>
                </tr>
            @endif

            <tr>
                <td colspan="{{ $isQuartoDeMilha ? 3 : 3 }}">
                    <span class="label">Valor Total:</span>
                    R$ {{ number_format($order->gross_value, 2, ',', '.') }}
                </td>

                <td colspan="{{ $isQuartoDeMilha ? 2 : 1 }}">
                    <span class="label">Fatura de Venda/OS:</span>
                    {{ $order->number }}
                </td>
            </tr>
        </table>

        {{-- TEXTO --}}
        <p class="contract-text">
            Através do presente contrato, o comprador adquire o bem acima descrito,
            comprometendo-se a efetuar o pagamento da seguinte forma:
        </p>

        <p class="payment-text">
            {{ $paymentText }}
        </p>

        <p class="contract-text">
            Na hipótese de haver atraso no pagamento, de qualquer uma das parcelas do preço,
            constituirá o comprador em mora, independentemente de notificação, implicará no
            vencimento das demais antecipadamente, as quais serão corrigidas pelo IGP-M e
            acrescidas de juros de vencimento de mora à razão de 1% ao mês a contar do vencimento,
            e sendo assim implicará no protesto do presente título de dívida. Em caso de rescisão
            por inadimplemento do comprador, os valores já pagos não serão restituídos, ficando
            retidos pelo vendedor a título de cláusula penal compensatória e indenização por perdas
            e danos, sem prejuízo da cobrança de eventuais valores ainda pendentes.
        </p>

        <p class="contract-text">
            Consideramos o comprador e o vendedor como conhecendo e aceitando todos os termos do
            regulamento deste Remate/Leilão e o conteúdo nele existente tendo validade como documento
            e servindo para sanar futuras dúvidas.
        </p>

        <p class="contract-text">
            Fica também ajustado que, nos termos do art. 190 do Código de Processo Civil, em caso de
            inadimplemento de qualquer das parcelas previstas neste contrato, poderá o vendedor,
            a seu exclusivo critério, ingressar com ação de busca e apreensão do bem objeto deste
            instrumento, ou promover a execução dos valores devidos, conforme as disposições aqui
            estabelecidas, facultando-se ao vendedor a adoção do procedimento que melhor atender
            aos seus interesses.
        </p>

        <p class="contract-text">
            A transferência do(s) animal(is), ou cota(s) dele, será realizada junto à ABCCC logo
            após a quitação total do(s) produto(s). Em caso de transferências dos mesmo(s) ainda
            com o contrato ainda em vigor, ambas partes SÃO DE ACORDO com inclusão de Reserva de
            Domínio no(s) animal(is), sendo liberada pelo Vendedor logo após a quitação total
            deste contrato.
        </p>

        <p class="contract-text">
            Fica eleito o Foro da Comarca de Uruguaiana (RS) para dirimir qualquer questão
            atinente ao presente contrato.
        </p>

        <p class="contract-text">
            E por assim estarem justos e contratados, firma o presente instrumento em duas vias
            de igual teor e forma.
        </p>

        <p style="text-align: center; font-size: 9.5px; margin-top: 12px;">
            {{ $seller->address->city ?? 'Uruguaiana' }} - {{ $seller->address->state ?? 'RS' }},
            {{ \Carbon\Carbon::parse($contractDate)->locale('pt_BR')->translatedFormat('d \d\e F \d\e Y') }}
        </p>

        {{-- ASSINATURAS --}}
        <table class="signature-table">
            <tr>
                <td>
                    <div class="signature-line"></div>
                    <div class="signature-name">{{ $seller->name }}</div>
                    <div class="signature-role">VENDEDOR</div>
                </td>

                <td>
                    <div class="signature-line"></div>
                    <div class="signature-name">{{ $buyer->name }}</div>
                    <div class="signature-role">COMPRADOR</div>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="signature-line"></div>
                    <div class="signature-name">{{ mb_strtoupper($event->witness_1_name ?? '', 'UTF-8') }}</div>
                    <div class="signature-role">TESTEMUNHA 1</div>
                </td>

                <td>
                    <div class="signature-line"></div>
                    <div class="signature-name">{{ mb_strtoupper($event->witness_2_name ?? '', 'UTF-8') }}</div>
                    <div class="signature-role">TESTEMUNHA 2</div>
                </td>
            </tr>
        </table>
    </div>
    @include('reports.footer')
</body>

</html>
