@extends('reports.layout')

@section('content')
    <div class="page-section">
        {{-- CABEÇALHO --}}
        <table class="table-header">
            <tr>
                <td style="width: 20%; text-align: left;">
                    @if (!empty($eventBanner))
                        <img src="{{ $eventBanner }}" style="max-height: 45px;">
                    @endif
                </td>
                <td style="width: 60%;" class="header-title">
                    <h2>REGULAMENTO DO LEILÃO / REMATE</h2>
                    <h3>{{ $event->name }}</h3>
                </td>
                <td style="width: 20%; text-align: right;">
                    @if (!empty($boqueiraoLogo))
                        <img src="{{ $boqueiraoLogo }}" style="max-height: 45px;">
                    @endif
                </td>
            </tr>
        </table>

        {{-- LISTA DE REGRAS DO REGULAMENTO --}}
        @php
            $regulationClauses = $event->regulationClauses->sortBy('order');
        @endphp

        <table class="rules-list">
            @if ($regulationClauses->count() > 0)
                @foreach ($regulationClauses as $index => $clause)
                    @php
                        $title = $clause->title ?? null;
                        $content = $clause->content ?? '';
                        $cleanContent = preg_replace('/^<p>(.*?)<\/p>$/s', '$1', trim($content));
                    @endphp
                    <tr>
                        <td class="num" style="vertical-align: top; padding-top: 0; line-height: 1.4;">
                            {{ $index + 1 }} )
                        </td>
                        <td style="vertical-align: top; padding-top: 0; line-height: 1.4;" class="rich-content">
                            @if (!empty($title))
                                <strong>{{ $title }}</strong><br>
                            @endif
                            {!! $cleanContent !!}
                        </td>
                    </tr>
                @endforeach
            @else
                {{-- FALLBACK: Lista de regras estáticas legadas --}}
                <tr>
                    <td class="num">1 )</td>
                    <td>O leilão será realizado no dia
                        <strong>{{ !empty(is_array($event) ? $event['start_date'] ?? null : $event->start_date ?? null) ? \Carbon\Carbon::parse(is_array($event) ? $event['start_date'] : $event->start_date)->format('d/m/Y') : $contractDate->format('d/m/Y') ?? '' }}</strong>,
                        às
                        <strong>{{ (is_array($event) ? $event['time'] ?? null : $event->time ?? null) ?? '19h' }}</strong>,
                        na cidade de
                        <strong>{{ $contractCity ?? ($seller->address->city ?? 'Uruguaiana') . ' - ' . ($seller->address->state ?? 'RS') }}</strong>.
                    </td>
                </tr>
                <tr>
                    <td class="num">2 )</td>
                    <td>Os animais serão apresentados através de fotos e vídeos no site oficial e sistema de pré-lance do
                        evento.</td>
                </tr>
                <tr>
                    <td class="num">3 )</td>
                    <td>Para a oferta de lance nos lotes do
                        <strong>{{ is_array($event) ? $event['name'] ?? '' : $event->name ?? '' }}</strong>, o cliente
                        deverá ter feito antecipadamente o seu cadastro junto ao escritório realizador.
                    </td>
                </tr>
                <tr>
                    <td class="num">4 )</td>
                    <td>Ao preencher o cadastro você registrará a sua senha pessoal e intransferível que lhe confere o
                        direito
                        de participar do certame.</td>
                </tr>
                <tr>
                    <td class="num">5 )</td>
                    <td>O meio de comunicação utilizado pela empresa leiloeira para contatar com os clientes durante o
                        pré-lance
                        será por telefone, e-mail ou WhatsApp.</td>
                </tr>
                <tr>
                    <td class="num">6 )</td>
                    <td class="highlight-red">Antes do leilão é facultado ao comprador examinar ou mandar examinar por um
                        veterinário ou por um técnico de sua confiança, os animais na propriedade ou no recinto, antes do
                        remate, motivo pelo qual as vendas do leilão são irrevogáveis, não podendo o comprador recusar o
                        animal
                        ou pedir redução de preço.</td>
                </tr>
                <tr>
                    <td class="num">7 )</td>
                    <td>Os animais irão a leilão tendo como preço base inicial os valores do sistema de pré-lance.</td>
                </tr>
                <tr>
                    <td class="num">8 )</td>
                    <td>A condição de pagamento da negociação foi acordada entre as partes e está descrita no texto do
                        contrato
                        de número &nbsp; <strong>{{ is_array($order) ? $order['number'] : $order->number }} /
                            {{ isset($contractDate) ? $contractDate->format('Y') : date('Y') }}</strong>, bem como na
                        promissória, que leva a mesma numeração e compõe o conjunto de documentos.</td>
                </tr>
                <tr>
                    <td class="num">9 )</td>
                    <td>O comprador pagará ao vendedor conforme parcelamento gerado na Nota Promissória que compõe o
                        conjunto de
                        documentos que formalizam a negociação.</td>
                </tr>
                <tr>
                    <td class="num">10 )</td>
                    <td>Na hipótese do preço ajustado entre as partes ser parcelado e houver atraso no pagamento de qualquer
                        uma
                        das parcelas do preço, constituirá o comprador em mora, independentemente de notificação, implicará
                        no
                        vencimento das demais antecipadamente, corrigidas pelo IGP-M e juros de 1% ao mês.</td>
                </tr>
                <tr>
                    <td class="num">11 )</td>
                    <td>A comissão do remate é cobrada do comprador no ato do acerto de contas da compra.</td>
                </tr>
                <tr>
                    <td class="num">12 )</td>
                    <td>O comissionamento da leiloeira trata-se de uma prestação de serviços executada durante o período de
                        pré-lance e recinto e/ou transmissão da finalização.</td>
                </tr>
                <tr>
                    <td class="num">13 )</td>
                    <td>Imediatamente após a batida do martelo e a definição do comprador de cada lote, é dada
                        automaticamente
                        ao escritório a autorização para emissão do contrato de compra e venda.</td>
                </tr>
                <tr>
                    <td class="num">14 )</td>
                    <td>O comprador, assinando o contrato de compra e venda e quitando as parcelas determinadas para o ato e
                        a
                        comissão de compra, receberá uma liberação para retirada do animal.</td>
                </tr>
                <tr>
                    <td class="num">15 )</td>
                    <td>Assiste ao vendedor o direito de solicitar avalista de seu conhecimento antes que o comprador faça
                        seu
                        acerto de contas.</td>
                </tr>
                <tr>
                    <td class="num">16 )</td>
                    <td>O leiloeiro/escritório se reserva o direito de não aceitar lances de pessoas que, a seu critério,
                        não
                        considera merecedoras de crédito.</td>
                </tr>
                <tr>
                    <td class="num">17 )</td>
                    <td>O comprador dá ao vendedor, em penhor pecuniário, a mercadoria até a liquidação da dívida, ficando o
                        comprador na qualidade de fiel depositário.</td>
                </tr>
                <tr>
                    <td class="num">18 )</td>
                    <td>É de inteira responsabilidade do VENDEDOR a geração de boletos para cobrança das parcelas vincendas,
                        isentando o Leiloeiro e a Leiloeira de garantias de pagamentos.</td>
                </tr>
                <tr>
                    <td class="num">19 )</td>
                    <td>A transferência junto à ABCCC dos animais e/ou Cotas vendidos neste leilão será efetuada pelos
                        vendedores após a quitação da totalidade das parcelas.</td>
                </tr>
                <tr>
                    <td class="num">20 )</td>
                    <td>O VENDEDOR fica com o compromisso de após quitado o bem objeto deste contrato, efetuar a
                        transferência
                        do mesmo para o nome do COMPRADOR, utilizando o sistema de RESERVA DE DOMÍNIO oferecido pela ABCCC.
                    </td>
                </tr>
                <tr>
                    <td class="num">21 )</td>
                    <td>A escolha do transportador é de responsabilidade exclusiva do comprador, isentando o vendedor e a
                        empresa leiloeira de qualquer problema durante o transporte.</td>
                </tr>
                <tr>
                    <td class="num">22 )</td>
                    <td>Quaisquer incidências de ICMS nas transações de venda ou transporte deverão transcorrer por conta do
                        comprador.</td>
                </tr>
                <tr>
                    <td class="num">23 )</td>
                    <td>Os vendedores não se responsabilizam por qualquer tipo de trauma ocorrido após a entrega do animal
                        ao
                        comprador.</td>
                </tr>
                <tr>
                    <td class="num">24 )</td>
                    <td>A Cabanha vendedora, bem como a Empresa Leiloeira não se responsabilizam por erros que possam
                        ocorrer no
                        catálogo on-line (pré-lance) e/ou físico.</td>
                </tr>
                <tr>
                    <td class="num">25 )</td>
                    <td>Este regulamento deverá ser guardado, pois o conteúdo nele existente vale como documento e servirá
                        para
                        sanar futuras dúvidas.</td>
                </tr>
            @endif
        </table>

        {{-- DECLARAÇÃO EM BRANCO PARA PREENCHIMENTO DA NEGOCIAÇÃO --}}
        <div class="decl-box">
            Eu, <span class="fill-line" style="min-width: 250px;"></span>,
            portador do CPF/CNPJ nº <span class="fill-line" style="min-width: 150px;"></span>,
            declaro para devidos fins e a quem possa interessar que adquiri o equino/lote
            <span class="fill-line" style="min-width: 200px;"></span>,
            no <span class="fill-line" style="min-width: auto; display: inline;">{{ $event->name }}</span>
            e estou CIENTE e DE ACORDO com o regulamento acima especificado que normatiza a negociação.
        </div>

        <div style="text-align: right; margin-top: 15px; font-weight: bold; font-size: 9.5px;">
            {{ $event->city ?? 'Uruguaiana - RS' }},
            {{ \Carbon\Carbon::parse($event->start_date ?? now())->locale('pt_BR')->translatedFormat('d \d\e F \d\e Y') }}.
        </div>

        {{-- ASSINATURAS --}}
        <div class="signatures-container" style="margin-top: 30px;">
            <div class="signature-block">
                <div class="signature-line-item">COMPRADOR</div>
                <div class="signature-role">COMPRADOR</div>
            </div>

            <div class="signature-block">
                <div class="signature-line-item">VENDEDOR</div>
                <div class="signature-role">VENDEDOR</div>
            </div>

            <div class="signature-block">
                <div class="signature-line-item">{{ $event->witness_1_name ?? 'TESTEMUNHA 1' }}</div>
                <div class="signature-role">TESTEMUNHA</div>
            </div>

            <div class="signature-block">
                <div class="signature-line-item">{{ $event->witness_2_name ?? 'TESTEMUNHA 2' }}</div>
                <div class="signature-role">TESTEMUNHA</div>
            </div>
        </div>
    </div>
@endsection
