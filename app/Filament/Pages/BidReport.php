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

    protected function getHeaderActions(): array
    {
        return [
            Action::make('sortear')
                ->label('Sortear Ganhador')
                ->color('warning')
                ->icon('heroicon-m-gift')
                ->requiresConfirmation()
                ->modalHeading('Sortear entre os lances aprovados')
                ->modalDescription('O sistema escolherá aleatoriamente um dos clientes da lista abaixo.')
                ->modalSubmitActionLabel('Realizar Sorteio')
                ->action(function () {
                    $bids = $this->getBidsProperty();

                    if ($bids->isEmpty()) {
                        Notification::make()
                            ->title('Nenhum lance encontrado')
                            ->danger()
                            ->send();
                        return;
                    }

                    // Pega um lance aleatório
                    $sorteado = $bids->random();

                    // Dispara um alerta de sucesso com o nome do ganhador
                    Notification::make()
                        ->title('🎉 Temos um vencedor!')
                        ->body("O cliente sorteado foi: **{$sorteado->user->name}**\n\nLote: {$sorteado->lot_number}")
                        ->success()
                        ->persistent() // O alerta não some sozinho
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
            ->where('status', 1) // Certifica-te que 'approved' é o status correto no teu banco
            ->with(['user', 'event'])
            ->whereHas('user', function ($query) {
                $query->whereNotIn('name', [
                    '%LUIS EMERSON HOISLER DA ROSA%',
                    '%LEANDRO CESAR DORNELES DE OLIVEIRA%'
                ]);
            })
            ->get();
    }
}
