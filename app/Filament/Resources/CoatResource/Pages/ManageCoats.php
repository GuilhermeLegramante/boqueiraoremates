<?php

namespace App\Filament\Resources\CoatResource\Pages;

use App\Filament\Resources\CoatResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Str;

class ManageCoats extends ManageRecords
{
    protected static string $resource = CoatResource::class;

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
