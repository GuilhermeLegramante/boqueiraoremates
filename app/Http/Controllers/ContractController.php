<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Utils\ReportFactory;
use Carbon\Carbon;
use NumberToWords\NumberToWords;
use NumberToWords\Exception\InvalidArgumentException;

class ContractController extends Controller
{
    private $numberTransformer;

    public function __construct()
    {
        // Reutiliza uma única instância do conversor durante o ciclo do controller
        $numberToWords = new NumberToWords();
        $this->numberTransformer = $numberToWords->getNumberTransformer('pt_BR');
    }

    public function getPdf_teste_tempo($id)
    {
        $timeStart = microtime(true);
        $checkpoints = [];

        set_time_limit(0);

        // 1. Query no Banco
        $contract = Contract::with([
            'order.event',
            'order.seller',
            'order.buyer',
            'order.animal',
            'order.paymentWay',
            'order.parcels',
        ])->findOrFail($id);

        $checkpoints['1_database_query'] = round(microtime(true) - $timeStart, 3);
        $t1 = microtime(true);

        // 2. Processamento dos Dados em Memória
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

        $boqueiraoLogo = public_path('img/logo_completa.png');
        $fileName = 'CONTRATO_' . $order->number . '.pdf';

        $args = [
            'title' => 'CONTRATO DE VENDA',
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
            'contractCity' => 'Uruguaiana - RS',
            'fixedTexts' => [
                'delay' => 'Na hipótese de haver atraso no pagamento...',
                'regulation' => 'Consideramos o comprador e o vendedor...',
                'default' => 'Fica também ajustado que...',
                'transfer' => 'A transferência do(s) animal(is)...',
                'forum' => 'Fica eleito o Foro da Comarca de Uruguaiana...',
                'closing' => 'E por assim estarem justos...',
            ],
        ];

        $checkpoints['2_data_processing'] = round(microtime(true) - $t1, 3);
        $t2 = microtime(true);

        // 3. Renderização da View Blade (HTML puro)
        $html = view('reports.contract', $args)->render();

        $checkpoints['3_blade_rendering'] = round(microtime(true) - $t2, 3);
        $t3 = microtime(true);

        // 4. Teste de geração do PDF
        $pdf = ReportFactory::getBasicPdf(
            'portrait',
            'reports.contract',
            $args,
            $fileName
        );

        $checkpoints['4_pdf_generation'] = round(microtime(true) - $t3, 3);
        $checkpoints['TOTAL_TIME'] = round(microtime(true) - $timeStart, 3);

        // Manda o resultado na tela pra ver onde está o gargalo
        dd($checkpoints);
    }

    public function getPdf($id)
    {
        set_time_limit(0);

        $contract = Contract::with([
            'order.event',
            'order.seller',
            'order.buyer',
            'order.animal',
            'order.paymentWay',
            'order.parcels',
        ])->findOrFail($id);

        $order = $contract->order;
        $event = $order->event;

        $buyer = $order->buyer;
        $seller = $order->seller;
        $animal = $order->animal;

        $grossValue = (float) $order->gross_value;
        $discountValue = ($grossValue * (float) $order->discount_percentage) / 100;
        $netValue = $grossValue - $discountValue;

        // OTIMIZAÇÃO: Usa a coleção carregada via eager loading sem bater no banco
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

        $boqueiraoLogo = public_path('img/logo_completa.png');
        $fileName = 'CONTRATO_' . $order->number . '.pdf';

        $args = [
            'title' => 'CONTRATO DE VENDA',
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
            'contractCity' => 'Uruguaiana - RS',
            'fixedTexts' => [
                'delay' => 'Na hipótese de haver atraso no pagamento, de qualquer uma das parcelas do preço, constituirá o comprador em mora, independentemente de notificação, implicará no vencimento das demais antecipadamente, as quais serão corrigidas pelo IGP-M e acrescidas de juros de vencimento de mora à razão de 1% ao mês a contar do vencimento, e sendo assim implicará no protesto do presente título de dívida. Em caso de rescisão por inadimplemento do comprador, os valores já pagos não serão restituídos, ficando retidos pelo vendedor a título de cláusula penal compensatória e indenização por perdas e danos, sem prejuízo da cobrança de eventuais valores ainda pendentes.',
                'regulation' => 'Consideramos o comprador e o vendedor como conhecendo e aceitando todos os termos do regulamento deste Remate/Leilão e o conteúdo nele existente tendo validade como documento e servindo para sanar futuras dúvidas.',
                'default' => 'Fica também ajustado que, nos termos do art. 190 do Código de Processo Civil, em caso de inadimplemento de qualquer das parcelas previstas neste contrato, poderá o vendedor, a seu exclusivo critério, ingressar com ação de busca e apreensão do bem objeto deste instrumento, ou promover a execução dos valores devidos, conforme as disposições aqui estabelecidas, facultando-se ao vendedor a adoção do procedimento que melhor atender aos seus interesses.',
                'transfer' => 'A transferência do(s) animal(is), ou cota(s) dele, será realizada junto à ABCCC logo após a quitação total do(s) produto(s). Em caso de transferências dos mesmo(s) ainda com o contrato ainda em vigor, ambas partes SÃO DE ACORDO com inclusão de Reserva de Domínio no(s) animal(is), sendo liberada pelo Vendedor logo após a quitação total deste contrato.',
                'forum' => 'Fica eleito o Foro da Comarca de Uruguaiana (RS) para dirimir qualquer questão atinente a este contrato.',
                'closing' => 'E por assim estarem justos e contratados, firma o presente instrumento em duas vias de igual teor e forma.',
            ],
        ];

        return ReportFactory::getBasicPdf(
            'portrait',
            'reports.contract',
            $args,
            $fileName
        );
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

        if ($order->due_day) {
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
}
