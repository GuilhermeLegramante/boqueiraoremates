<?php

namespace App\Utils;

use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Str;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class ExcelBulkExport extends ExportBulkAction
{
    public static function getDefaultName(): ?string
    {
        return 'planilha';
    }
}