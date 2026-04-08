<?php

namespace App\Filament\Pages;

use App\Models\Bid;
use App\Models\Event;
use Filament\Pages\Page;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class BidReport extends Page implements HasForms
{
    use InteractsWithForms;

    public ?\App\Models\Bid $winner = null; // Armazena o lance sorteado

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static string $view = 'filament.pages.bid-report';
    protected static ?string $title = 'Relatório de Lances Aprovados';

    protected static ?string $navigationGroup = 'Relatórios';

    public array $selectedBids = []; // Armazena os IDs dos lances selecionados para o sorteio

    // Propriedade para armazenar o ID do evento selecionado
    public ?array $data = [];

    public function mount(): void
    {
        // Inicializa o formulário com dados vazios
        $this->form->fill();
    }

    // Adicione este método para resetar a seleção ao mudar o evento
    public function updatedDataEventId()
    {
        $this->winner = null;
        $this->selectedBids = $this->getBidsProperty()->pluck('id')->toArray();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('sortear')
                ->label('Sortear entre selecionados')
                ->color('warning')
                ->icon('heroicon-m-gift')
                ->requiresConfirmation()
                ->action(function () {
                    // Filtra os lances originais apenas pelos IDs marcados nos checkboxes
                    $pool = $this->getBidsProperty()->whereIn('id', $this->selectedBids);

                    if ($pool->isEmpty()) {
                        Notification::make()
                            ->title('Nenhum lance selecionado')
                            ->body('Marca pelo menos um cliente na tabela para realizar o sorteio.')
                            ->danger()
                            ->send();
                        return;
                    }

                    $this->winner = $pool->random();

                    Notification::make()
                        ->title('🎉 Sorteio Realizado!')
                        ->body("Ganhador: **{$this->winner->user->name}**")
                        ->success()
                        ->persistent()
                        ->send();
                }),
        ];
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
            ->where('status', 1)
            ->with(['user', 'approvedBy'])
            ->whereHas('user', function ($query) {
                $query->whereNotIn('name', [
                    'LUIS EMERSON HOISLER DA ROSA',
                    'LEANDRO CESAR DORNELES DE OLIVEIRA'
                ]);
            })
            ->get();
    }
}
