<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\EventResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEvent extends EditRecord
{
    protected static string $resource = EventResource::class;

    protected static ?string $navigationLabel = 'Editar Evento';

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('downloadRegulationPdf')
                ->label('Gerar PDF do Regulamento')
                ->icon('heroicon-o-document-text')
                ->color('success')
                ->url(fn(): string => route('events.regulation.pdf', $this->record->id))
                ->openUrlInNewTab(),

            Actions\DeleteAction::make(),
        ];
    }
}
