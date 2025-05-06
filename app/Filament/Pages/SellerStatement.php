<?php

namespace App\Filament\Pages;

use App\Models\Animal;
use App\Models\AnimalType;
use App\Models\Breed;
use App\Models\Client;
use App\Models\Coat;
use App\Models\EarningDiscount;
use App\Models\Event;
use App\Models\Order;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Exception;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SellerStatement extends Page
{
    // use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.seller-statement';

    protected static ?int $navigationSort = 2;

    protected static ?string $title = 'Extrato de Vendedor';

    protected static ?string $slug = 'extrato-de-vendedor';

    protected ?string $heading = 'Extrato de Vendedor';

    protected static ?string $navigationGroup = 'Relatórios';

    // protected ?string $subheading = 'Geração do Extrato do Vendedor por Evento';

    public ?array $data = [];

    public bool $showActionButton = false;

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Geração do Extrato de Vendedor por Evento')
                    ->columns([
                        'sm' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
                    ->description('Selecione um Evento, um Vendedor e informe os Descontos e Proventos adicionais.')
                    ->schema([
                        DatePicker::make('start_date')
                            ->label('Data Inicial')
                            ->reactive()
                            ->afterStateUpdated(fn($state, callable $set) => $set('event_id', null)),

                        DatePicker::make('end_date')
                            ->label('Data Final')
                            ->reactive()
                            ->afterStateUpdated(fn($state, callable $set) => $set('event_id', null)),

                        Select::make('event_id')
                            ->label(__('fields.event'))
                            ->columnSpanFull()
                            ->options(function (callable $get) {
                                $startDate = $get('start_date');
                                $endDate = $get('end_date');

                                $query = Event::query();

                                if ($startDate) {
                                    $query->whereDate('start_date', '>=', $startDate);
                                }

                                if ($endDate) {
                                    $query->whereDate('start_date', '<=', $endDate);
                                }

                                return $query->pluck('name', 'id')->toArray();
                            })
                            ->reactive()
                            ->required()
                            ->afterStateUpdated(function ($state, callable $set, $get, $livewire) {
                                $set('seller_id', null);
                                $set('additional_earnings', []);
                                $set('additional_discounts', []);
                                $livewire->loadEarningsAndDiscounts();
                            }),

                        Select::make('seller_id')
                            ->label('Vendedor')
                            ->columnSpanFull()
                            ->options(function (callable $get) {
                                $eventId = $get('event_id');
                                if (!$eventId) return [];

                                return Client::whereHas(
                                    'sellerOrders',
                                    fn($query) =>
                                    $query->where('event_id', $eventId)->whereColumn('seller_id', 'clients.id')
                                )->pluck('name', 'id')->toArray();
                            })
                            ->searchable()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn($state, callable $set, $get, $livewire) => $livewire->loadEarningsAndDiscounts())
                            ->visible(fn(callable $get) => !empty($get('event_id'))),

                        Section::make('Proventos 01')
                            ->schema([
                                Placeholder::make('total_earnings_01')
                                    ->label('Total de Proventos (01 + 02)')
                                    ->content(
                                        fn(callable $get) =>
                                        'R$ ' . number_format($get('total_earnings_01'), 2, ',', '.')
                                    )
                                    ->reactive(),
                            ])
                            ->reactive()
                            ->visible(fn(callable $get) => !empty($get('seller_id')))
                            ->columnSpanFull(),

                        Repeater::make('additional_earnings')
                            ->label('Proventos 02')
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


                        Repeater::make('additional_discounts')
                            ->label('Descontos')
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
                            ->label('Total de Proventos (01 + 02)')
                            ->content(
                                fn(callable $get) =>
                                'R$ ' . number_format(collect($get('additional_earnings'))->sum('value'), 2, ',', '.')
                            )
                            ->reactive(),

                        Placeholder::make('total_discounts')
                            ->label('Total de Descontos')
                            ->content(
                                fn(callable $get) =>
                                'R$ ' . number_format(collect($get('additional_discounts'))->sum('value'), 2, ',', '.')
                            )
                            ->reactive()
                            ->extraAttributes(['class' => 'text-red-600']),

                        Placeholder::make('final_balance')
                            ->label('Saldo Final')
                            ->content(function (callable $get) {
                                $earnings = collect($get('additional_earnings'))->sum('value');
                                $discounts = collect($get('additional_discounts'))->sum('value');
                                $balance = $earnings - $discounts;
                                return 'R$ ' . number_format($balance, 2, ',', '.');
                            })
                            ->extraAttributes(function (callable $get) {
                                $earnings = collect($get('additional_earnings'))->sum('value');
                                $discounts = collect($get('additional_discounts'))->sum('value');
                                $balance = $earnings - $discounts;

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

    public function loadEarningsAndDiscounts()
    {
        $eventId = $this->data['event_id'];
        $sellerId = $this->data['seller_id'];

        // Calculando os Proventos 01
        $orders = Order::where('event_id', $eventId)
            ->with(['seller'])
            ->whereHas('seller', fn($query) => $query->where('id', $sellerId))
            ->orderByRaw("batch IS NULL, batch")
            ->get();

        foreach ($orders as $order) {
            $receipt1 = DB::table('parcels')
                ->where('order_id', $order->id)
                ->where('payment_method_id', 5)
                ->sum('value');

            $receipt2 = DB::table('buyer_parcels')
                ->where('order_id', $order->id)
                ->where('payment_method_id', 5)
                ->sum('value');

            $receipt3 = DB::table('seller_parcels')
                ->where('order_id', $order->id)
                ->where('payment_method_id', 5)
                ->sum('value');

            $order->receipt = $receipt1 + $receipt2 + $receipt3;
        }

        $receivedTotal = $orders->sum('receipt');


        if ($eventId && $sellerId) {
            $records = EarningDiscount::where('event_id', $eventId)
                ->where('client_id', $sellerId)
                ->get();

            $earnings = $records->where('type', 'earning')->map(fn($item) => [
                'description' => $item->description,
                'value' => $item->amount,
            ])->values()->toArray();

            $discounts = $records->where('type', 'discount')->map(fn($item) => [
                'description' => $item->description,
                'value' => $item->amount,
            ])->values()->toArray();

            $this->form->fill([
                'event_id' => $eventId,
                'seller_id' => $sellerId,
                'additional_earnings' => $earnings,
                'additional_discounts' => $discounts,
                'total_earnings_01' => $receivedTotal,
            ]);

            if (empty($earnings) && empty($discounts)) {
                Notification::make()
                    ->title('Nenhum registro encontrado')
                    ->body('Este vendedor não possui proventos ou descontos cadastrados para este evento.')
                    ->info()
                    ->send();
            }
        }
    }

    public function submit()
    {
        $data = $this->form->getState();

        $eventId = $data['event_id'] ?? null;
        $sellerId = $data['seller_id'] ?? null;

        if (!$eventId || !$sellerId) {
            Notification::make()
                ->title('Erro')
                ->body('Evento e Vendedor são obrigatórios.')
                ->danger()
                ->send();
            return;
        }

        try {
            // Exclui os registros anteriores
            \App\Models\EarningDiscount::where('event_id', $eventId)
                ->where('client_id', $sellerId)
                ->delete();

            // Insere os proventos
            foreach ($data['additional_earnings'] ?? [] as $earning) {
                \App\Models\EarningDiscount::create([
                    'event_id'    => $eventId,
                    'client_id'   => $sellerId,
                    'description' => $earning['description'],
                    'amount'      => $earning['value'],
                    'type'        => 'earning',
                ]);
            }

            // Insere os descontos
            foreach ($data['additional_discounts'] ?? [] as $discount) {
                \App\Models\EarningDiscount::create([
                    'event_id'    => $eventId,
                    'client_id'   => $sellerId,
                    'description' => $discount['description'],
                    'amount'      => $discount['value'],
                    'type'        => 'discount',
                ]);
            }

            Notification::make()
                ->title('Sucesso!')
                ->body('Proventos e descontos salvos com sucesso.')
                ->success()
                ->send();

            return redirect()->route('seller-statement-pdf', [
                'eventId' => $eventId,
                'sellerId' => $sellerId,
            ]);
        } catch (\Exception $e) {
            Notification::make()
                ->title('Erro ao salvar')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
