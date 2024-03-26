<?php

namespace App\Filament\Resources\ServiceTypeResource\Pages;

use App\Filament\Resources\ServiceTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Str;

class ManageServiceTypes extends ManageRecords
{
    protected static string $resource = ServiceTypeResource::class;

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
