<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Filament\Traits\WithParcels;
use App\Models\BuyerParcel;
use App\Models\Event;
use App\Models\Order;
use App\Models\Parcel;
use App\Models\SellerParcel;
use App\Models\User;
use App\Utils\ReportFactory;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Halt;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\DB;

class EditOrder extends EditRecord
{
    use WithParcels;

    protected static string $resource = OrderResource::class;

    protected static ?string $navigationLabel = 'Editar Ordem de Serviço';

    protected static string $view = 'pages.edit-order';

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('generatePdf')
                ->label('Gerar PDF')
                ->icon('heroicon-o-document-text')
                ->url(fn() => route('order-pdf', ['orderId' => $this->record->id]))
                ->openUrlInNewTab(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $netValue = floatval($data['gross_value']) - (floatval($data['gross_value']) * floatval($data['discount_percentage'])) / 100;
        $data['net_value'] = str_replace(",", "", number_format($netValue, 2));

        $buyerComission = (floatval($data['gross_value']) * floatval($data['buyer_commission'])) / 100;
        $data['buyer_comission_value'] = str_replace(",", "", number_format($buyerComission, 2));

        $sellerComission = (floatval($data['gross_value']) * floatval($data['seller_commission'])) / 100;
        $data['seller_comission_value'] = str_replace(",", "", number_format($sellerComission, 2));

        $data['discount_percentage'] = number_format((float) $data['discount_percentage'], 2, '.', '');

        $data['original_parcel'] = floatval($data['gross_value']) / floatval($data['multiplier']);

        return $data;
    }

    public function save(bool $shouldRedirect = true): void
    {
        $this->authorizeAccess();

        try {
            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeSave($data);

            $this->callHook('beforeSave');

            $this->handleRecordUpdate($this->getRecord(), $data);

            if (count($this->parcels) > 0) {
                Parcel::where('order_id', $this->getRecord()->id)->delete();
                $this->saveParcels();
            }

            if (count($this->buyerParcels) > 0) {
                BuyerParcel::where('order_id', $this->getRecord()->id)->delete();
                $this->saveBuyerParcels();
            }

            if (count($this->sellerParcels) > 0) {
                SellerParcel::where('order_id', $this->getRecord()->id)->delete();
                $this->saveSellerParcels();
            }

            $this->callHook('afterSave');
        } catch (Halt $exception) {
            return;
        }

        $this->rememberData();

        $this->getSavedNotification()?->send();

        if ($shouldRedirect && ($redirectUrl = $this->getRedirectUrl())) {
            $this->redirect($redirectUrl);
        }
    }

    private function deleteParcels()
    {
        Parcel::where('order_id', $this->getRecord()->id)->delete();
        SellerParcel::where('order_id', $this->getRecord()->id)->delete();
        BuyerParcel::where('order_id', $this->getRecord()->id)->delete();
    }
}
