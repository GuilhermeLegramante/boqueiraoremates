<?php

namespace App\Utils;

use App\Models\PaymentWay;
use Barryvdh\DomPDF\PDF;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;

class ParcelsVerification
{
    public static function getDifferenceBetweenParcelsAndGrossValueMessage($grossValue, $sum): string
    {
        $difference = doubleval($grossValue) - $sum;

        if ($difference > 0) {
            return "Há uma diferença de R$" . number_format($difference, 2, ',', '.') . " entre o valor das parcelas e o valor bruto.";
        } else {
            return "A soma das parcelas excede R$" . number_format(abs($difference), 2, ',', '.') . " do valor bruto.";
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
}
