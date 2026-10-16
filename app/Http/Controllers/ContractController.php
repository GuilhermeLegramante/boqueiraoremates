<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Event;
use App\Models\Order;
use App\Utils\ReportFactory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use NumberToWords\NumberToWords;

class ContractController extends Controller
{
    private $numberTransformer;

    public function __construct()
    {
        $numberToWords = new NumberToWords();
        $this->numberTransformer = $numberToWords->getNumberTransformer('pt_BR');
    }

    /**
     * Imprime a 1ª ou 2ª via do Contrato
     */
    public function getPdf($id, Request $request)
    {
        set_time_limit(0);

        $contract = Contract::with([
            'order.event.clauses',
            'order',
            'order.event.regulationClauses',
            'order.seller.address',
            'order.buyer.address',
            'order.animal.breed',
            'order.animal.coat',
            'order.paymentWay',
            'order.parcels',
            'order.sellerParcels',
            'order.buyerParcels',
        ])->findOrFail($id);

        $via = (int) $request->get('via', 1);

        $data = !empty($contract->snapshot)
            ? $this->prepareFromSnapshot($contract, $via)
            : $this->prepareFromDatabase($contract, $via);

        $fileName = 'CONTRATO_' . $data['order']->number . '_VIA_' . $via . '.pdf';

        return ReportFactory::getBasicPdf(
            'portrait',
            'reports.contract',
            $data,
            $fileName
        );
    }

    /**
     * Imprime a Nota Promissória Padrão (Geral)
     */
    public function showPromissoryNote($id)
    {
        return $this->renderPromissoryPdf($id, 'promissory', 'NOTA PROMISSÓRIA - ÚNICA', 'NOTA_PROMISSORIA_');
    }

    /**
     * Imprime a Nota Promissória - Faturamento Vendedor
     */
    public function showSellerPromissoryNote($id)
    {
        return $this->renderBillingPromissoryPdf($id, 'sellerParcels', 'seller', 'NOTA PROMISSÓRIA - FATURAMENTO VENDEDOR', 'NOTA_PROMISSORIA_VENDEDOR_');
    }

    /**
     * Imprime a Nota Promissória - Faturamento Comprador
     */
    public function showBuyerPromissoryNote($id)
    {
        return $this->renderBillingPromissoryPdf($id, 'buyerParcels', 'buyer', 'NOTA PROMISSÓRIA - FATURAMENTO COMPRADOR', 'NOTA_PROMISSORIA_COMPRADOR_');
    }

    private function renderBillingPromissoryPdf($id, string $parcelKey, string $payerKey, string $title, string $prefix)
    {
        set_time_limit(0);

        $contract = Contract::with([
            'order.event.clauses',
            'order',
            'order.event.regulationClauses',
            'order.seller.address',
            'order.buyer.address',
            'order.animal.breed',
            'order.animal.coat',
            'order.paymentWay',
            'order.parcels',
            'order.sellerParcels',
            'order.buyerParcels',
        ])->findOrFail($id);

        $data = !empty($contract->snapshot)
            ? $this->prepareFromSnapshot($contract, 1)
            : $this->prepareFromDatabase($contract, 1);

        $data['promissoryTitle'] = $title;
        $data['payer'] = $data[$payerKey] ?? null;
        $data['activeParcels'] = $data[$parcelKey] ?? collect();

        $fileName = $prefix . $data['order']->number . '.pdf';

        return ReportFactory::getBasicPdf(
            'portrait',
            'reports.promissory-note-billing', // Blade dedicada a faturamento
            $data,
            $fileName
        );
    }

    /**
     * Monta os dados a partir do banco
     */
    private function prepareFromDatabase(Contract $contract, int $via): array
    {
        $order = $contract->order;
        $event = $order->event;
        $buyer = $order->buyer;
        $seller = $order->seller;
        $animal = $order->animal;

        $grossValue = (float) $order->gross_value;
        $discountValue = ($grossValue * (float) $order->discount_percentage) / 100;
        $netValue = $grossValue - $discountValue;

        $parcels = $order->parcels->sortBy('date');
        $sellerParcels = $order->sellerParcels ? $order->sellerParcels->sortBy('date') : collect();
        $buyerParcels = $order->buyerParcels ? $order->buyerParcels->sortBy('date') : collect();

        $installments = $parcels->count();
        $firstParcel = $parcels->first();

        $firstParcelValue = $firstParcel
            ? (float) $firstParcel->value
            : (float) $order->first_parcel_value;

        $firstDueDate = $firstParcel?->date
            ? Carbon::parse($firstParcel->date)
            : ($order->first_date ? Carbon::parse($order->first_date) : null);

        $paymentText = $this->buildPaymentText(
            order: $order,
            netValue: $netValue,
            parcels: $parcels,
            firstParcelValue: $firstParcelValue,
            firstDueDate: $firstDueDate,
        );

        $contractDate = $contract->generated_at
            ? Carbon::parse($contract->generated_at)
            : now();

        $eventBanner = null;
        if ($event?->banner_contract) {
            $bannerPath = public_path('storage/' . ltrim($event->banner_contract, '/'));
            if (file_exists($bannerPath)) {
                $eventBanner = $bannerPath;
            }
        }

        $boqueiraoLogo = public_path('img/logo_header_10_anos.png');
        $city = $seller->address->city ?? 'Uruguaiana';
        $state = $seller->address->state ?? 'RS';

        return [
            'title' => "CONTRATO DE VENDA - {$via}ª VIA",
            'via' => $via,
            'contract' => $contract,
            'order' => $order,
            'event' => $event,
            'buyer' => $buyer,
            'seller' => $seller,
            'animal' => $animal,
            'parcels' => $parcels,
            'sellerParcels' => $sellerParcels,
            'buyerParcels' => $buyerParcels,
            'grossValue' => $grossValue,
            'netValue' => $netValue,
            'discountValue' => $discountValue,
            'installments' => $installments,
            'firstParcelValue' => $firstParcelValue,
            'firstDueDate' => $firstDueDate,
            'paymentText' => $paymentText,
            'contractDate' => $contractDate,
            'eventBanner' => $eventBanner,
            'boqueiraoLogo' => $boqueiraoLogo,
            'contractCity' => "{$city} - {$state}",
            'fixedTexts' => $this->getFixedTexts($city, $state),
        ];
    }

    /**
     * Monta os dados a partir do Snapshot JSON
     */
    private function prepareFromSnapshot(Contract $contract, int $via): array
    {
        $snapshot = $contract->snapshot;

        $order = (object) $snapshot['order'];
        $parcels = collect($snapshot['parcels'] ?? [])->map(fn($p) => (object) $p)->sortBy('date');
        $sellerParcels = collect($snapshot['seller_parcels'] ?? [])->map(fn($p) => (object) $p)->sortBy('date');
        $buyerParcels = collect($snapshot['buyer_parcels'] ?? [])->map(fn($p) => (object) $p)->sortBy('date');

        $order->parcels = $parcels;
        $order->sellerParcels = $sellerParcels;
        $order->buyerParcels = $buyerParcels;

        $order->animalEvent = isset($snapshot['lote']) ? (object) $snapshot['lote'] : null;
        $order->paymentWay = isset($snapshot['payment_way']) ? (object) $snapshot['payment_way'] : null;

        $seller = json_decode(json_encode($snapshot['seller'] ?? []));
        $buyer = json_decode(json_encode($snapshot['buyer'] ?? []));
        $animal = json_decode(json_encode($snapshot['animal'] ?? []));
        $event = json_decode(json_encode($snapshot['event'] ?? []));

        // Se as cláusulas não existirem no snapshot (contratos antigos),
        // busca do evento no banco de dados como fallback:
        if (empty($event->clauses) && $contract->order && $contract->order->event) {
            $event->clauses = $contract->order->event->clauses;
        } else {
            // Converte em Collection para facilitar a iteração na Blade
            $event->clauses = collect($event->clauses ?? [])->sortBy('order');
        }

        if (empty($event->regulation_clauses) && $contract->order && $contract->order->event) {
            $event->regulation_clauses = $contract->order->event->regulationClauses;
        } else {
            $event->regulation_clauses = collect($event->regulation_clauses ?? [])->sortBy('order');
        }

        // Fallbacks Vendedor e Comprador
        if ($contract->order && $contract->order->seller) {
            $dbSeller = $contract->order->seller;
            if (empty($seller->whatsapp) && empty($seller->phone) && empty($seller->cellphone)) {
                $seller->whatsapp = $dbSeller->whatsapp ?? $dbSeller->phone ?? $dbSeller->cellphone ?? null;
            }
            if ($dbSeller->address) {
                if (!isset($seller->address) || is_null($seller->address)) {
                    $seller->address = (object) [];
                }
                $seller->address->street = $seller->address->street ?? $dbSeller->address->street ?? null;
                $seller->address->number = $seller->address->number ?? $dbSeller->address->number ?? $dbSeller->address->street_number ?? null;
                $seller->address->complement = $seller->address->complement ?? $dbSeller->address->complement ?? null;
                $seller->address->district = $seller->address->district ?? $dbSeller->address->district ?? null;
                $seller->address->city = $seller->address->city ?? $dbSeller->address->city ?? null;
                $seller->address->state = $seller->address->state ?? $dbSeller->address->state ?? null;
                $seller->address->postal_code = $seller->address->postal_code ?? $dbSeller->address->postal_code ?? null;
            }
        }

        if ($contract->order && $contract->order->buyer) {
            $dbBuyer = $contract->order->buyer;
            if (empty($buyer->whatsapp) && empty($buyer->phone) && empty($buyer->cellphone)) {
                $buyer->whatsapp = $dbBuyer->whatsapp ?? $dbBuyer->phone ?? $dbBuyer->cellphone ?? null;
            }
            if ($dbBuyer->address) {
                if (!isset($buyer->address) || is_null($buyer->address)) {
                    $buyer->address = (object) [];
                }
                $buyer->address->street = $buyer->address->street ?? $dbBuyer->address->street ?? null;
                $buyer->address->number = $buyer->address->number ?? $dbBuyer->address->number ?? $dbBuyer->address->street_number ?? null;
                $buyer->address->complement = $buyer->address->complement ?? $dbBuyer->address->complement ?? null;
                $buyer->address->district = $buyer->address->district ?? $dbBuyer->address->district ?? null;
                $buyer->address->city = $buyer->address->city ?? $dbBuyer->address->city ?? null;
                $buyer->address->state = $buyer->address->state ?? $dbBuyer->address->state ?? null;
                $buyer->address->postal_code = $buyer->address->postal_code ?? $dbBuyer->address->postal_code ?? null;
            }
        }

        $grossValue = (float) $order->gross_value;
        $discountValue = ($grossValue * (float) ($order->discount_percentage ?? 0)) / 100;
        $netValue = (float) ($order->net_value ?? ($grossValue - $discountValue));

        $installments = $order->total_installments;
        $firstParcel = $parcels->first();

        $firstParcelValue = $firstParcel
            ? (float) $firstParcel->value
            : (float) ($order->first_parcel_value ?? 0);

        $firstDueDate = isset($firstParcel->date)
            ? Carbon::parse($firstParcel->date)
            : (!empty($order->first_due_date) ? Carbon::parse($order->first_due_date) : null);

        $paymentText = $this->buildPaymentText(
            order: $order,
            netValue: $netValue,
            parcels: $parcels,
            firstParcelValue: $firstParcelValue,
            firstDueDate: $firstDueDate,
        );

        $contractDate = $contract->generated_at
            ? Carbon::parse($contract->generated_at)
            : now();

        $eventBanner = null;
        if (!empty($event->banner_contract)) {
            $bannerPath = public_path('storage/' . ltrim($event->banner_contract, '/'));
            if (file_exists($bannerPath)) {
                $eventBanner = $bannerPath;
            }
        }

        $boqueiraoLogo = public_path('img/logo_header_10_anos.png');
        $city = $seller->address->city ?? 'Uruguaiana';
        $state = $seller->address->state ?? 'RS';

        return [
            'title' => "CONTRATO DE VENDA - {$via}ª VIA",
            'via' => $via,
            'contract' => $contract,
            'order' => $order,
            'event' => $event,
            'buyer' => $buyer,
            'seller' => $seller,
            'animal' => $animal,
            'parcels' => $parcels,
            'sellerParcels' => $sellerParcels,
            'buyerParcels' => $buyerParcels,
            'grossValue' => $grossValue,
            'netValue' => $netValue,
            'discountValue' => $discountValue,
            'installments' => $installments,
            'firstParcelValue' => $firstParcelValue,
            'firstDueDate' => $firstDueDate,
            'paymentText' => $paymentText,
            'contractDate' => $contractDate,
            'eventBanner' => $eventBanner,
            'boqueiraoLogo' => $boqueiraoLogo,
            'contractCity' => "{$city} - {$state}",
            'fixedTexts' => $this->getFixedTexts($city, $state),
        ];
    }

    private function getFixedTexts(string $city, string $state): array
    {
        return [
            'delay' => 'Na hipótese de haver atraso no pagamento, de qualquer uma das parcelas do preço, constituirá o comprador em mora, independentemente de notificação, implicará no vencimento das demais antecipadamente, as quais serão corrigidas pelo IGP-M e acrescidas de juros de vencimento de mora à razão de 1% ao mês a contar do vencimento, e sendo assim implicará no protesto do presente título de dívida. Em caso de rescisão por inadimplemento do comprador, os valores já pagos não serão restituídos, ficando retidos pelo vendedor a título de cláusula penal compensatória e indenização por perdas e danos, sem prejuízo da cobrança de eventuais valores ainda pendentes.',
            'regulation' => 'Consideramos o comprador e o vendedor como conhecendo e aceitando todos os termos do regulamento deste Remate/Leilão e o conteúdo nele existente tendo validade como documento e servindo para sanar futuras dúvidas.',
            'default' => 'Fica também ajustado que, nos termos do art. 190 do Código de Processo Civil, em caso de inadimplemento de qualquer das parcelas previstas neste contrato, poderá o vendedor, a seu exclusivo critério, ingressar com ação de busca e apreensão do bem objeto deste instrumento, ou promover a execução dos valores devidos, conforme as disposições aqui estabelecidas, facultando-se ao vendedor a adoção do procedimento que melhor atender aos seus interesses.',
            'transfer' => 'A transferência do(s) animal(is), ou cota(s) dele, será realizada junto à ABCCC logo após a quitação total do(s) produto(s). Em caso de transferências dos mesmo(s) ainda com o contrato ainda em vigor, ambas partes SÃO DE ACORDO com inclusão de Reserva de Domínio no(s) animal(is), sendo liberada pelo Vendedor logo após a quitação total deste contrato.',
            'forum' => "Fica eleito o Foro da Comarca de {$city} ( {$state} ) para dirimir qualquer questão atinente a este contrato.",
            'closing' => 'E por assim estarem justos e contratados, firma o presente instrumento em duas vias de igual teor e forma.',
        ];
    }

    private function buildPaymentText(
        $order,
        float $netValue,
        $parcels,
        float $firstParcelValue,
        ?Carbon $firstDueDate
    ): string {
        $totalFormatted = number_format($netValue, 2, ',', '.');
        $totalInWordsMoney = $this->currencyInWords($netValue);

        // Se não houver parcelas cadastradas no banco, retorna fallback simples
        if ($parcels->isEmpty()) {
            return sprintf(
                'Valor total de R$ %s (%s), a ser pago conforme condições negociadas.',
                $totalFormatted,
                $totalInWordsMoney
            );
        }

        // 1. Obtém a forma de pagamento (ex: "2+2+46", "1+1+10", "20")
        $paymentWayName = is_array($order)
            ? ($order['payment_way']['name'] ?? null)
            : ($order->paymentWay->name ?? null);

        if (empty($paymentWayName)) {
            $paymentWayName = (string) $parcels->count();
        }

        // Processa os grupos do plano (ex: "2+2+46" -> [2, 2, 46])
        $rawParts = array_map('intval', explode('+', $paymentWayName));
        $parts = array_values(array_filter($rawParts, fn($val) => $val > 0));

        // Se a soma da regra do plano não bater com a quantidade real de parcelas gravadas,
        // ajusta o último grupo para englobar as parcelas restantes do banco.
        if (!empty($parts)) {
            $sumParts = array_sum($parts);
            if ($sumParts !== $parcels->count()) {
                $diff = $parcels->count() - $sumParts;
                $parts[count($parts) - 1] += $diff;
            }
        } else {
            $parts = [$parcels->count()];
        }

        $dueDay = (int) (is_array($order) ? ($order['due_day'] ?? 10) : ($order->due_day ?? 10));

        // Início padrão do texto
        $text = sprintf(
            'Valor total de R$ %s (%s), que serão pagos da seguinte maneira: ',
            $totalFormatted,
            $totalInWordsMoney
        );

        // Reindexa as parcelas para iteração exata por índice (0..N-1)
        $parcelsArray = $parcels->values();

        // CASO 1: Condição composta com parcelas agrupadas (ex: 2+2+46, 1+1+10)
        if (count($parts) > 1) {
            $entryGroups = array_slice($parts, 0, -1); // Grupos de entrada
            $remainingCount = end($parts);             // Quantidade do grupo final
            $clauses = [];
            $currentIndex = 0;

            foreach ($entryGroups as $index => $groupSize) {
                // Pega fatia exata de parcelas reais do banco para este grupo
                $groupParcels = $parcelsArray->slice($currentIndex, $groupSize);

                $groupTotalValue = $groupParcels->sum(fn($p) => (float) (is_array($p) ? $p['value'] : $p->value));
                $groupValueFormatted = number_format($groupTotalValue, 2, ',', '.');
                $groupValueInWords = $this->currencyInWords($groupTotalValue);

                $firstInGroup = $groupParcels->first();
                $dateRaw = is_array($firstInGroup) ? $firstInGroup['date'] : $firstInGroup->date;
                $formattedDate = Carbon::parse($dateRaw)->format('d/m/Y');

                $startParcelNum = $currentIndex + 1;

                if ($groupSize === 1) {
                    $label = sprintf(
                        'a parcela %d no valor de R$ %s (%s), com vencimento em %s',
                        $startParcelNum,
                        $groupValueFormatted,
                        $groupValueInWords,
                        $formattedDate
                    );
                } else {
                    $endParcelNum = $currentIndex + $groupSize;
                    $label = sprintf(
                        'as parcelas %d e %d perfazendo um valor de R$ %s (%s), com vencimento em %s',
                        $startParcelNum,
                        $endParcelNum,
                        $groupValueFormatted,
                        $groupValueInWords,
                        $formattedDate
                    );
                }

                if ($index === 0) {
                    $clauses[] = 'As ' . ltrim($label, 'as ');
                } else {
                    $clauses[] = $label;
                }

                $currentIndex += $groupSize;
            }

            // Conecta os blocos de entrada
            $text .= implode(', ', $clauses);

            // Parcelas restantes (último grupo) lidas do banco
            $remainingParcels = $parcelsArray->slice($currentIndex, $remainingCount);
            $sampleRemaining = $remainingParcels->first();
            $sampleValue = (float) (is_array($sampleRemaining) ? $sampleRemaining['value'] : $sampleRemaining->value);

            $remainingInWords = $this->numberInWords($remainingParcels->count());
            $unitValueFormatted = number_format($sampleValue, 2, ',', '.');
            $unitValueInWords = $this->currencyInWords($sampleValue);

            $text .= sprintf(
                ' e as demais %s parcelas mensais e consecutivas no valor de R$ %s (%s) cada',
                $remainingInWords,
                $unitValueFormatted,
                $unitValueInWords
            );
        } else {
            // CASO 2: Plano simples (sem "+", ex: 20 parcelas ou 1 parcela única)
            $totalParcelsCount = $parcelsArray->count();
            $firstParcelReal = $parcelsArray->first();

            $firstRealValue = (float) (is_array($firstParcelReal) ? $firstParcelReal['value'] : $firstParcelReal->value);
            $firstRealDateRaw = is_array($firstParcelReal) ? $firstParcelReal['date'] : $firstParcelReal->date;
            $firstRealDateFormatted = Carbon::parse($firstRealDateRaw)->format('d/m/Y');

            if ($totalParcelsCount === 1) {
                $text .= sprintf(
                    'parcela única no valor de R$ %s (%s), com vencimento em %s',
                    $totalFormatted,
                    $totalInWordsMoney,
                    $firstRealDateFormatted
                );
            } else {
                // Amostra o valor das parcelas seguintes (index 1 em diante)
                $secondParcelReal = $parcelsArray->get(1) ?? $firstParcelReal;
                $secondRealValue = (float) (is_array($secondParcelReal) ? $secondParcelReal['value'] : $secondParcelReal->value);

                $firstValueFormatted = number_format($firstRealValue, 2, ',', '.');
                $firstValueInWords = $this->currencyInWords($firstRealValue);

                $secondValueFormatted = number_format($secondRealValue, 2, ',', '.');
                $secondValueInWords = $this->currencyInWords($secondRealValue);

                $text .= sprintf(
                    'a primeira parcela no valor de R$ %s (%s) com vencimento em %s e as demais %s parcelas mensais e consecutivas no valor de R$ %s (%s) cada',
                    $firstValueFormatted,
                    $firstValueInWords,
                    $firstRealDateFormatted,
                    $this->numberInWords($totalParcelsCount - 1),
                    $secondValueFormatted,
                    $secondValueInWords
                );
            }
        }

        // Fechamento padrão
        $text .= sprintf(
            ', até a quitação do produto, sendo definida a data do dia %d de cada mês para o vencimento.',
            $dueDay
        );

        return $text;
    }

    /**
     * Auxiliar para formatar qualquer valor monetário em extenso com a biblioteca NumberToWords
     */
    private function currencyInWords(float $value): string
    {
        $dollars = (int) floor($value);
        $cents = (int) round(($value - $dollars) * 100);

        $words = $this->numberTransformer->toWords($dollars) . ($dollars === 1 ? ' real' : ' reais');

        if ($cents > 0) {
            $words .= ' e ' . $this->numberTransformer->toWords($cents) . ($cents === 1 ? ' centavo' : ' centavos');
        }

        return $words;
    }

    private function numberInWords(int $number): string
    {
        return $this->numberTransformer->toWords($number);
    }

    public function showRegulation($id)
    {
        set_time_limit(0);

        $contract = Contract::with([
            'order.event.clauses',
            'order',
            'order.event.regulationClauses',
            'order.seller.address',
            'order.buyer.address',
            'order.animal.breed',
            'order.animal.coat',
            'order.paymentWay',
            'order.parcels',
        ])->findOrFail($id);

        $data = !empty($contract->snapshot)
            ? $this->prepareFromSnapshot($contract, 1)
            : $this->prepareFromDatabase($contract, 1);

        $fileName = 'REGULAMENTO_' . $data['order']->number . '.pdf';

        return ReportFactory::getBasicPdf(
            'portrait',
            'reports.regulation',
            $data,
            $fileName
        );
    }

    /**
     * Pré-visualizações Individuais
     */
    public function previewPdf(Order $order, Request $request)
    {
        $via = $request->get('via', 1);
        $order->load([
            'event.clauses',
            'event.regulationClauses',
            'seller.address',
            'buyer.address',
            'animal.breed',
            'animalEvent',
            'paymentWay',
            'parcels',
            'sellerParcels',
            'buyerParcels',
        ]);

        $grossValue = (float) $order->gross_value;
        $discountValue = ($grossValue * (float) $order->discount_percentage) / 100;
        $netValue = $grossValue - $discountValue;

        // Usa as parcelas reais já cadastradas/geradas na Order
        $parcels = $order->parcels->sortBy('date');
        $sellerParcels = $order->sellerParcels ? $order->sellerParcels->sortBy('date') : collect();
        $buyerParcels = $order->buyerParcels ? $order->buyerParcels->sortBy('date') : collect();

        $installments = $parcels->count();
        $firstParcel = $parcels->first();

        // Evita recalcular valores se já existirem parcelas reais gravadas
        $firstParcelValue = $firstParcel
            ? (float) $firstParcel->value
            : (float) $order->first_parcel_value;

        $firstDueDate = $firstParcel?->date
            ? Carbon::parse($firstParcel->date)
            : ($order->first_date ? Carbon::parse($order->first_date) : null);

        // Passa a collection de parcelas correta para a construção do texto de pagamento
        $paymentText = $this->buildPaymentText(
            order: $order,
            netValue: $netValue,
            parcels: $parcels,
            firstParcelValue: $firstParcelValue,
            firstDueDate: $firstDueDate,
        );

        $city = $order->seller->address->city ?? 'Uruguaiana';
        $state = $order->seller->address->state ?? 'RS';

        $data = [
            'order' => $order,
            'event' => $order->event,
            'seller' => $order->seller,
            'buyer' => $order->buyer,
            'animal' => $order->animal,
            'parcels' => $parcels,
            'sellerParcels' => $sellerParcels,
            'buyerParcels' => $buyerParcels,
            'grossValue' => $grossValue,
            'netValue' => $netValue,
            'discountValue' => $discountValue,
            'installments' => $installments,
            'firstParcelValue' => $firstParcelValue,
            'firstDueDate' => $firstDueDate,
            'paymentText' => $paymentText,
            'contractDate' => now(),
            'via' => $via,
            'isPreview' => true,
            'title' => 'PRÉ-VISUALIZAÇÃO DE CONTRATO',
            'eventBanner' => $order->event && $order->event->banner_contract ? storage_path('app/public/' . $order->event->banner_contract) : null,
            'boqueiraoLogo' => public_path('img/logo_header_10_anos.png'),
            'contractCity' => "{$city} - {$state}",
            'fixedTexts' => $this->getFixedTexts($city, $state),
        ];

        return ReportFactory::getBasicPdf('portrait', 'reports.contract', $data, "previa_contrato_OS_{$order->number}_via_{$via}.pdf");
    }

    public function previewPromissoryPdf(Order $order)
    {
        return $this->renderPreviewPromissory($order, 'parcels', 'PRÉ-VISUALIZAÇÃO DE NOTA PROMISSÓRIA', 'previa_promissoria_OS_');
    }

    public function previewSellerPromissoryPdf(Order $order)
    {
        return $this->renderPreviewPromissory($order, 'sellerParcels', 'PRÉ-VISUALIZAÇÃO DE NOTA PROMISSÓRIA - FAT. VENDEDOR', 'previa_promissoria_vendedor_OS_');
    }

    public function previewBuyerPromissoryPdf(Order $order)
    {
        return $this->renderPreviewPromissory($order, 'buyerParcels', 'PRÉ-VISUALIZAÇÃO DE NOTA PROMISSÓRIA - FAT. COMPRADOR', 'previa_promissoria_comprador_OS_');
    }

    private function renderPreviewPromissory(Order $order, string $relation, string $title, string $filePrefix)
    {
        $order->load(['event', 'seller.address', 'buyer.address', 'animal.breed', 'animalEvent', 'paymentWay', $relation]);

        $data = [
            'order' => $order,
            'event' => $order->event,
            'seller' => $order->seller,
            'buyer' => $order->buyer,
            'animal' => $order->animal,
            'activeParcels' => $order->$relation,
            'promissoryTitle' => mb_strtoupper(str_replace('PRÉ-VISUALIZAÇÃO DE ', '', $title)),
            'isPreview' => true,
            'contractDate' => now(),
            'eventBanner' => $order->event && $order->event->banner_contract ? storage_path('app/public/' . $order->event->banner_contract) : null,
            'title' => $title,
        ];

        return ReportFactory::getBasicPdf('portrait', 'reports.promissory-note', $data, "{$filePrefix}{$order->number}.pdf");
    }

    public function previewRegulationPdf(Order $order)
    {
        $order->load(['event', 'seller.address', 'buyer.address', 'animal.breed', 'animalEvent', 'paymentWay', 'parcels']);

        $data = [
            'order' => $order,
            'event' => $order->event,
            'seller' => $order->seller,
            'buyer' => $order->buyer,
            'animal' => $order->animal,
            'isPreview' => true,
            'contractDate' => now(),
            'title' => 'PRÉ-VISUALIZAÇÃO DE REGULAMENTO',
            'eventBanner' => $order->event && $order->event->banner_contract ? storage_path('app/public/' . $order->event->banner_contract) : null,
            'boqueiraoLogo' => public_path('img/logo_header_10_anos.png'),
        ];

        return ReportFactory::getBasicPdf('portrait', 'reports.regulation', $data, "previa_regulamento_OS_{$order->number}.pdf");
    }

    /**
     * Imprime Pacote de Documentos Selecionados (Gerado/Oficial)
     */
    public function bundlePdf($id, Request $request)
    {
        set_time_limit(0);

        $contract = Contract::with([
            'order.event.clauses',
            'order',
            'order.event.regulationClauses',
            'order.seller.address',
            'order.seller',
            'order.buyer.address',
            'order.animal.breed',
            'order.animal.coat',
            'order.paymentWay',
            'order.parcels',
            'order.sellerParcels',
            'order.buyerParcels',
        ])->findOrFail($id);

        $data = !empty($contract->snapshot)
            ? $this->prepareFromSnapshot($contract, 1)
            : $this->prepareFromDatabase($contract, 1);

        $selectedDocs = $request->get('docs');
        if (is_string($selectedDocs)) {
            $selectedDocs = explode(',', $selectedDocs);
        }

        $data['selectedDocs'] = $selectedDocs ?? ['via1', 'via2', 'promissory', 'seller_promissory', 'buyer_promissory', 'regulation'];
        $data['title'] = 'DOCUMENTOS DO CONTRATO - OS ' . $data['order']->number;
        $data['isPreview'] = false;

        $fileName = 'PACOTE_COMPLETO_OS_' . $data['order']->number . '.pdf';

        return ReportFactory::getBasicPdf('portrait', 'reports.bundle', $data, $fileName);
    }

    /**
     * Pré-visualiza Pacote Selecionado
     */
    public function previewBundlePdf(Order $order, Request $request)
    {
        set_time_limit(0);

        $order->load([
            'event.clauses',
            'event.regulationClauses',
            'event',
            'seller.address',
            'buyer.address',
            'animal.breed',
            'animalEvent',
            'paymentWay',
            'parcels',
            'sellerParcels',
            'buyerParcels',
        ]);

        $event = $order->event;
        $seller = $order->seller;
        $buyer = $order->buyer;
        $animal = $order->animal;

        $grossValue = (float) $order->gross_value;
        $discountValue = ($grossValue * (float) $order->discount_percentage) / 100;
        $netValue = $grossValue - $discountValue;

        // Recupera as parcelas reais vinculadas
        $parcels = $order->parcels->sortBy('date');
        $sellerParcels = $order->sellerParcels ? $order->sellerParcels->sortBy('date') : collect();
        $buyerParcels = $order->buyerParcels ? $order->buyerParcels->sortBy('date') : collect();

        $installments = $parcels->count();
        $firstParcel = $parcels->first();

        $firstParcelValue = $firstParcel ? (float) $firstParcel->value : (float) $order->first_parcel_value;
        $firstDueDate = $firstParcel?->date ? Carbon::parse($firstParcel->date) : ($order->first_date ? Carbon::parse($order->first_date) : null);

        // Utiliza o mesmo construtor de texto padrão baseado nas parcelas reais
        $paymentText = $this->buildPaymentText(
            order: $order,
            netValue: $netValue,
            parcels: $parcels,
            firstParcelValue: $firstParcelValue,
            firstDueDate: $firstDueDate,
        );

        $city = $seller->address->city ?? 'Uruguaiana';
        $state = $seller->address->state ?? 'RS';

        $selectedDocs = $request->get('docs');
        if (is_string($selectedDocs)) {
            $selectedDocs = explode(',', $selectedDocs);
        }

        $data = [
            'order' => $order,
            'event' => $event,
            'seller' => $seller,
            'buyer' => $buyer,
            'animal' => $animal,
            'parcels' => $parcels,
            'sellerParcels' => $sellerParcels,
            'buyerParcels' => $buyerParcels,
            'selectedDocs' => $selectedDocs ?? ['via1', 'via2', 'promissory', 'seller_promissory', 'buyer_promissory', 'regulation'],
            'grossValue' => $grossValue,
            'netValue' => $netValue,
            'discountValue' => $discountValue,
            'installments' => $installments,
            'firstParcelValue' => $firstParcelValue,
            'firstDueDate' => $firstDueDate,
            'paymentText' => $paymentText,
            'contractDate' => now(),
            'isPreview' => true,
            'title' => 'PRÉ-VISUALIZAÇÃO - PACOTE SELECIONADO',
            'eventBanner' => $event && $event->banner_contract ? storage_path('app/public/' . $event->banner_contract) : null,
            'boqueiraoLogo' => public_path('img/logo_header_10_anos.png'),
            'contractCity' => "{$city} - {$state}",
            'fixedTexts' => $this->getFixedTexts($city, $state),
        ];

        return ReportFactory::getBasicPdf('portrait', 'reports.bundle', $data, "previa_pacote_OS_{$order->number}.pdf");
    }

    /**
     * Gera o PDF do Regulamento Geral diretamente a partir de um Evento
     */
    public function getEventRegulationPdf($eventId)
    {
        set_time_limit(0);

        $event = Event::with(['regulationClauses'])->findOrFail($eventId);

        $eventBanner = null;
        if ($event->banner_contract) {
            $bannerPath = public_path('storage/' . ltrim($event->banner_contract, '/'));
            if (file_exists($bannerPath)) {
                $eventBanner = $bannerPath;
            }
        }

        $boqueiraoLogo = public_path('img/logo_header_10_anos.png');

        $data = [
            'title' => 'REGULAMENTO DO EVENTO',
            'event' => $event,
            'eventBanner' => $eventBanner,
            'boqueiraoLogo' => $boqueiraoLogo,
            'contractDate' => now(),
            'contractCity' => $event->city ?? 'Uruguaiana - RS',
        ];

        $fileName = 'REGULAMENTO_EVENTO_' . \Illuminate\Support\Str::slug($event->name) . '.pdf';

        return ReportFactory::getBasicPdf(
            'portrait',
            'reports.event-regulation',
            $data,
            $fileName
        );
    }
}
