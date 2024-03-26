<?php

namespace App\Filament\Resources\AnimalTypeResource\Pages;

use App\Filament\Resources\AnimalTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Str;

class ManageAnimalTypes extends ManageRecords
{
    protected static string $resource = AnimalTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->mutateFormDataUsing(function (array $data): array {
                    $data['name'] = Str::upper($data['name']);
                    return $data;
                }),
        ];
    }
}
