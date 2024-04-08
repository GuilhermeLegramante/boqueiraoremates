<?php

namespace App\Utils;

class SellerParcelsVerification
{
    public static function getDifferenceBetweenParcelsAndGrossValueMessage($grossValue, $sum): string
    {
        $difference = doubleval($grossValue) - $sum;

        if ($difference > 0) {
            return "Há uma diferença de R$" . number_format($difference, 2, ',', '.') . " entre o valor das parcelas e o valor da comissão do vendedor.";
        } else {
            return "A soma das parcelas excede R$" . number_format(abs($difference), 2, ',', '.') . " o valor da comissão do vendedor.";
        }
    }

}
