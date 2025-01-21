<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\EventResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEvent extends CreateRecord
{
    protected static string $resource = EventResource::class;

    protected static ?string $navigationLabel = 'Criar Evento';
}
