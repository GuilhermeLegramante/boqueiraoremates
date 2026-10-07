<div class="promissory-container">
    {{-- CABEÇALHO --}}
    <table class="promissory-header">
        <tr>
            <td style="width: 40%;">
                @if (!empty($boqueiraoLogo) && file_exists($boqueiraoLogo))
                    @php
                        $type = pathinfo($boqueiraoLogo, PATHINFO_EXTENSION);
                        $data = file_get_contents($boqueiraoLogo);
                        $base64Logo = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    @endphp
                    <img src="{{ $base64Logo }}" class="event-logo">
                @elseif (!empty($eventBanner) && file_exists($eventBanner))
                    @php
                        $type = pathinfo($eventBanner, PATHINFO_EXTENSION);
                        $data = file_get_contents($eventBanner);
                        $base64Banner = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    @endphp
                    <img src="{{ $base64Banner }}" class="event-logo">
                @endif
            </td>
            <td style="width: 60%; text-align: right;">
                <div class="promissory-title">{{ $promissoryTitle ?? 'NOTA PROMISSÓRIA' }}</div>
                <div style="font-size: 9.5px; line-height: 1.25;">
                    <div><span class="label">Nota do:</span> {{ mb_strtoupper($event->name ?? '', 'UTF-8') }}</div>
                    <div><span class="label">NP Nº:</span> {{ $order->number }}</div>
                </div>
            </td>
        </tr>
    </table>

    {{-- VENDEDOR DO SERVIÇO (DADOS DA LEILOEIRA/EMPRESA) --}}
    <div class="section-header">VENDEDOR DO SERVIÇO</div>
    <table class="info-table">
        <tr>
            <td style="width: 60%;"><span class="label">Nome:</span> VARGAS E HOISLER LTDA - BOQUEIRÃO REMATES</td>
            <td style="width: 40%;"><span class="label">CNPJ:</span> 23.732.345/0001-35</td>
        </tr>
        <tr>
            <td><span class="label">Ender.:</span> RUA BARÃO DO RIO BRANCO, Nº 70</td>
            <td><span class="label">Bairro:</span> SANTIAGO POMPEO</td>
        </tr>
        <tr>
            <td><span class="label">Cidade/UF:</span> SANTIAGO - RS</td>
            <td><span class="label">Cep:</span> 97.701-254</td>
        </tr>
        <tr>
            <td><span class="label">E-mail:</span> centralboqueirao@gmail.com</td>
            <td><span class="label">Contato:</span> 55 9 9937.0070</td>
        </tr>
    </table>

    {{-- COMPRADOR (OU VENDEDOR DO ANIMAL QUE ESTÁ PAGANDO A COMISSÃO) --}}
    <div class="section-header">COMPRADOR</div>
    <table class="info-table">
        <tr>
            <td style="width: 60%;"><span class="label">Nome:</span> {{ $payer->name ?? '' }}</td>
            <td style="width: 40%;"><span class="label">CPF/CNPJ:</span> {{ $payer->cpf_cnpj ?? '' }}</td>
        </tr>
        <tr>
            <td><span class="label">Ender.:</span> {{ $payer->address->street ?? '' }},
                {{ $payer->address->number ?? '' }} {{ $payer->address->complement ?? '' }}</td>
            <td><span class="label">Bairro:</span> {{ $payer->address->district ?? '' }}</td>
        </tr>
        <tr>
            <td><span class="label">Cidade/UF:</span> {{ $payer->address->city ?? '' }} -
                {{ $payer->address->state ?? '' }}</td>
            <td><span class="label">Cep:</span> {{ $payer->address->postal_code ?? '' }}</td>
        </tr>
        <tr>
            <td><span class="label">E-mail:</span> {{ $payer->email ?? '' }}</td>
            <td><span class="label">Contato:</span> {{ $payer->whatsapp ?? ($payer->phone ?? '') }}</td>
        </tr>
    </table>

    {{-- ESPECIFICAÇÃO DOS PRODUTOS / LOTES --}}
    <div class="section-header">INTERMEDIAÇÃO COMERCIAL DOS PRODUTOS/LOTES</div>
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
    @php
        $parcelsArray = is_array($activeParcels) ? $activeParcels : $activeParcels->toArray();
        $totalParcelsCount = count($parcelsArray);
        $totalParcelsValue = array_reduce($parcelsArray, fn($acc, $p) => $acc + (float) data_get($p, 'value', 0), 0);
    @endphp
    <div class="section-header">ACERTO FINANCEIRO</div>
    <table class="info-table" style="width: 100%;">
        <tr>
            <td style="width: 33%;"><span class="label">Dta da Compra:</span>
                {{ \Carbon\Carbon::parse($order->base_date ?? now())->format('d/m/Y') }}</td>
            <td style="width: 34%;"><span class="label">Cond.:</span> {{ $order->paymentWay->name ?? '' }}</td>
            <td style="width: 33%;" class="text-right"><span class="label">{{ $totalParcelsCount }} PARCELAS</span>
            </td>
        </tr>
        <tr>
            <td><span class="label">Vlr Bruto:</span> R$ {{ number_format($order->gross_value ?? 0, 2, ',', '.') }}
            </td>
            <td><span class="label">Desc.:</span> {{ number_format($order->discount_percentage ?? 0, 2, ',', '.') }}%
            </td>
            <td class="text-right"><span class="label">Vlr Líquido:</span> R$
                {{ number_format($totalParcelsValue, 2, ',', '.') }}</td>
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
                    $numColumns = max(1, min(4, ceil($totalParcelsCount / 15)));
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
                                            <td>{{ data_get($parcelsArray[$i], 'number', $i + 1) }}</td>
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
        Fica eleito o Foro da Comarca de Santiago (RS) para dirimir qualquer questão atinente ao presente contrato.
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
            <div style="font-size: 8px; text-transform: uppercase;">CPF/CNPJ: {{ $buyer->cpf_cnpj }}</div>
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
