<?php

namespace App\Filament\Pages;

use App\Models\Bid;
use App\Models\Event;
use Filament\Pages\Page;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;

class BidReport extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static string $view = 'filament.pages.bid-report';
    protected static ?string $title = 'Relatório de Lances Aprovados';

    protected static ?string $navigationGroup = 'Relatórios';

    // Propriedade para armazenar o ID do evento selecionado
    public ?array $data = [];

    public function mount(): void
    {
        // Inicializa o formulário com dados vazios
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('event_id')
                    ->label('Selecione o Evento')
                    ->options(Event::pluck('name', 'id'))
                    ->required()
                    ->live() // Atualiza a página automaticamente ao mudar
                    ->afterStateUpdated(fn() => $this->data['event_id']),
            ])
            ->statePath('data');
    }

    // Função para buscar os lances aprovados do evento selecionado
    public function getBidsProperty()
    {
        $eventId = $this->data['event_id'] ?? null;

        if (!$eventId) {
            return collect();
        }

        return Bid::where('event_id', $eventId)
            ->where('status', 'approved') // Certifica-te que 'approved' é o status correto no teu banco
            ->with(['user', 'event'])
            ->get();
    }
}
