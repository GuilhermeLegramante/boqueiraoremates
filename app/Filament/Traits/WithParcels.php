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


    public function resolveParcels(): void
    {
        $data = $this->form->getState();

        $parcel = [];
        $month = 1;
        $year = 0;
        $this->sum = doubleval($data['first_parcel_value']);
        $this->parcels = [];
        $this->values = [];

        $paymentWay = PaymentWay::find($data['payment_way_id']);
        $parcelsParts = explode("+", $paymentWay->name);
        array_pop($parcelsParts); // Remove a última posição (parcelas iguais ex. 2+2+46, deixa só as duas primeiras partes)

        $parcelCounter = 0;

        $parcelsRemains = 0;

        $parcels = 0;

        // Montando as primeiras parcelas da fórmula
        if (count($parcelsParts) > 1) {
            $parcelCounter += intval($parcelsParts[0]);

            for ($i = 0; $i < count($parcelsParts); $i++) {
                $day = str_pad(floatval($data['due_day']), 2, '0', STR_PAD_LEFT);
                $year = $year == 0 ? now()->format('Y') : $year;
                $month = ($month == 1 && $year == now()->format('Y')) ? now()->addMonths(1)->format('n') : $month;

                $parcels = intval($parcelsParts[$i]);

                $parcel['ord'] = $parcelCounter . '-' . $parcels + $parcelCounter - 1 . '/' . $data['multiplier']; // Ex: 1-2/50 , 1-3/50, etc.
                $parcel['date'] = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . $day;
                $this->parcelsDates[$i] = $parcel['date'];


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

            $this->values = array_slice($this->values, -1, 1);
        }


        for ($i = intval($parcelsParts[0]); $i < floatval($data['multiplier']); $i++) {
            $day = str_pad(floatval($data['due_day']), 2, '0', STR_PAD_LEFT);
            $year = $year == 0 ? now()->format('Y') : $year;
            $month = ($month == 1 && $year == now()->format('Y')) ? now()->addMonths(1)->format('n') : $month;

            $parcel['ord'] = $i + 1 . '/' . $data['multiplier'];
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
        array_splice($this->parcels, 1, $parcelsRemains);
        array_splice($this->values, 1, $parcelsRemains);
        array_splice($this->parcelsDates, 1, $parcelsRemains);

        // Inclui a "entrada" nas parcelas
        if (intval($parcelsParts[0]) > 0) {
            $this->resolveFirstPayment(intval($parcelsParts[0]), floatval($data['first_parcel_value']), $data['multiplier']); // parcelsParts[0] é a qtde de parcelas da entrada
        }

        // Tira a "vírgula" de valores maior que 1000
        for ($i = 0; $i < count($this->values); $i++) {
            $this->values[$i] = str_replace(",", "", $this->values[$i]);
        }

        $this->showParcels = true;
    }

    private function resolveFirstPayment($firstPaymentParcelsQuantity, $firstParcelValue, $multiplier)
    {
        $parcel = [];

        $prefix = $firstPaymentParcelsQuantity == 1 ? '1' : '1-' . $firstPaymentParcelsQuantity;

        $parcel['ord'] = $prefix . '/' . $multiplier . ' (Ent.)';
        $parcel['date'] = now()->format('Y-m-d');
        $value = $firstParcelValue;

        array_unshift($this->parcels, $parcel); // array_unshift => Coloca na primeira posição e desloca os demais para índices maiores
        array_unshift($this->values, number_format($value, 2));
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

        if (doubleval($data['net_value']) != $this->sum) {
            $msg = ParcelsVerification::getDifferenceBetweenParcelsAndNetValueMessage(doubleval($data['net_value']), $this->sum);

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

        if (doubleval($data['buyer_comission_value']) != $this->buyerSum) {
            $msg = BuyerParcelsVerification::getDifferenceBetweenParcelsAndGrossValueMessage(doubleval($data['buyer_comission_value']), $this->buyerSum);

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
                'date' => $value['date'],
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
                'date' => $value['date'],
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
                'date' => $value['date'],
                'value' => floatval($this->sellerValues[$key])
            ]);
        }
    }
}
