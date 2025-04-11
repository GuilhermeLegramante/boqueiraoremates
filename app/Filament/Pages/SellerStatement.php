<?php

namespace App\Filament\Pages;

use App\Models\Animal;
use App\Models\AnimalType;
use App\Models\Breed;
use App\Models\Client;
use App\Models\Coat;
use App\Models\Event;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Exception;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Storage;

class SellerStatement extends Page
{
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.seller-statement';

    protected static ?int $navigationSort = 2;

    protected static ?string $title = 'Extrato do Vendedor';

    protected static ?string $slug = 'extrato-do-vendedor';

    protected ?string $heading = 'Extrato do Vendedor';

    // protected ?string $subheading = 'Geração do Extrato do Vendedor por Evento';

    public ?array $data = [];

    public bool $showActionButton = false;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Geração do Extrato do Vendedor por Evento')
                    ->columns([
                        'sm' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
                    ->description('Selecione um Evento, um Vendedor e informe os Descontos e Proventos adicionais.')
                    ->schema([
                        Select::make('event_id')
                            ->label(__('fields.event'))
                            ->columnSpanFull()
                            ->options(Event::all()->pluck('name', 'id')->toArray())
                            ->reactive()
                            ->required()
                            ->afterStateUpdated(fn($state, callable $set) => $set('seller_id', null)),

                        Select::make('seller_id')
                            ->label('Vendedor')
                            ->columnSpanFull()
                            ->options(function (callable $get) {
                                $eventId = $get('event_id');
                                if (!$eventId) {
                                    return [];
                                }

                                return \App\Models\Client::whereHas('sellerOrders', function ($query) use ($eventId) {
                                    $query->where('event_id', $eventId)
                                        ->whereColumn('seller_id', 'clients.id');
                                })
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->searchable()
                            ->required()
                            ->reactive()
                            ->visible(fn(callable $get) => !empty($get('event_id'))),

                        Repeater::make('additional_earnings')
                            ->label('Proventos Adicionais')
                            ->schema([
                                TextInput::make('description')
                                    ->label('Descrição')
                                    ->required(),
                                TextInput::make('value')
                                    ->label('Valor')
                                    ->numeric()
                                    ->required(),
                            ])
                            ->default([])
                            ->addActionLabel('Adicionar Provento')
                            ->columnSpanFull()
                            ->reactive()
                            ->visible(fn(callable $get) => !empty($get('seller_id'))),


                        Repeater::make('additional_deductions')
                            ->label('Descontos Adicionais')
                            ->schema([
                                TextInput::make('description')
                                    ->label('Descrição')
                                    ->required(),
                                TextInput::make('value')
                                    ->label('Valor')
                                    ->numeric()
                                    ->required(),
                            ])
                            ->default([])
                            ->addActionLabel('Adicionar Desconto')
                            ->columnSpanFull()
                            ->reactive()
                            ->visible(fn(callable $get) => !empty($get('seller_id'))),


                    ]),

                Section::make('Totais')
                    ->description('Resumo dos valores calculados com base nos campos acima.')
                    ->schema([
                        Placeholder::make('total_earnings')
                            ->label('Total de Proventos')
                            ->content(
                                fn(callable $get) =>
                                'R$ ' . number_format(collect($get('additional_earnings'))->sum('value'), 2, ',', '.')
                            )
                            ->reactive(),

                        Placeholder::make('total_deductions')
                            ->label('Total de Descontos')
                            ->content(
                                fn(callable $get) =>
                                'R$ ' . number_format(collect($get('additional_deductions'))->sum('value'), 2, ',', '.')
                            )
                            ->reactive(),

                        Placeholder::make('final_balance')
                            ->label('Saldo Final')
                            ->content(function (callable $get) {
                                $earnings = collect($get('additional_earnings'))->sum('value');
                                $deductions = collect($get('additional_deductions'))->sum('value');
                                $balance = $earnings - $deductions;
                                return 'R$ ' . number_format($balance, 2, ',', '.');
                            })
                            ->extraAttributes(function (callable $get) {
                                $earnings = collect($get('additional_earnings'))->sum('value');
                                $deductions = collect($get('additional_deductions'))->sum('value');
                                $balance = $earnings - $deductions;

                                return [
                                    'class' => 'font-bold ' . ($balance >= 0 ? 'text-green-600' : 'text-red-600'),
                                ];
                            })
                            ->reactive(),
                    ])
                    ->reactive()
                    ->visible(fn(callable $get) => !empty($get('seller_id')))
                    ->columns(3)
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        dd($data);

        try {

            Notification::make()
                ->title('Sucesso!')
                ->body('Dados importados')
                ->success()
                ->send();
        } catch (Exception $e) {
            Notification::make()
                ->title('Erro ao importar os dados!')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
