<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Filament\Traits\WithParcels;
use App\Models\Parcel;
use App\Utils\ParcelsVerification;
use Filament\Actions;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;
use Filament\Support\Facades\FilamentView;


class CreateOrder extends CreateRecord
{
    use WithParcels;

    protected static string $resource = OrderResource::class;

    protected static ?string $navigationLabel = 'Criar Fatura de Venda / OS';

    protected static string $view = 'pages.create-order';

    public function create(bool $another = false): void
    {
        $this->authorizeAccess();

        try {
            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeCreate($data);

            $this->callHook('beforeCreate');

            $this->record = $this->handleRecordCreation($data);

            $this->form->model($this->getRecord())->saveRelationships();

            $this->saveParcels();
            $this->saveBuyerParcels();
            $this->saveSellerParcels();

            $this->callHook('afterCreate');
        } catch (Halt $exception) {
            return;
        }

        $this->rememberData();

        $this->getCreatedNotification()?->send();

        if ($another) {
            // Ensure that the form record is anonymized so that relationships aren't loaded.
            $this->form->model($this->getRecord()::class);
            $this->record = null;

            $this->fillForm();

            return;
        }

        $redirectUrl = $this->getRedirectUrl();

        $this->redirect($redirectUrl, navigate: FilamentView::hasSpaMode());
    }
}
