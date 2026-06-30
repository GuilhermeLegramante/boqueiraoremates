<?php

namespace App\Filament\Traits;

use App\Models\BuyerParcel;
use App\Models\Parcel;
use App\Models\PaymentWay;
use App\Models\SellerParcel;
use App\Utils\BuyerParcelsVerification;
use App\Utils\ParcelsVerification;
use App\Utils\SellerParcelsVerification;
use Filament\Notifications\Notification;

trait WithParcels
{
    public array $parcels = [];
    public array $values = [];
    public array $parcelsDates = [];
    public float $sum = 0;
    public bool $showParcels = false;
    public bool $showParcelsEdition = false;

    public $dateTest;

    public array $buyerParcels = [];
    public array $buyerValues = [];
    public array $buyerParcelsDates = [];
    public float $buyerSum = 0;
    public bool $showBuyerParcels = false;

    public array $sellerParcels = [];
    public array $sellerValues = [];
    public array $sellerParcelsDates = [];
    public float $sellerSum = 0;
    public bool $showSellerParcels = false;

    public $parcelsQuantity;


    public function resolveParcels_OLD(): void
    {
        $data = $this->form->getState();

        $baseDate = explode('-', $data['first_due_date']);

        $parcel = [];
        $month = intval($baseDate[1]);
        $year = intval($baseDate[0]);
        $day = intval($baseDate[2]);

        $this->sum = doubleval($data['first_parcel_value']);
        $this->parcels = [];
        $this->values = [];

        $paymentWay = PaymentWay::find($data['payment_way_id']);
        $parcelsParts = explode("+", $paymentWay->name);
        array_pop($parcelsParts); // Remove a última posição (parcelas iguais ex. 2+2+46, deixa só as duas primeiras partes)

        $parcelCounter = 0;

        $parcelsRemains = 0;

        $parcels = 0;

        $this->parcelsQuantity = ParcelsVerification::getMultiplier($data['payment_way_id']);

        // Montando as primeiras parcelas da fórmula
        if (count($parcelsParts) > 1) {
            $parcelCounter += intval($parcelsParts[0]);

            for ($i = 0; $i < count($parcelsParts); $i++) {
                $year = $year == 0 ? now()->format('Y') : $year;

                $parcels = intval($parcelsParts[$i]);

                $parcel['ord'] = $parcelCounter . '-' . $parcels + $parcelCounter - 1 . '/' . $this->parcelsQuantity; // Ex: 1-2/50 , 1-3/50, etc.
                $parcel['date'] = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . $day;
                $this->parcelsDates[$i] = $parcel['date'];

                if ($i > 1) { // Para casos como 2+2+2+44, estava ficando 3-4/50, 4-5/50 o correto é 5-6/50
                    $parcelCounter++;
                    $parcel['ord'] = $parcelCounter . '-' . $parcels + $parcelCounter - 1 . '/' . $this->parcelsQuantity; // Ex: 1-2/50 , 1-3/50, etc.
                }


                $parcelValue = floatval($data['parcel_value']) * intval($parcelsParts[$i]);
                $this->values[$i] = number_format($parcelValue, 2);

                if (intval($month) <= 11) {
                    $month++;
                } else {
                    $month = 1;
                    $year++;
                }

                $parcelCounter = $parcelCounter + 1;

                if ($i > 0) {
                    array_push($this->parcels, $parcel);

                    $parcelsRemains += intval($parcelsParts[$i]);
                }
            }

            // Para 2+2+8, deixa só 1 posição, para 2+2+2+44 deixa 2
            $parcelIndexToPivot = count($parcelsParts) - 1;

            // Garante que só extraímos o número correto de elementos
            if ($parcelIndexToPivot > 0) {
                $this->values = array_slice($this->values, -$parcelIndexToPivot);
            } else {
                $this->values = []; // Ou outra lógica para tratar o caso de índice 0 ou negativo
            }
        }

        for ($i = intval($parcelsParts[0]); $i < floatval($this->parcelsQuantity); $i++) {
            // $day = str_pad(floatval($data['due_day']), 2, '0', STR_PAD_LEFT);
            $year = $year == 0 ? now()->format('Y') : $year;
            // $month = ($month == 1 && $year == now()->format('Y')) ? now()->addMonths(1)->format('n') : $month;

            $parcel['ord'] = $i + 1 . '/' . $this->parcelsQuantity;
            $parcel['date'] = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) .  '-' . $day;
            $this->parcelsDates[$i] = $parcel['date'];

            array_push($this->values, number_format($data['parcel_value'], 2));

            $this->sum += doubleval($data['parcel_value']);

            if (intval($month) <= 11) {
                $month++;
                $month = str_pad($month, 2, '0', STR_PAD_LEFT);
            } else {
                $month = 1;
                $month = str_pad($month, 2, '0', STR_PAD_LEFT);
                $year++;
            }

            array_push($this->parcels, $parcel);
        }


        /*
         Remover parcelas "do meio" em caso de parcelamento múltiplo ex. 2+2+8, 
         */
        $removedPosition = count($parcelsParts) - 1; // Posição a ser removida ex. 2+2+9 vai remover a posição 1, se for 2+2+2+44 a pos 2

        array_splice($this->parcels, $removedPosition, $parcelsRemains);
        array_splice($this->values, $removedPosition, $parcelsRemains);
        array_splice($this->parcelsDates, $removedPosition, $parcelsRemains);

        // Inclui a "entrada" nas parcelas
        if (intval($parcelsParts[0]) > 0) {
            $this->resolveFirstPayment(intval($parcelsParts[0]), floatval($data['first_parcel_value']), $this->parcelsQuantity); // parcelsParts[0] é a qtde de parcelas da entrada
        }

        // Tira a "vírgula" de valores maior que 1000
        for ($i = 0; $i < count($this->values); $i++) {
            $this->values[$i] = str_replace(",", "", $this->values[$i]);
        }

        $this->showParcels = true;

        $this->adjustMonths();
    }

    public function resolveParcels(): void
    {
        // Obtém todos os dados preenchidos no formulário
        $data = $this->form->getState();

        // Converte a data do primeiro vencimento (YYYY-MM-DD) em ano, mês e dia
        [$year, $month, $day] = array_map(
            'intval',
            explode('-', $data['first_due_date'])
        );

        // Busca a forma de pagamento selecionada
        // Ex.: "2+48", "0+20", "20", "2+2+2+2"
        $paymentWay = PaymentWay::find($data['payment_way_id']);

        // Divide a forma de pagamento em grupos
        // Ex.: "2+48" => [2,48]
        // Ex.: "0+20" => [0,20]
        // Ex.: "2+2+2" => [2,2,2]
        $groups = array_map('intval', explode('+', $paymentWay->name));

        // Remove grupos iguais a zero
        // Isso faz com que "0+20" vire simplesmente [20]
        $groups = array_values(array_filter(
            $groups,
            fn($value) => $value > 0
        ));

        // Se por algum motivo não existir nenhum grupo, apenas encerra
        if (empty($groups)) {
            return;
        }

        // Valor individual de cada parcela
        $parcelValue = (float) ($data['parcel_value'] ?? 0);

        // Soma todas as parcelas da forma de pagamento
        // Ex.: [2,48] = 50 parcelas
        $totalParcels = array_sum($groups);

        // Limpa todas as listas antes de recalcular
        $this->parcels = [];
        $this->values = [];
        $this->parcelsDates = [];
        $this->sum = 0;

        // Número da próxima parcela que será criada
        $currentParcel = 1;

        // Índice do último grupo
        $lastIndex = count($groups) - 1;

        // Percorre cada grupo da forma de pagamento
        foreach ($groups as $index => $groupSize) {

            // Verifica se estamos processando o último grupo
            $isLastGroup = ($index === $lastIndex);

            // Obtém o grupo anterior (caso exista)
            $previousGroup = $groups[$index - 1] ?? null;

            /**
             * Decide se este grupo será expandido.
             *
             * Expandir significa criar uma linha para cada parcela.
             *
             * Exemplo:
             * 20
             *   1/20
             *   2/20
             *   ...
             *   20/20
             *
             * Agrupar significa criar apenas uma linha.
             *
             * Exemplo:
             * 2 parcelas
             *   1-2/50
             */
            $expandGroup = false;

            // Caso exista apenas um grupo
            //
            // Exemplo:
            // 20
            // 60
            //
            // Sempre expandimos todas as parcelas.
            if (count($groups) === 1) {

                $expandGroup = true;
            }

            // Caso seja o último grupo E seja maior que o grupo anterior.
            //
            // Exemplos:
            // 2+48
            // 5+10
            // 1+59
            //
            // Nesse cenário entendemos que o último grupo representa
            // as parcelas mensais do financiamento.
            elseif (
                $isLastGroup &&
                $previousGroup !== null &&
                $groupSize > $previousGroup
            ) {

                $expandGroup = true;
            }

            /**
             * ===========================================================
             * EXPANDE O GRUPO
             * ===========================================================
             *
             * Cria uma linha para cada parcela.
             */
            if ($expandGroup) {

                for ($i = 0; $i < $groupSize; $i++) {

                    // Ex.: 3/50
                    $ord = "{$currentParcel}/{$totalParcels}";

                    // Apenas a primeira parcela recebe "(Ent.)"
                    if ($currentParcel === 1) {
                        $ord .= " (Ent.)";
                    }

                    $this->pushParcel(
                        $ord,
                        $year,
                        $month,
                        $day,
                        $parcelValue
                    );

                    $currentParcel++;
                }

                // Como este grupo já foi tratado,
                // passamos para o próximo.
                continue;
            }

            /**
             * ===========================================================
             * AGRUPA O GRUPO
             * ===========================================================
             */

            // Caso o grupo possua apenas uma parcela
            if ($groupSize === 1) {

                // Ex.: 1/60
                $ord = "{$currentParcel}/{$totalParcels}";

                if ($currentParcel === 1) {
                    $ord .= " (Ent.)";
                }

                $value = $parcelValue;
            }

            // Grupo com mais de uma parcela
            else {

                // Ex.: 3-4/50
                $start = $currentParcel;
                $end = $currentParcel + $groupSize - 1;

                $ord = "{$start}-{$end}/{$totalParcels}";

                if ($start === 1) {
                    $ord .= " (Ent.)";
                }

                // Soma o valor das parcelas agrupadas
                $value = $parcelValue * $groupSize;
            }

            // Adiciona apenas uma linha representando o grupo
            $this->pushParcel(
                $ord,
                $year,
                $month,
                $day,
                $value
            );

            // Avança o contador das parcelas
            $currentParcel += $groupSize;
        }

        // Recalcula os meses de vencimento das parcelas
        $this->adjustMonths();

        // Exibe a tabela de parcelas
        $this->showParcels = true;
    }
    
    private function pushParcel(string $ord, int &$year, int &$month, int $day, ?float $value): void
    {
        $date = sprintf('%04d-%02d-%02d', $year, $month, $day);

        $this->parcels[] = [
            'ord'   => $ord,
            'date'  => $date,
            'value' => $value,
        ];

        $this->parcelsDates[] = $date;
        $this->values[] = $value; // float puro
        $this->sum           += $value;

        // Avança mês
        if ($month < 12) {
            $month++;
        } else {
            $month = 1;
            $year++;
        }
    }


    /**
     * Para casos de parcelas agrupadas Ex. 2+2+46
     * Precisa corrigir os meses para que não "salte" nenhum
     */
    private function adjustMonths(): void
    {
        if (empty($this->parcelsDates)) {
            return;
        }

        $newParcelsDates = [];

        [$year, $month, $day] = explode('-', $this->parcelsDates[0]);
        $day = str_pad($day, 2, '0', STR_PAD_LEFT);

        foreach ($this->parcelsDates as $index => $parcelDate) {

            // Ajusta fevereiro
            if ($month == '02' && in_array($day, ['29', '30', '31'])) {
                $finalDay = '28';
            } else {
                $finalDay = $day;
            }

            $newParcelsDates[] = $year . '-' . $month . '-' . $finalDay;

            // Avança mês
            if ((int)$month < 12) {
                $month = str_pad((int)$month + 1, 2, '0', STR_PAD_LEFT);
            } else {
                $month = '01';
                $year++;
            }
        }

        $this->parcelsDates = $newParcelsDates;

        // Mantém as datas sincronizadas dentro de $this->parcels
        foreach ($this->parcels as $i => $parcel) {
            $this->parcels[$i]['date'] = $this->parcelsDates[$i];
        }

        // dd($this->values);
    }

    private function resolveFirstPayment($firstPaymentParcelsQuantity, $firstParcelValue, $multiplier)
    {
        $data = $this->form->getState();

        $parcel = [];

        $prefix = $firstPaymentParcelsQuantity == 1 ? '1' : '1-' . $firstPaymentParcelsQuantity;

        $date = explode('-', $data['first_due_date']);

        $parcel['ord'] = $prefix . '/' . $multiplier . ' (Ent.)';
        $parcel['date'] = $date[0] . '-' . $date[1] . '-' . $date[2];
        $value = $firstParcelValue;

        array_unshift($this->parcels, $parcel); // array_unshift => Coloca na primeira posição e desloca os demais para índices maiores
        array_unshift($this->values, number_format($value, 2));
        array_unshift($this->parcelsDates, $parcel['date']);
    }

    public function resolveBuyerParcels()
    {
        $data = $this->form->getState();

        $parcel = [];
        $month = 1;
        $year = 0;
        $this->buyerSum = 0;
        $this->buyerParcels = [];
        $this->buyerValues = [];

        $parcelValue = floatval($data['buyer_comission_value']) / $data['buyer_commission_installments_number'];

        $this->buyerValues[0] = number_format($parcelValue, 2);

        for ($i = 0; $i < floatval($data['buyer_commission_installments_number']); $i++) {
            $day = str_pad(floatval($data['buyer_due_day']), 2, '0', STR_PAD_LEFT);

            $year = $year == 0 ? now()->format('Y') : $year;

            $month = ($month == 1 && $year == now()->format('Y')) ? now()->addMonths(1)->format('n') : $month;

            $parcel['ord'] = $i + 1 . '/' . $data['buyer_commission_installments_number'];
            $parcel['date'] = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) .  '-' . $day;

            $this->buyerValues[$i] = number_format($parcelValue, 2);
            $this->buyerParcelsDates[$i] = $parcel['date'];

            $this->buyerSum += doubleval($parcelValue);

            if (intval($month) <= 11) {
                $month++;
                $month = str_pad($month, 2, '0', STR_PAD_LEFT);
            } else {
                $month = 1;
                $month = str_pad($month, 2, '0', STR_PAD_LEFT);
                $year++;
            }

            if ((($day == '30') || ($day == '29') || ($day == '31')) && ($month == 2)) {
                $day = '28';
            } else {
                $day = str_pad(floatval($data['seller_due_day']), 2, '0', STR_PAD_LEFT);
            }

            array_push($this->buyerParcels, $parcel);
        }

        for ($i = 0; $i < count($this->buyerValues); $i++) {
            $this->buyerValues[$i] = str_replace(",", "", str_replace("11223344", "", $this->buyerValues[$i]));
        }

        $this->showBuyerParcels = true;
    }

    public function resolveSellerParcels()
    {
        $data = $this->form->getState();

        $parcel = [];
        $month = 1;
        $year = 0;
        $this->sellerSum = 0;
        $this->sellerParcels = [];

        $parcelValue = floatval($data['seller_comission_value']) / $data['seller_commission_installments_number'];

        $this->sellerValues[0] = $parcelValue;

        for ($i = 0; $i < floatval($data['seller_commission_installments_number']); $i++) {
            $day = str_pad(floatval($data['seller_due_day']), 2, '0', STR_PAD_LEFT);

            $year = $year == 0 ? now()->format('Y') : $year;

            $month = ($month == 1 && $year == now()->format('Y')) ? now()->addMonths(1)->format('n') : $month;

            $parcel['ord'] = $i + 1 . '/' . $data['seller_commission_installments_number'];
            $parcel['date'] = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) .  '-' . $day;

            $this->sellerValues[$i] = number_format($parcelValue, 2);
            $this->sellerParcelsDates[$i] = $parcel['date'];

            $this->sellerSum += doubleval($parcelValue);

            if (intval($month) <= 11) {
                $month++;
                $month = str_pad($month, 2, '0', STR_PAD_LEFT);
            } else {
                $month = 1;
                $month = str_pad($month, 2, '0', STR_PAD_LEFT);
                $year++;
            }

            if ((($day == '30') || ($day == '29') || ($day == '31')) && ($month == 2)) {
                $day = '28';
            } else {
                $day = str_pad(floatval($data['seller_due_day']), 2, '0', STR_PAD_LEFT);
            }

            array_push($this->sellerParcels, $parcel);
        }

        for ($i = 0; $i < count($this->sellerValues); $i++) {
            $this->sellerValues[$i] = str_replace(",", "", $this->sellerValues[$i]);
        }

        $this->showSellerParcels = true;
    }

    public function checkParcelValues()
    {
        $data = $this->form->getState();

        // $this->sum = doubleval($data['first_parcel_value']);
        $this->sum = 0;

        foreach ($this->values as $value) {
            $this->sum += doubleval($value);
        }

        // Padronizando as casas decimais
        $this->sum = number_format((float)$this->sum, 2, '.', '');
        $netValue =  number_format((float)$data['net_value'], 2, '.', '');

        if ($netValue != $this->sum) {
            $msg = ParcelsVerification::getDifferenceBetweenParcelsAndNetValueMessage($netValue, $this->sum);

            Notification::make()
                ->title('Atenção!')
                ->body($msg)
                ->danger()
                ->persistent()
                ->send();
        } else {
            Notification::make()
                ->title('Sucesso!')
                ->body('A soma das parcelas e o valor líquido são iguais.')
                ->success()
                ->send();
        }
    }

    public function checkBuyerParcelValues()
    {
        $data = $this->form->getState();

        $this->buyerSum = 0;

        foreach ($this->buyerValues as $value) {
            $this->buyerSum += doubleval($value);
        }

        // Padronizando as casas decimais
        $this->buyerSum = number_format((float)$this->buyerSum, 2, '.', '');
        $parcelValue =  number_format((float)$data['buyer_comission_value'], 2, '.', '');

        if ($parcelValue != $this->buyerSum) {
            $msg = BuyerParcelsVerification::getDifferenceBetweenParcelsAndGrossValueMessage($parcelValue, $this->buyerSum);

            Notification::make()
                ->title('Atenção!')
                ->body($msg)
                ->danger()
                ->persistent()
                ->send();
        } else {
            Notification::make()
                ->title('Sucesso!')
                ->body('A soma das parcelas e o valor da comissão são iguais.')
                ->success()
                ->send();
        }
    }

    public function checkSellerParcelValues()
    {
        $data = $this->form->getState();

        $this->sellerSum = 0;

        foreach ($this->sellerValues as $value) {
            $this->sellerSum += doubleval($value);
        }

        // Padronizando as casas decimais
        $this->sellerSum = number_format((float)$this->sellerSum, 2, '.', '');
        $parcelValue =  number_format((float)$data['buyer_comission_value'], 2, '.', '');

        if (doubleval($data['seller_comission_value']) != $this->sellerSum) {
            $msg = SellerParcelsVerification::getDifferenceBetweenParcelsAndGrossValueMessage(doubleval($data['seller_comission_value']), $this->sellerSum);

            Notification::make()
                ->title('Atenção!')
                ->body($msg)
                ->danger()
                ->persistent()
                ->send();
        } else {
            Notification::make()
                ->title('Sucesso!')
                ->body('A soma das parcelas e o valor da comissão são iguais.')
                ->success()
                ->send();
        }
    }

    public function hideParcels(): void
    {
        $this->showParcels = false;
    }

    public function enableParcelsEdition(): void
    {
        $this->showParcelsEdition = true;
    }

    public function hideBuyerParcels(): void
    {
        $this->showBuyerParcels = false;
    }

    public function hideSellerParcels(): void
    {
        $this->showSellerParcels = false;
    }

    private function saveParcels(): void
    {
        foreach ($this->parcels as $key => $value) {
            Parcel::create([
                'order_id' => $this->record->id,
                'number' => $value['ord'],
                'date' => $this->parcelsDates[$key],
                'value' => floatval($this->values[$key])
            ]);
        }
    }

    private function saveBuyerParcels(): void
    {
        foreach ($this->buyerParcels as $key => $value) {
            BuyerParcel::create([
                'order_id' => $this->record->id,
                'number' => $value['ord'],
                'date' => $this->buyerParcelsDates[$key],
                'value' => floatval($this->buyerValues[$key])
            ]);
        }
    }

    private function saveSellerParcels(): void
    {
        foreach ($this->sellerParcels as $key => $value) {
            SellerParcel::create([
                'order_id' => $this->record->id,
                'number' => $value['ord'],
                'date' => $this->sellerParcelsDates[$key],
                'value' => floatval($this->sellerValues[$key])
            ]);
        }
    }
}
