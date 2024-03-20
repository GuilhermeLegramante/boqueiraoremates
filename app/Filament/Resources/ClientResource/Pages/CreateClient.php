<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Resources\ClientResource;
use App\Models\Address;
use App\Models\City;
use App\Models\Neighborhood;
use App\Models\State;
use App\Models\Street;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateClient extends CreateRecord
{
    protected static string $resource = ClientResource::class;

    protected static ?string $navigationLabel = 'Criar Cliente';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $data;
    }
}
