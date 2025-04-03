<?php

namespace App\Filament\Resources\SalesMapResource\Pages;

use App\Filament\Resources\SalesMapResource;
use App\Filament\Resources\SalesMapResource\Widgets\ViewSalesMapAnimals;
use App\Models\Animal;
use App\Models\Client;
use Filament\Actions;
use Filament\Forms\Components\Select;
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

    protected function getHeaderActions(): array
    {
        // dd(request()->query()); // Depuração para ver quais filtros estão sendo passados

        return [
            // Actions\Action::make('generatePdf')
            //     ->label('Gerar PDF')
            //     ->icon('heroicon-o-document-text')
            //     ->url(fn() => route('sales-map-pdf', [
            //         'record' => $this->record->id,
            //         'seller' => request()->query('tableFilters')['seller'] ?? null, // Captura o filtro corretamente
            //     ]))
            //     ->openUrlInNewTab(),
            Actions\Action::make('gerar_pdf')
                ->label('Gerar PDF')
                ->form([
                    Select::make('seller')
                        ->label('Vendedor')
                        ->searchable()
                        ->options(
                            Client::pluck('name', 'id')->toArray()
                        )
                        ->default(request()->query('tableFilters.seller')), // Tenta pegar o filtro da tabela, se existir
                ])
                ->action(function (array $data) {
                    return redirect()->route('sales-map-pdf', [
                        'record' => $this->record->id,
                        'seller' => $data['seller'],
                    ]);
                })
                ->modalHeading('Gerar PDF')
                ->modalSubmitActionLabel('Gerar'),
            Actions\DeleteAction::make(),
        ];
    }
}
