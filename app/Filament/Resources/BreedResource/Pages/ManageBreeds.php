<?php

namespace App\Filament\Resources\BreedResource\Pages;

use App\Filament\Resources\BreedResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Str;

class ManageBreeds extends ManageRecords
{
    protected static string $resource = BreedResource::class;

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
