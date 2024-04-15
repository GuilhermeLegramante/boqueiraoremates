<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Filament\Traits\WithParcels;
use App\Models\Event;
use App\Models\Order;
use App\Models\Parcel;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Halt;
use Filament\Support\Facades\FilamentView;

class EditOrder extends EditRecord
{
    use WithParcels;

    protected static string $resource = OrderResource::class;

    protected static ?string $navigationLabel = 'Editar Ordem de Serviço';

    protected static string $view = 'pages.edit-order';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['net_value'] = floatval($data['gross_value']) - (floatval($data['gross_value']) * floatval($data['discount_percentage'])) / 100;

        $event = Event::find($data['event_id']);

        $data['multiplier'] = $event->multiplier; 

        return $data;
    }
}
