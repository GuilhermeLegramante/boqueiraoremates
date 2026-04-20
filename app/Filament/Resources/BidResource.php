<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BidResource\Pages;
use App\Filament\Resources\BidResource\RelationManagers;
use App\Filament\Tables\BidTable;
use App\Models\Bid;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Grid;
use Filament\Forms\Get;
use Illuminate\Support\HtmlString;
class BidResource extends Resource
{
    protected static ?string $model = Bid::class;

    protected static ?string $navigationLabel = 'Todos os Lances';
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $recordTitleAttribute = 'user.name';

    protected static ?string $modelLabel = 'lance';

    protected static ?string $pluralModelLabel = 'Todos os Lances';

    protected static ?string $slug = 'lances';

    protected static ?string $navigationGroup = 'Lances';

    protected static ?int $navigationSort = -3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detalhes do Lance')
                    ->schema([
                        // Seleção do Evento
                        Forms\Components\Select::make('event_id')
                            ->label('Evento')
                            ->relationship('event', 'name')
                            ->live()
                            ->afterStateUpdated(fn($set) => $set('animal_event_id', null))
                            ->required(),

                        // Seleção do Animal/Lote
                        Forms\Components\Select::make('animal_event_id')
                            ->label('Animal / Lote')
                            ->options(function (Get $get) {
                                $eventId = $get('event_id');
                                if (!$eventId) return collect();

                                return \App\Models\AnimalEvent::where('event_id', $eventId)
                                    ->with('animal')
                                    ->get()
                                    ->sortBy(fn($item) => (float) $item->lot_number)
                                    ->mapWithKeys(fn($item) => [
                                        $item->id => "Lote {$item->lot_number} - " . ($item->animal?->name ?? 'S/N')
                                    ]);
                            })
                            ->live() // Essencial para atualizar os placeholders abaixo
                            ->required()
                            ->searchable(),

                        // --- SEÇÃO DE INFORMAÇÕES DE VALORES ---
                        Grid::make(3) // Divide em 3 colunas para mostrar os valores lado a lado
                            ->schema([
                                Placeholder::make('valor_minimo')
                                    ->label('Lance Mínimo Inicial')
                                    ->content(function (Get $get) {
                                        $pivotId = $get('animal_event_id');
                                        if (!$pivotId) return '---';

                                        $ae = \App\Models\AnimalEvent::find($pivotId);
                                        return $ae ? 'R$ ' . number_format($ae->min_value, 2, ',', '.') : '---';
                                    }),

                                Placeholder::make('lance_atual')
                                    ->label('Lance Atual (Aprovado)')
                                    ->content(function (Get $get) {
                                        $pivotId = $get('animal_event_id');
                                        if (!$pivotId) return '---';

                                        $maxBid = \App\Models\Bid::where('animal_event_id', $pivotId)
                                            ->where('status', 1) // Apenas aprovados
                                            ->max('amount');

                                        return $maxBid ? 'R$ ' . number_format($maxBid, 2, ',', '.') : 'Nenhum lance';
                                    }),

                                Placeholder::make('proximo_lance')
                                    ->label('Sugestão Próximo Lance')
                                    ->extraAttributes(['class' => 'text-success-600 font-bold'])
                                    ->content(function (Get $get) {
                                        $pivotId = $get('animal_event_id');
                                        if (!$pivotId) return '---';

                                        $ae = \App\Models\AnimalEvent::find($pivotId);
                                        if (!$ae) return '---';

                                        $maxBid = \App\Models\Bid::where('animal_event_id', $pivotId)
                                            ->where('status', '<>', 2) // Não rejeitados
                                            ->max('amount');

                                        $base = $maxBid ?: $ae->min_value;
                                        $sugestao = $base + ($ae->increment_value ?? 0);

                                        return new HtmlString('<span style="color: green; font-weight: bold;">R$ ' . number_format($sugestao, 2, ',', '.') . '</span>');
                                    }),
                            ])
                            ->visible(fn(Get $get) => filled($get('animal_event_id'))), // Só mostra se houver animal selecionado

                        // Campo de preenchimento do valor
                        Forms\Components\TextInput::make('amount')
                            ->label('Valor do Lance')
                            ->numeric()
                            ->prefix('R$')
                            ->required()
                            ->helperText('O valor deve ser igual ou superior à sugestão do próximo lance.')
                            ->columnSpanFull(),

                        Forms\Components\Select::make('user_id')
                            ->label('Cliente')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->required(),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(BidTable::columns())
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Action::make('report')
                        ->label('Ficha')
                        ->icon('heroicon-o-document-text')
                        ->color('info')
                        ->url(function ($record) {
                            $client = $record->user?->client;
                            return $client ? route('client-details-pdf', $client->id) : null;
                        })->openUrlInNewTab(),

                    // Aprovar
                    Tables\Actions\Action::make('aprovar')
                        ->label('Aprovar')
                        ->color('success')
                        ->icon('heroicon-o-check')
                        ->visible(fn(Bid $record) => $record->status === 0) // só pendente
                        ->requiresConfirmation()
                        ->action(fn(Bid $record) => $record->update([
                            'status' => 1,
                            'approved_by' => auth()->id(),
                        ])),

                    // Rejeitar
                    Tables\Actions\Action::make('rejeitar')
                        ->label('Rejeitar')
                        ->color('danger')
                        ->icon('heroicon-o-x-circle')
                        ->visible(fn(Bid $record) => $record->status === 0) // só pendente
                        ->requiresConfirmation()
                        ->action(fn(Bid $record) => $record->update([
                            'status' => 2,
                            'approved_by' => auth()->id(),
                        ])),

                    // Reverter
                    Tables\Actions\Action::make('reverter')
                        ->label('Reverter para pendente')
                        ->color('warning')
                        ->icon('heroicon-o-arrow-path')
                        ->visible(fn(Bid $record) => in_array($record->status, [1, 2]))
                        ->requiresConfirmation()
                        ->action(fn(Bid $record) => $record->update([
                            'status' => 0,
                            'approved_by' => null,
                        ])),
                ])
                    ->label('Ações') // texto do botão do grupo
                    ->icon('heroicon-o-cog-6-tooth')
                    ->color('gray'),
            ], position: ActionsPosition::BeforeColumns)
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        1 => 'Aprovados',
                        0 => 'Pendentes',
                        2 => 'Rejeitados',
                    ])
                    ->multiple(),

                Tables\Filters\Filter::make('published_events')
                    ->label('Somente eventos publicados')
                    ->toggle() // transforma em checkbox
                    ->query(fn($query) => $query->whereHas('event', fn($q) => $q->where('published', true))),

                Tables\Filters\SelectFilter::make('event_id')
                    ->label('Evento')
                    ->searchable()
                    ->relationship('event', 'name'),

                // Tables\Filters\SelectFilter::make('user_id')
                //     ->label('Cliente')
                //     ->searchable()
                //     ->relationship('user', 'name'),
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Cliente')
                    ->relationship('user', 'name')
                    ->searchable(),
            ])
            ->deferFilters()
            ->filtersApplyAction(
                fn(Action $action) => $action
                    ->link()
                    ->label('Aplicar Filtro(s)'),
            )
            ->groups([
                Group::make('event.name')
                    ->label('Evento')
                    ->collapsible(),

                Group::make('user.name')
                    ->label('Cliente')
                    ->collapsible(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBids::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return true; // Permitir criação de lances manualmente
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
