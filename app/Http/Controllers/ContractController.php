<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Order;
use App\Utils\ReportFactory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use NumberToWords\Exception\InvalidArgumentException;
use NumberToWords\NumberToWords;
use Barryvdh\DomPDF\PDF;

class ContractController extends Controller
{
    private $numberTransformer;

    public function __construct()
    {
        // Reutiliza uma única instância do conversor durante o ciclo do controller
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
            'order.event',
            'order.seller.address',
            'order.buyer.address',
            'order.animal.breed',
            'order.animal.coat',
            'order.paymentWay',
            'order.parcels',
        ])->findOrFail($id);

        $via = (int) $request->get('via', 1);

        // Se houver snapshot gravado, prioriza a leitura dele
        if (!empty($contract->snapshot)) {
            $data = $this->prepareFromSnapshot($contract, $via);
        } else {
            $data = $this->prepareFromDatabase($contract, $via);
        }

        $fileName = 'CONTRATO_' . $data['order']->number . '_VIA_' . $via . '.pdf';

        return ReportFactory::getBasicPdf(
            'portrait',
            'reports.contract',
            $data,
            $fileName
        );
    }

    /**
     * Imprime a Nota Promissória
     */
    public function showPromissoryNote($id)
    {
        set_time_limit(0);

        $contract = Contract::with([
            'order.event',
            'order.seller.address',
            'order.buyer.address',
            'order.animal.breed',
            'order.animal.coat',
            'order.paymentWay',
            'order.parcels',
        ])->findOrFail($id);

        if (!empty($contract->snapshot)) {
            $data = $this->prepareFromSnapshot($contract, 1);
        } else {
            $data = $this->prepareFromDatabase($contract, 1);
        }

        $fileName = 'NOTA_PROMISSORIA_' . $data['order']->number . '.pdf';

        return ReportFactory::getBasicPdf(
            'portrait',
            'reports.promissory-note',
            $data,
            $fileName
        );
    }

    /**
     * Monta os dados a partir do banco (Fallback ou geração em tempo real)
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
            installments: $installments,
            firstParcelValue: $firstParcelValue,
            firstDueDate: $firstDueDate,
        );

        $contractDate = $contract->generated_at
            ? Carbon::parse($contract->generated_at)
            : now();

        $eventBanner = null;
        if ($event?->banner_min) {
            $bannerPath = public_path('storage/' . ltrim($event->banner_min, '/'));
            if (file_exists($bannerPath)) {
                $eventBanner = $bannerPath;
            }
        }

        $boqueiraoLogo = public_path('img/logo_header_10_anos.png');

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
            'contractCity' => ($seller->address->city ?? 'Uruguaiana') . ' - ' . ($seller->address->state ?? 'RS'),
            'fixedTexts' => $this->getFixedTexts($seller->address->city ?? 'Uruguaiana', $seller->address->state ?? 'RS'),
        ];
    }

    /**
     * Monta os dados a partir do Snapshot JSON com fallback para o Banco caso faltem campos
     */
    private function prepareFromSnapshot(Contract $contract, int $via): array
    {
        $snapshot = $contract->snapshot;

        $order = (object) $snapshot['order'];
        $parcels = collect($snapshot['parcels'] ?? [])->map(fn($p) => (object) $p)->sortBy('date');
        $order->parcels = $parcels;
        $order->animalEvent = isset($snapshot['lote']) ? (object) $snapshot['lote'] : null;
        $order->paymentWay = isset($snapshot['payment_way']) ? (object) $snapshot['payment_way'] : null;

        $seller = json_decode(json_encode($snapshot['seller'] ?? []));
        $buyer = json_decode(json_encode($snapshot['buyer'] ?? []));
        $animal = json_decode(json_encode($snapshot['animal'] ?? []));
        $event = json_decode(json_encode($snapshot['event'] ?? []));

        // 🔹 FALLBACK SE FALTAR DADOS NO SNAPSHOT DO VENDEDOR
        if ($contract->order && $contract->order->seller) {
            $dbSeller = $contract->order->seller;

            // Garante Telefone / WhatsApp
            if (empty($seller->whatsapp) && empty($seller->phone) && empty($seller->cellphone)) {
                $seller->whatsapp = $dbSeller->whatsapp ?? $dbSeller->phone ?? $dbSeller->cellphone ?? null;
            }

            // Garante dados do Endereço (Rua, Número, Bairro, CEP, etc.)
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

        // 🔹 FALLBACK SE FALTAR DADOS NO SNAPSHOT DO COMPRADOR
        if ($contract->order && $contract->order->buyer) {
            $dbBuyer = $contract->order->buyer;

            // Garante Telefone / WhatsApp
            if (empty($buyer->whatsapp) && empty($buyer->phone) && empty($buyer->cellphone)) {
                $buyer->whatsapp = $dbBuyer->whatsapp ?? $dbBuyer->phone ?? $dbBuyer->cellphone ?? null;
            }

            // Garante dados do Endereço
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

        $installments = $parcels->count();
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
            installments: $installments,
            firstParcelValue: $firstParcelValue,
            firstDueDate: $firstDueDate,
        );

        $contractDate = $contract->generated_at
            ? Carbon::parse($contract->generated_at)
            : now();

        $eventBanner = null;
        if (!empty($event->banner_min)) {
            $bannerPath = public_path('storage/' . ltrim($event->banner_min, '/'));
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
        int $installments,
        float $firstParcelValue,
        ?Carbon $firstDueDate,
    ): string {
        $totalInWords = $this->moneyInWords($netValue);
        $installmentsInWords = $this->numberInWords($installments);
        $parcelValueInWords = $this->moneyInWords($firstParcelValue);

        $firstDueDateText = $firstDueDate
            ? $firstDueDate->locale('pt_BR')->translatedFormat('d \d\e F \d\e Y')
            : '';

        $text = sprintf(
            'R$ %s (%s), divididos em %s parcelas',
            number_format($netValue, 2, ',', '.'),
            $totalInWords,
            $installmentsInWords
        );

        if ($installments > 1) {
            $text .= sprintf(
                ' iguais, no valor de R$ %s (%s)',
                number_format($firstParcelValue, 2, ',', '.'),
                $parcelValueInWords
            );
        }

        if ($firstDueDateText) {
            $text .= sprintf(
                ', sendo o pagamento da primeira no dia %s',
                $firstDueDateText
            );
        }

        if (!empty($order->due_day)) {
            $text .= sprintf(
                ', e as demais parcelas mensais e consecutivas, com vencimento todo dia %d de cada mês',
                $order->due_day
            );
        }

        $text .= ', até a quitação do produto,';

        return $text;
    }

    private function moneyInWords(float $value): string
    {
        $integer = (int) floor($value);
        $cents = (int) round(($value - $integer) * 100);

        $result = $this->numberInWords($integer);
        $result .= $integer === 1 ? ' real' : ' reais';

        if ($cents > 0) {
            $result .= ' e ' . $this->numberInWords($cents);
            $result .= $cents === 1 ? ' centavo' : ' centavos';
        }

        return $result;
    }

    private function numberInWords(int $number): string
    {
        return $this->numberTransformer->toWords($number);
    }

    /**
     * Imprime o Regulamento do Remate
     */
    public function showRegulation($id)
    {
        set_time_limit(0);

        $contract = Contract::with([
            'order.event',
            'order.seller.address',
            'order.buyer.address',
            'order.animal.breed',
            'order.animal.coat',
            'order.paymentWay',
            'order.parcels',
        ])->findOrFail($id);

        if (!empty($contract->snapshot)) {
            $data = $this->prepareFromSnapshot($contract, 1);
        } else {
            $data = $this->prepareFromDatabase($contract, 1);
        }

        $fileName = 'REGULAMENTO_' . $data['order']->number . '.pdf';

        return ReportFactory::getBasicPdf(
            'portrait',
            'reports.regulation',
            $data,
            $fileName
        );
    }

    /**
     * Pré-visualiza o contrato antes de ser gerado/fechado
     */
    public function previewPdf(Order $order, Request $request)
    {
        $via = $request->get('via', 1);

        // Carrega as relações necessárias diretamente do Order
        $order->load([
            'event',
            'seller.address',
            'buyer.address',
            'animal.breed',
            'animalEvent',
            'paymentWay',
            'parcels',
        ]);

        $event = $order->event;
        $seller = $order->seller;
        $buyer = $order->buyer;
        $animal = $order->animal;

        $grossValue = (float) $order->gross_value;
        $discountValue = ($grossValue * (float) $order->discount_percentage) / 100;
        $netValue = $grossValue - $discountValue;

        $parcels = $order->parcels->sortBy('date');
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
            installments: $installments,
            firstParcelValue: $firstParcelValue,
            firstDueDate: $firstDueDate,
        );

        $data = [
            'order' => $order,
            'event' => $event,
            'seller' => $seller,
            'buyer' => $buyer,
            'animal' => $animal,
            'paymentText' => $paymentText,
            'contractDate' => now(),
            'via' => $via,
            'isPreview' => true,
            'title' => 'PRÉ-VISUALIZAÇÃO DE CONTRATO',
            'eventBanner' => $event && $event->banner_min ? storage_path('app/public/' . $event->banner_min) : null,
            'boqueiraoLogo' => public_path('img/logo_header_10_anos.png'),
        ];

        return ReportFactory::getBasicPdf(
            'portrait',
            'reports.contract', // caminho da sua blade
            $data,
            "previa_contrato_OS_{$order->number}_via_{$via}.pdf"
        );
    }

    /**
     * Pré-visualiza a Nota Promissória antes de ser gerada/fechada
     */
    public function previewPromissoryPdf(Order $order)
    {
        // Carrega as relações necessárias diretamente do Order
        $order->load([
            'event',
            'seller.address',
            'buyer.address',
            'animal.breed',
            'animalEvent',
            'paymentWay',
            'parcels',
        ]);

        $event = $order->event;
        $seller = $order->seller;
        $buyer = $order->buyer;
        $animal = $order->animal;

        $data = [
            'order' => $order,
            'event' => $event,
            'seller' => $seller,
            'buyer' => $buyer,
            'animal' => $animal,
            'isPreview' => true,
            'contractDate' => now(),
            'title' => 'PRÉ-VISUALIZAÇÃO DE NOTA PROMISSÓRIA',
        ];

        return ReportFactory::getBasicPdf(
            'portrait',
            'reports.promissory-note', // ajuste para a sua blade de promissória
            $data,
            "previa_promissoria_OS_{$order->number}.pdf"
        );
    }

    /**
     * Pré-visualiza o Regulamento
     */
    public function previewRegulationPdf(Order $order)
    {
        $order->load([
            'event',
            'seller.address',
            'buyer.address',
            'animal.breed',
            'animalEvent',
            'paymentWay',
            'parcels',
        ]);

        $data = [
            'order' => $order,
            'event' => $order->event,
            'seller' => $order->seller,
            'buyer' => $order->buyer,
            'animal' => $order->animal,
            'isPreview' => true,
            'contractDate' => now(),
            'title' => 'PRÉ-VISUALIZAÇÃO DE REGULAMENTO',
            'eventBanner' => $order->event && $order->event->banner_min ? storage_path('app/public/' . $order->event->banner_min) : null,
            'boqueiraoLogo' => public_path('img/logo_header_10_anos.png'),
        ];

        return ReportFactory::getBasicPdf(
            'portrait',
            'reports.regulation',
            $data,
            "previa_regulamento_OS_{$order->number}.pdf"
        );
    }

    /**
     * Imprime Todos os Documentos Agrupados em uma única View
     */
    public function bundlePdf($id)
    {
        set_time_limit(0);

        $contract = Contract::with([
            'order.event',
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

        $data['title'] = 'DOCUMENTOS DO CONTRATO - OS ' . $data['order']->number;
        $data['isPreview'] = false;

        $fileName = 'PACOTE_COMPLETO_OS_' . $data['order']->number . '.pdf';

        return ReportFactory::getBasicPdf(
            'portrait',
            'reports.bundle',
            $data,
            $fileName
        );
    }

    /**
     * Pré-visualiza Todos os Documentos Agrupados em uma única View
     */
    public function previewBundlePdf(Order $order)
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
        ]);

        $event = $order->event;
        $seller = $order->seller;
        $buyer = $order->buyer;
        $animal = $order->animal;

        $grossValue = (float) $order->gross_value;
        $discountValue = ($grossValue * (float) $order->discount_percentage) / 100;
        $netValue = $grossValue - $discountValue;

        $parcels = $order->parcels->sortBy('date');
        $installments = $parcels->count();
        $firstParcel = $parcels->first();

        $firstParcelValue = $firstParcel ? (float) $firstParcel->value : (float) $order->first_parcel_value;
        $firstDueDate = $firstParcel?->date ? Carbon::parse($firstParcel->date) : ($order->first_date ? Carbon::parse($order->first_date) : null);

        $paymentText = $this->buildPaymentText($order, $netValue, $installments, $firstParcelValue, $firstDueDate);

        // Cidade e Estado do Vendedor (fallback para 'Uruguaiana - RS')
        $city = $seller->address->city ?? 'Uruguaiana';
        $state = $seller->address->state ?? 'RS';

        $data = [
            'order' => $order,
            'event' => $event,
            'seller' => $seller,
            'buyer' => $buyer,
            'animal' => $animal,
            'parcels' => $parcels,
            'grossValue' => $grossValue,
            'netValue' => $netValue,
            'discountValue' => $discountValue,
            'installments' => $installments,
            'firstParcelValue' => $firstParcelValue,
            'firstDueDate' => $firstDueDate,
            'paymentText' => $paymentText,
            'contractDate' => now(),
            'isPreview' => true,
            'title' => 'PRÉ-VISUALIZAÇÃO - PACOTE COMPLETO',
            'eventBanner' => $event && $event->banner_min ? storage_path('app/public/' . $event->banner_min) : null,
            'boqueiraoLogo' => public_path('img/logo_header_10_anos.png'),

            // 🔹 VARIÁVEIS ADICIONADAS PARA EVITAR O ERRO:
            'contractCity' => "{$city} - {$state}",
            'fixedTexts' => $this->getFixedTexts($city, $state),
        ];

        return ReportFactory::getBasicPdf(
            'portrait',
            'reports.bundle',
            $data,
            "previa_pacote_completo_OS_{$order->number}.pdf"
        );
    }
}
