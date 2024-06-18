<?php

namespace App\Utils;

use App\Models\PaymentWay;
use Barryvdh\DomPDF\PDF;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;

class ParcelsVerification
{
    public static function getDifferenceBetweenParcelsAndNetValueMessage($netValue, $sum): string
    {
        $difference = doubleval($netValue) - $sum;

        if ($difference > 0) {
            return "Há uma diferença de R$" . number_format($difference, 2, ',', '.') . " entre o valor das parcelas e o valor líquido.";
        } else {
            return "A soma das parcelas excede R$" . number_format(abs($difference), 2, ',', '.') . " do valor líquido.";
        }
    }

    public static function checkIfPaymentWaySumIsInAccordingWithMultiplier($paymentWayId, $multiplier): bool
    {
        $paymentWay = PaymentWay::find($paymentWayId);

        if (isset($paymentWay)) {
            $values = explode("+", $paymentWay->name);

            $sum = 0;

            foreach ($values as $key => $value) {
                $sum += doubleval($value);
            }

            if ($sum != doubleval($multiplier)) {
                return false;
            } else {
                return true;
            }
        }
    }

    public static function getMultiplier($paymentWayId): int
    {
        $sum = 0;

        $paymentWay = PaymentWay::find($paymentWayId);

        if (isset($paymentWay)) {
            $values = explode("+", $paymentWay->name);

            foreach ($values as $key => $value) {
                $sum += intval($value);
            }
        }
        if ($sum == 0) {
            $sum = 1;
        }
        return $sum;
    }

    public static function getFirstParcelValue($paymentWayId, $parcelValue): float
    {
        $paymentWay = PaymentWay::find($paymentWayId);

        if (isset($paymentWay)) {
            $values = explode("+", $paymentWay->name);

            return doubleval($parcelValue) * intval($values[0]);
        } else {
            return 0;
        }
    }
}
