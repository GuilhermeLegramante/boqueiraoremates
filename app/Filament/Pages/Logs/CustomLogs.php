<?php

namespace App\Filament\Pages\Logs;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use FilipFonal\FilamentLogManager\Pages\Logs;

class CustomLogs extends Logs
{
    use HasPageShield;

    public static function getNavigationGroup(): ?string
    {
        return 'Configurações';
    }
}