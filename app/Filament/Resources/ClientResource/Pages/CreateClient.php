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
use Illuminate\Support\Str;

class CreateClient extends CreateRecord
{
    protected static string $resource = ClientResource::class;

    protected static ?string $navigationLabel = 'Criar Cliente';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['name'] = Str::upper($data['name']);
        $data['establishment'] = Str::upper($data['establishment']);
        $data['occupation'] = Str::upper($data['occupation']);
        $data['note_occupation'] = Str::upper($data['note_occupation']);
        $data['mother'] = Str::upper($data['mother']);
        $data['father'] = Str::upper($data['father']);
        $data['auctioneer'] = Str::upper($data['auctioneer']);
        $data['note'] = Str::upper($data['note']);

        return $data;
    }
}
