<?php

namespace App\Utils;

use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Str;

class MoneyHandler
{
    /**
     * This method is used to convert string output from Money (Leandrocfe\FilamentPtbrFormFields) component
     */
    public static function stringToFloat($value): float
    {
        return floatval(
            Str::of($value)
                ->replace('.', '')
                ->replace(',', '.')
                ->toString()
        ) * 10;
    }

}