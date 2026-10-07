<div class="promissory-container">
    {{-- CABEÇALHO --}}
    <table class="promissory-header">
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
            <td style="width: 60%; text-align: right;">
                <div class="promissory-title">NOTA PROMISSÓRIA - ÚNICA</div>
                <div style="font-size: 9.5px; line-height: 1.25;">
                    <div><span class="label">Nota do:</span> {{ mb_strtoupper($event->name ?? '', 'UTF-8') }}</div>
                    <div><span class="label">NP Nº:</span> {{ $order->number }}</div>
                    <div><span class="label">Escritório/Leiloeiro:</span> {{ $event->auctioneer ?? '' }}</div>
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
        $objetoTexto = match ($order->sale_type ?? '') {
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

    <div style="text-align: center; font-weight: bold; font-size: 9px; margin: 2px 0;">
        {{ $objetoTexto }}
    </div>

    <table class="info-table">
        <tr>
            <td style="width: 25%;"><span class="label">Nº Lote(s):</span>
                {{ $order->animalEvent->lot_number ?? ($order->batch ?? '') }}</td>
            <td style="width: 75%;" colspan="2"><span class="label">Nome(s):</span>
                {{ $order->animalEvent->name ?? ($animal->name ?? '') }}</td>
        </tr>
        <tr>
            <td><span class="label">Raça:</span> {{ $animal->breed->name ?? '' }}</td>
            @if ($isQuartoDeMilha)
                <td><span class="label">Registro:</span> {{ $animal->register ?? '' }}</td>
                <td>
                    <span class="label">Grau de Sangue:</span>
                    @if (($animal->blood_level ?? '') === 'pure')
                        Puro
                    @elseif (($animal->blood_level ?? '') === 'mixed')
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
                {{ ($animal->gender ?? '') === 'male' ? 'MACHO' : (($animal->gender ?? '') === 'female' ? 'FÊMEA' : '') }}
            </td>
            <td colspan="2"><span class="label">Pelagem (ns):</span> {{ $animal->coat->name ?? '' }}</td>
        </tr>
    </table>

    {{-- ACERTO FINANCEIRO --}}
    <div class="section-header">ACERTO FINANCEIRO</div>
    <table class="info-table" style="width: 100%;">
        <tr>
            <td style="width: 33%;"><span class="label">Dta da Compra:</span>
                {{ \Carbon\Carbon::parse($order->base_date ?? now())->format('d/m/Y') }}</td>
            <td style="width: 34%;"><span class="label">Cond.:</span> {{ $order->paymentWay->name ?? '' }}</td>
            <td style="width: 33%;" class="text-right"><span class="label">{{ count($order->parcels ?? []) }}
                    PARCELAS</span></td>
        </tr>
        <tr>
            <td><span class="label">Vlr Bruto:</span> R$ {{ number_format($order->gross_value ?? 0, 2, ',', '.') }}
            </td>
            <td><span class="label">Desc.:</span> {{ number_format($order->discount_percentage ?? 0, 2, ',', '.') }}%
            </td>
            <td class="text-right"><span class="label">Vlr Líquido:</span> R$
                {{ number_format($order->net_value ?? ($order->gross_value ?? 0), 2, ',', '.') }}</td>
        </tr>
        @if (($order->first_parcel_value ?? 0) > 0)
            <tr>
                <td colspan="3">
                    <span class="label">Entrada de:</span> R$
                    {{ number_format($order->first_parcel_value, 2, ',', '.') }} (+) as parcelas e seus respectivos
                    vencimentos, descritos a seguir:
                </td>
            </tr>
        @endif
    </table>

    {{-- TABELA DE PARCELAS --}}
    <div class="parcels-wrapper">
        <table class="parcels-columns-table">
            <tr>
                @php
                    $parcelsArray = is_array($order->parcels ?? [])
                        ? $order->parcels
                        : $order->parcels?->toArray() ?? [];
                    $totalParcels = count($parcelsArray);
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
                                            </td>
                                            <td>{{ date('d/m/Y', strtotime(data_get($parcelsArray[$i], 'date'))) }}
                                            </td>
                                            <td class="text-right">R$
                                                {{ number_format(data_get($parcelsArray[$i], 'value', 0), 2, ',', '.') }}
                                            </td>
                                        @else
                                            <td>—</td>
                                            <td>—</td>
                                            <td>—</td>
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
        Se o credor sair vitorioso em uma ação judicial para cobrar esta nota, o devedor pagará os custos de tribunal,
        custos de agencia de cobrança e honorários advocatícios no valor estabelecido pelo tribunal.
    </p>
    <p class="clause-text">
        Fica eleito o Foro da Comarca da cidade do vendedor para dirimir qualquer questão atinente ao presente contrato.
    </p>
    <p class="clause-text">
        Com as assinaturas, as partes ficam de acordo com as disposições gerais que se encontram acima descritas,
        fazendo parte integral do presente instrumento.
    </p>

    {{-- CIDADE E DATA --}}
    <div class="city-date">
        {{ $event->city ?? 'Uruguaiana - RS' }},
        {{ \Carbon\Carbon::parse($order->base_date ?? now())->locale('pt_BR')->translatedFormat('d \d\e F \d\e Y') }}
    </div>

    {{-- ASSINATURAS (Protegidas para não quebrar de página) --}}
    <div class="promissory-signatures no-break">
        <div class="promissory-signature-box">
            <div class="signature-line"></div>
            <div class="signature-name">{{ $buyer->name }}</div>
            <div style="font-size: 8px; text-transform: uppercase;">COMPRADOR - CPF/CNPJ: {{ $buyer->cpf_cnpj }}</div>
        </div>

        <div class="promissory-signature-box" style="margin-top: 15px;">
            <div class="signature-line"></div>
            <div class="signature-name">{{ $event->witness_1_name ?? 'TESTEMUNHA 1' }}</div>
            <div style="font-size: 8px; text-transform: uppercase;">TESTEMUNHA</div>
        </div>

        <div class="promissory-signature-box" style="margin-top: 15px;">
            <div class="signature-line"></div>
            <div class="signature-name">{{ $event->witness_2_name ?? 'TESTEMUNHA 2' }}</div>
            <div style="font-size: 8px; text-transform: uppercase;">TESTEMUNHA</div>
        </div>
    </div>
</div>
