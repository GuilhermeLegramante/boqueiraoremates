<div class="contract contract-page">
    {{-- CABEÇALHO --}}
    <table class="contract-header">
        <tr>
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

            @php
                $establishment = $order->seller ?? ($seller ?? null);
            @endphp

            <td style="width: 40%; text-align: center;">
                <div class="event-title-center">
                    {{ $establishment?->establishment ?? '' }}
                </div>
                <div class="contract-city">
                    {{ $establishment?->establishment_city ?? '' }}
                </div>
            </td>

            <td style="width: 30%; text-align: right;">
                @if (!empty($boqueiraoLogo) && file_exists($boqueiraoLogo) && (!isset($event->show_logo) || $event->show_logo == 1))
                    @php
                        $typeLogo = pathinfo($boqueiraoLogo, PATHINFO_EXTENSION);
                        $dataLogo = file_get_contents($boqueiraoLogo);
                        $base64Logo = 'data:image/' . $typeLogo . ';base64,' . base64_encode($dataLogo);
                    @endphp
                    <img src="{{ $base64Logo }}" class="boqueirao-logo">
                @else
                    @if ((!isset($event->show_logo) || $event->show_logo == 1))
                        <img src="{{ public_path('img/logo_completa.png') }}" class="boqueirao-logo">
                    @endif
                @endif
            </td>
        </tr>
    </table>

    {{-- TÍTULO DO CONTRATO E NÚMERO DA OS --}}
    <table class="title-row-table">
        <tr>
            <td style="width: 80%;">
                <span class="contract-title">
                    CONTRATO DE COMPRA COM RESERVA DE DOMÍNIO
                </span>

                @if (isset($via))
                    <div style="text-align: center; font-size: 10px; font-weight: bold; color: #555; margin-top: 2px;">
                        ({{ $via }}ª VIA)
                    </div>
                @endif
            </td>

            <td style="width: 20%; text-align: right;">
                <div class="contract-number">
                    Nº {{ $order->number }} /
                    {{ $order->base_date ? \Carbon\Carbon::parse($order->base_date)->format('Y') : '' }}
                </div>
            </td>
        </tr>
    </table>

    {{-- VENDEDOR --}}
    <div class="section-title">VENDEDOR</div>
    <table class="data-table">
        <tr>
            <td colspan="2"><span class="label">Nome:</span> {{ data_get($seller, 'name') }}</td>
            <td>
                <span class="label">CNPJ/CPF:</span>
                {{ data_get($seller, 'cpf_cnpj', data_get($seller, 'cpf', data_get($seller, 'cnpj', data_get($seller, 'document')))) }}
            </td>
            <td>
                <span class="label">Telefone:</span>
                {{ data_get($seller, 'whatsapp', data_get($seller, 'phone', data_get($seller, 'cellphone', data_get($seller, 'celular', data_get($seller, 'telefone'))))) }}
            </td>
        </tr>
        <tr>
            <td colspan="2"><span class="label">E-mail:</span> {{ data_get($seller, 'email') }}</td>
            <td colspan="2">
                <span class="label">Endereço:</span>
                {{ data_get($seller, 'address.street', data_get($seller, 'street')) }}
                @php
                    $sellerNumber = data_get(
                        $seller,
                        'address.number',
                        data_get(
                            $seller,
                            'address.street_number',
                            data_get($seller, 'number', data_get($seller, 'numero')),
                        ),
                    );
                    $sellerComplement = data_get(
                        $seller,
                        'address.complement',
                        data_get($seller, 'complemento', data_get($seller, 'complement')),
                    );
                @endphp
                @if (!empty($sellerNumber))
                    , {{ $sellerNumber }}
                @endif
                @if (!empty($sellerComplement))
                    - {{ $sellerComplement }}
                @endif
            </td>
        </tr>
        <tr>
            <td><span class="label">Bairro:</span>
                {{ data_get($seller, 'address.district', data_get($seller, 'district', data_get($seller, 'bairro'))) }}
            </td>
            <td>
                <span class="label">Cidade/UF:</span>
                {{ data_get($seller, 'address.city', data_get($seller, 'city', data_get($seller, 'cidade'))) }} /
                {{ data_get($seller, 'address.state', data_get($seller, 'state', data_get($seller, 'uf'))) }}
            </td>
            <td colspan="2">
                <span class="label">CEP:</span>
                {{ data_get($seller, 'address.postal_code', data_get($seller, 'postal_code', data_get($seller, 'zip_code', data_get($seller, 'cep')))) }}
            </td>
        </tr>
    </table>

    {{-- COMPRADOR --}}
    <div class="section-title">COMPRADOR</div>
    <table class="data-table">
        <tr>
            <td colspan="2"><span class="label">Nome:</span> {{ data_get($buyer, 'name') }}</td>
            <td>
                <span class="label">CNPJ/CPF:</span>
                {{ data_get($buyer, 'cpf_cnpj', data_get($buyer, 'cpf', data_get($buyer, 'cnpj', data_get($buyer, 'document')))) }}
            </td>
            <td>
                <span class="label">Telefone:</span>
                {{ data_get($buyer, 'whatsapp', data_get($buyer, 'phone', data_get($buyer, 'cellphone', data_get($buyer, 'celular', data_get($buyer, 'telefone'))))) }}
            </td>
        </tr>
        <tr>
            <td colspan="2"><span class="label">E-mail:</span> {{ data_get($buyer, 'email') }}</td>
            <td colspan="2">
                <span class="label">Endereço:</span>
                {{ data_get($buyer, 'address.street', data_get($buyer, 'street')) }}
                @php
                    $buyerNumber = data_get(
                        $buyer,
                        'address.number',
                        data_get(
                            $buyer,
                            'address.street_number',
                            data_get($buyer, 'number', data_get($buyer, 'numero')),
                        ),
                    );
                    $buyerComplement = data_get(
                        $buyer,
                        'address.complement',
                        data_get($buyer, 'complemento', data_get($buyer, 'complement')),
                    );
                @endphp
                @if (!empty($buyerNumber))
                    , {{ $buyerNumber }}
                @endif
                @if (!empty($buyerComplement))
                    - {{ $buyerComplement }}
                @endif
            </td>
        </tr>
        <tr>
            <td><span class="label">Bairro:</span>
                {{ data_get($buyer, 'address.district', data_get($buyer, 'district', data_get($buyer, 'bairro'))) }}
            </td>
            <td>
                <span class="label">Cidade/UF:</span>
                {{ data_get($buyer, 'address.city', data_get($buyer, 'city', data_get($buyer, 'cidade'))) }} /
                {{ data_get($buyer, 'address.state', data_get($buyer, 'state', data_get($buyer, 'uf'))) }}
            </td>
            <td colspan="2">
                <span class="label">CEP:</span>
                {{ data_get($buyer, 'address.postal_code', data_get($buyer, 'postal_code', data_get($buyer, 'zip_code', data_get($buyer, 'cep')))) }}
            </td>
        </tr>
    </table>

    {{-- OBJETO --}}
    <div class="section-title">OBJETO</div>
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
            default => '01 Animal equino com as informações a seguir descritas:',
        };

        $breedName = mb_strtoupper($animal->breed->name ?? '', 'UTF-8');
        $isQuartoDeMilha = $breedName === 'QUARTO DE MILHA';
    @endphp

    <p class="contract-text" style="margin-bottom: 4px; font-weight: bold;">
        {{ $objetoTexto }}
    </p>

    <table class="data-table">
        <tr>
            <td><span class="label">Lote:</span> {{ $order->animalEvent?->lot_number ?? ($order->batch ?? '') }}</td>
            <td><span class="label">Raça:</span> {{ $animal->breed->name ?? '' }}</td>
            <td><span class="label">Nome:</span> {{ $order->animalEvent?->name ?? ($animal->name ?? '') }}</td>

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
                <td><span class="label">RP:</span> {{ $animal->rb ?? '' }}</td>
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

                        @case('barriga')
                            Barriga ({{ $order->sale_type_quantity }} unidades)
                        @break

                        @case('embriao')
                            Embrião ({{ $order->sale_type_quantity }} unidades)
                        @break

                        @default
                            {{ ucfirst($order->sale_type) }}
                    @endswitch
                </td>
            </tr>
        @endif

        <tr>
            <td colspan="{{ $isQuartoDeMilha ? 3 : 3 }}">
                <span class="label">Valor Total:</span> R$ {{ number_format($order->net_value ?? 0, 2, ',', '.') }}
            </td>
            <td colspan="{{ $isQuartoDeMilha ? 2 : 1 }}">
                <span class="label">Fatura de Venda/OS:</span> {{ $order->number }}
            </td>
        </tr>
    </table>

    <p class="contract-text">
        {{ $order->contract_note }}
    </p>

    {{-- TEXTO --}}
    <p class="contract-text">
        Através do presente contrato, o comprador adquire o bem acima descrito, comprometendo-se a efetuar o pagamento
        da seguinte forma:
    </p>

    <p class="payment-text">
        {{ $paymentText ?? '' }}
    </p>
    <br>

    @forelse ($event->clauses as $clause)
        @if ($clause->title)
            <h4 class="contract-clause-title">{{ $clause->title }}</h4>
        @endif
        <div class="contract-text"
            style="text-align: justify; text-justify: inter-word; margin-top: -2px; margin-bottom: 2px;">
            {!! $clause->content !!}
        </div>
    @empty
        {{-- Caso o evento não tenha cláusulas cadastradas no banco, pode manter um fallback estático ou exibir nada --}}
        <p class="contract-text" style="margin-top: -15px; margin-bottom: 4px;">
            EVENTO SEM CLÁUSULAS CADASTRADAS.
        </p>
    @endforelse

    <p style="text-align: center; font-size: 9.5px; margin-top: 12px;">
        {{ $event->city ?? 'Uruguaiana - RS' }},
        {{ \Carbon\Carbon::parse($order->base_date ?? now())->locale('pt_BR')->translatedFormat('d \d\e F \d\e Y') }}
    </p>

    {{-- ASSINATURAS --}}
    <table class="signature-table">
        <tr>
            <td>
                <div class="signature-line"></div>
                <div class="signature-name">{{ $seller->name }}</div>
                <div class="signature-role">VENDEDOR</div>

                @if (!empty($seller->representative_name))
                    <div style="margin-top: 8px; font-size: 8.5px; color: #333;">
                        <span class="label">Rep. Legal:</span>
                        {{ mb_strtoupper($seller->representative_name, 'UTF-8') }}
                        @if (!empty($seller->representative_role))
                            ({{ mb_strtoupper($seller->representative_role, 'UTF-8') }})
                        @endif
                        @if (!empty($seller->representative_document))
                            <br><span class="label">CPF/CNPJ:</span> {{ $seller->representative_document }}
                        @endif
                    </div>
                @endif
            </td>

            <td>
                <div class="signature-line"></div>
                <div class="signature-name">{{ $buyer->name }}</div>
                <div class="signature-role">COMPRADOR</div>

                @if (!empty($buyer->representative_name))
                    <div style="margin-top: 8px; font-size: 8.5px; color: #333;">
                        <span class="label">Rep. Legal:</span>
                        {{ mb_strtoupper($buyer->representative_name, 'UTF-8') }}
                        @if (!empty($buyer->representative_role))
                            ({{ mb_strtoupper($buyer->representative_role, 'UTF-8') }})
                        @endif
                        @if (!empty($buyer->representative_document))
                            <br><span class="label">CPF/CNPJ:</span> {{ $buyer->representative_document }}
                        @endif
                    </div>
                @endif
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
