<?php

namespace App\Filament\Resources\SalesMapResource\Pages;

use App\Filament\Resources\SalesMapResource;
use App\Filament\Resources\SalesMapResource\Widgets\ViewSalesMapAnimals;
use App\Models\Animal;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ViewSalesMap extends ViewRecord
{
    protected static string $resource = SalesMapResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return $data;
    }

    protected function getFooterWidgets(): array
    {
        return [
            ViewSalesMapAnimals::class, // Chamando o widget
        ];
    }
}
