<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Filament\Traits\WithParcels;
use App\Models\Order;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

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
            // Actions\Action::make('getPdf')
            //     ->label('Gerar PDF')
            //     ->action(function (Order $record): string {
            //        return route('order-pdf', $record->id);
            //     }),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['net_value'] = floatval($data['gross_value']) - (floatval($data['gross_value']) * floatval($data['discount_percentage'])) / 100;

        return $data;
    }
}
