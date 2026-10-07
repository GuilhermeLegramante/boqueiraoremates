<?php

namespace App\Http\Controllers;

use App\Models\Contract;
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

        // 1. Obtém a forma de pagamento (ex: "2+2+46", "1+1+10", "20")
        $paymentWayName = is_array($order)
            ? ($order['payment_way']['name'] ?? null)
            : ($order->paymentWay->name ?? null);

        if (empty($paymentWayName)) {
            $paymentWayName = (string) (count($parcels) > 0 ? count($parcels) : 1);
        }

        // Processa os grupos do plano (ex: "2+2+46" -> [2, 2, 46])
        $rawParts = array_map('intval', explode('+', $paymentWayName));
        $parts = array_values(array_filter($rawParts, fn($val) => $val > 0));

        $totalInstallments = array_sum($parts);

        // Valor da parcela individual base
        $unitParcelValue = (float) (is_array($order) ? ($order['parcel_value'] ?? 0) : ($order->parcel_value ?? 0));
        if ($unitParcelValue <= 0 && $totalInstallments > 0) {
            $unitParcelValue = $netValue / $totalInstallments;
        }

        // Data de referência e dia de vencimento
        $currentDate = $firstDueDate ? $firstDueDate->copy() : now();
        $dueDay = (int) (is_array($order) ? ($order['due_day'] ?? $currentDate->format('d')) : ($order->due_day ?? $currentDate->format('d')));

        // Início padrão do texto
        $text = sprintf(
            'Valor total de R$ %s (%s), que serão pagos da seguinte maneira: ',
            $totalFormatted,
            $totalInWordsMoney
        );

        // CASO 1: Condição composta com parcelas agrupadas (ex: 2+2+46, 1+1+10)
        if (count($parts) > 1) {
            $entryGroups = array_slice($parts, 0, -1); // Grupos de entrada
            $remainingInstallments = end($parts);       // Parcelas vincendas finais

            $clauses = [];
            $currentParcelNum = 1;

            foreach ($entryGroups as $index => $groupSize) {
                $formattedDate = $currentDate->format('d/m/Y');
                $groupTotalValue = $unitParcelValue * $groupSize;
                $groupValueFormatted = number_format($groupTotalValue, 2, ',', '.');
                $groupValueInWords = $this->currencyInWords($groupTotalValue);

                if ($groupSize === 1) {
                    // Parcela individual (ex: a parcela 1 no valor de R$ 9.916,67 (nove mil...))
                    $label = sprintf(
                        'a parcela %d no valor de R$ %s (%s), com vencimento em %s',
                        $currentParcelNum,
                        $groupValueFormatted,
                        $groupValueInWords,
                        $formattedDate
                    );
                    $currentParcelNum += 1;
                } else {
                    // Parcelas agrupadas (ex: as parcelas 1 e 2 perfazendo um valor de R$ 740,00 (setecentos e quarenta reais...))
                    $endParcelNum = $currentParcelNum + $groupSize - 1;
                    $label = sprintf(
                        'as parcelas %d e %d perfazendo um valor de R$ %s (%s), com vencimento em %s',
                        $currentParcelNum,
                        $endParcelNum,
                        $groupValueFormatted,
                        $groupValueInWords,
                        $formattedDate
                    );
                    $currentParcelNum += $groupSize;
                }

                if ($index === 0) {
                    $clauses[] = 'As ' . ltrim($label, 'as ');
                } else {
                    $clauses[] = $label;
                }

                // Avança para o próximo mês
                $currentDate->addMonth();
                $currentDate->day(min($dueDay, $currentDate->daysInMonth));
            }

            // Conecta os blocos de entrada
            $text .= implode(', ', $clauses);

            // Adiciona as parcelas mensais restantes
            $remainingInWords = $this->numberInWords($remainingInstallments);
            $unitValueFormatted = number_format($unitParcelValue, 2, ',', '.');
            $unitValueInWords = $this->currencyInWords($unitParcelValue);

            $text .= sprintf(
                ' e as demais %s parcelas mensais e consecutivas no valor de R$ %s (%s) cada',
                $remainingInWords,
                $unitValueFormatted,
                $unitValueInWords
            );
        } else {
            // CASO 2: Parcelamento simples (ex: 20 parcelas diretas ou parcela única)
            $unitValueFormatted = number_format($unitParcelValue, 2, ',', '.');
            $unitValueInWords = $this->currencyInWords($unitParcelValue);

            if ($totalInstallments === 1) {
                $text .= sprintf(
                    'parcela única no valor de R$ %s (%s), com vencimento em %s',
                    $totalFormatted,
                    $totalInWordsMoney,
                    $currentDate->format('d/m/Y')
                );
            } else {
                $text .= sprintf(
                    'a primeira parcela no valor de R$ %s (%s) com vencimento em %s e as demais %s parcelas mensais e consecutivas no valor de R$ %s (%s) cada',
                    $unitValueFormatted,
                    $unitValueInWords,
                    $currentDate->format('d/m/Y'),
                    $this->numberInWords($totalInstallments - 1),
                    $unitValueFormatted,
                    $unitValueInWords
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
        $order->load(['event.clauses', 'event.regulationClauses', 'seller.address', 'buyer.address', 'animal.breed', 'animalEvent', 'paymentWay', 'parcels']);

        $grossValue = (float) $order->gross_value;
        $discountValue = ($grossValue * (float) $order->discount_percentage) / 100;
        $netValue = $grossValue - $discountValue;

        $parcels = $order->parcels->sortBy('date');
        $installments = $parcels->count();
        $firstParcel = $parcels->first();

        $firstParcelValue = $firstParcel ? (float) $firstParcel->value : (float) $order->first_parcel_value;
        $firstDueDate = $firstParcel?->date ? Carbon::parse($firstParcel->date) : ($order->first_date ? Carbon::parse($order->first_date) : null);

        $paymentText = $this->buildPaymentText($order, $netValue, $installments, $firstParcelValue, $firstDueDate);

        $data = [
            'order' => $order,
            'event' => $order->event,
            'seller' => $order->seller,
            'buyer' => $order->buyer,
            'animal' => $order->animal,
            'paymentText' => $paymentText,
            'contractDate' => now(),
            'via' => $via,
            'isPreview' => true,
            'title' => 'PRÉ-VISUALIZAÇÃO DE CONTRATO',
            'eventBanner' => $order->event && $order->event->banner_contract ? storage_path('app/public/' . $order->event->banner_contract) : null,
            'boqueiraoLogo' => public_path('img/logo_header_10_anos.png'),
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

        $parcels = $order->parcels->sortBy('date');
        $sellerParcels = $order->sellerParcels ? $order->sellerParcels->sortBy('date') : collect();
        $buyerParcels = $order->buyerParcels ? $order->buyerParcels->sortBy('date') : collect();

        $installments = $parcels->count();
        $firstParcel = $parcels->first();

        $firstParcelValue = $firstParcel ? (float) $firstParcel->value : (float) $order->first_parcel_value;
        $firstDueDate = $firstParcel?->date ? Carbon::parse($firstParcel->date) : ($order->first_date ? Carbon::parse($order->first_date) : null);

        $paymentText = $this->buildPaymentText($order, $netValue, $installments, $firstParcelValue, $firstDueDate);

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
}
