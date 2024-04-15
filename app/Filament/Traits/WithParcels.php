<?php

namespace App\Filament\Traits;

use App\Models\BuyerParcel;
use App\Models\Parcel;
use App\Models\SellerParcel;
use App\Utils\BuyerParcelsVerification;
use App\Utils\ParcelsVerification;
use App\Utils\SellerParcelsVerification;
use Filament\Notifications\Notification;

trait WithParcels
{
    public array $parcels = [];
    public array $values = [];
    public float $sum = 0;
    public bool $showParcels = false;

    public array $buyerParcels = [];
    public array $buyerValues = [];
    public float $buyerSum = 0;
    public bool $showBuyerParcels = false;

    public array $sellerParcels = [];
    public array $sellerValues = [];
    public float $sellerSum = 0;
    public bool $showSellerParcels = false;


    public function resolveParcels(): void
    {
        $data = $this->form->getState();

        $parcel = [];
        $month = 1;
        $year = 0;
        $this->sum = 0;
        $this->parcels = [];

        $this->values[0] = $data['parcel_value'];

        for ($i = 0; $i < floatval($data['multiplier']); $i++) {

            $day = floatval($data['due_day']);

            $year = $year == 0 ? now()->format('Y') : $year;

            $month = ($month == 1 && $year == now()->format('Y')) ? now()->addMonths(1)->format('n') : $month;

            $parcel['ord'] = $i + 1 . '/' . $data['multiplier'];
            $parcel['date'] = $day . '/' . $month . '/' . $year;
            $this->values[$i] = number_format($data['parcel_value'], 2);

            $this->sum += doubleval($data['parcel_value']);

            if (intval($month) <= 11) {
                $month++;
            } else {
                $month = 1;
                $year++;
            }

            array_push($this->parcels, $parcel);
        }

        $this->showParcels = true;
    }

    public function resolveBuyerParcels()
    {
        $data = $this->form->getState();

        $parcel = [];
        $month = 1;
        $year = 0;
        $this->buyerSum = 0;
        $this->buyerParcels = [];

        $parcelValue = number_format(floatval($data['buyer_comission_value']) / $data['buyer_commission_installments_number'], 2);

        $this->buyerValues[0] = $parcelValue;

        for ($i = 0; $i < floatval($data['buyer_commission_installments_number']); $i++) {

            $day = floatval($data['buyer_due_day']);

            $year = $year == 0 ? now()->format('Y') : $year;

            $month = ($month == 1 && $year == now()->format('Y')) ? now()->addMonths(1)->format('n') : $month;

            $parcel['ord'] = $i + 1 . '/' . $data['buyer_commission_installments_number'];
            $parcel['date'] = $day . '/' . $month . '/' . $year;
            $this->buyerValues[$i] = $parcelValue;

            $this->buyerSum += $parcelValue;

            if (intval($month) <= 11) {
                $month++;
            } else {
                $month = 1;
                $year++;
            }

            array_push($this->buyerParcels, $parcel);
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

        $parcelValue = number_format(floatval($data['seller_comission_value']) / $data['seller_commission_installments_number'], 2);

        $this->sellerValues[0] = $parcelValue;

        for ($i = 0; $i < floatval($data['seller_commission_installments_number']); $i++) {

            $day = floatval($data['seller_due_day']);

            $year = $year == 0 ? now()->format('Y') : $year;

            $month = ($month == 1 && $year == now()->format('Y')) ? now()->addMonths(1)->format('n') : $month;

            $parcel['ord'] = $i + 1 . '/' . $data['seller_commission_installments_number'];
            $parcel['date'] = $day . '/' . $month . '/' . $year;
            $this->sellerValues[$i] = $parcelValue;

            $this->sellerSum += $parcelValue;

            if (intval($month) <= 11) {
                $month++;
            } else {
                $month = 1;
                $year++;
            }

            array_push($this->sellerParcels, $parcel);
        }

        $this->showSellerParcels = true;
    }

    public function checkParcelValues()
    {
        $data = $this->form->getState();

        $this->sum = 0;

        foreach ($this->values as $value) {
            $this->sum += doubleval($value);
        }

        if (doubleval($data['gross_value']) != $this->sum) {
            $msg = ParcelsVerification::getDifferenceBetweenParcelsAndGrossValueMessage(doubleval($data['gross_value']), $this->sum);

            Notification::make()
                ->title('Atenção!')
                ->body($msg)
                ->danger()
                ->persistent()
                ->send();
        } else {
            Notification::make()
                ->title('Sucesso!')
                ->body('A soma das parcelas e o valor bruto são iguais.')
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
            $date = explode("/", $value['date']);

            Parcel::create([
                'order_id' => $this->record->id,
                'number' => $value['ord'],
                'date' => $date[2] . '-' . $date[1] . '-' . $date[0],
                'value' => floatval($this->values[$key])
            ]);
        }
    }

    private function saveBuyerParcels(): void
    {
        foreach ($this->buyerParcels as $key => $value) {
            $date = explode("/", $value['date']);

            BuyerParcel::create([
                'order_id' => $this->record->id,
                'number' => $value['ord'],
                'date' => $date[2] . '-' . $date[1] . '-' . $date[0],
                'value' => floatval($this->buyerValues[$key])
            ]);
        }
    }

    private function saveSellerParcels(): void
    {
        foreach ($this->sellerParcels as $key => $value) {
            $date = explode("/", $value['date']);

            SellerParcel::create([
                'order_id' => $this->record->id,
                'number' => $value['ord'],
                'date' => $date[2] . '-' . $date[1] . '-' . $date[0],
                'value' => floatval($this->sellerValues[$key])
            ]);
        }
    }
}
