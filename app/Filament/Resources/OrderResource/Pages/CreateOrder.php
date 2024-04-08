<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Filament\Traits\WithParcels;
use App\Utils\ParcelsVerification;
use Filament\Actions;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    use WithParcels;
    
    protected static string $resource = OrderResource::class;

    protected static ?string $navigationLabel = 'Criar Ordem de Serviço';

    protected static string $view = 'pages.create-order';

   
}
