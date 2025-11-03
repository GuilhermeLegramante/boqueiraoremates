<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BidResource\Pages;
use App\Filament\Resources\BidResource\RelationManagers;
use App\Filament\Tables\BidTable;
use App\Filament\Traits\HasBidFilters;
use App\Models\AnimalEvent;
use App\Models\Bid;
use App\Models\Event;
use App\Models\User;
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

class BidResource extends Resource
{
    use HasBidFilters;

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
        // Não precisa de formulário, pois os lances vêm do site
        return $form;
    }

    /**
     * Gera o nome qualificado do Resource (para isolar sessões).
     */
    protected static function sessionKey(string $key): string
    {
        return static::class . '.' . $key;
    }

    public static function table(Table $table): Table
    {
        return $table
            /**
             * 🔹 Header com o filtro customizado
             */
            ->header(function () {
                return view('filament.tables.headers.bid-filters', [
                    'resource' => class_basename(static::class), // ex: "BidResource" ou "ApprovedActiveBidResource"
                    'eventsQuery' => \App\Models\Event::query()->where('published', true),
                    'lotsQuery'   => \App\Models\AnimalEvent::query(),
                    'usersQuery'  => \App\Models\User::query(),
                    'statusOptions' => [0, 1, 2],
                ]);
            })

            /**
             * 🔹 Filtro aplicado automaticamente com base nas sessões
             */
            ->modifyQueryUsing(function (Builder $query) {
                $resource = static::class;

                $eventId  = session("{$resource}.selected_event_id");
                $lotId    = session("{$resource}.selected_lot_id");
                $clientId = session("{$resource}.selected_client_id");
                $statusId = session("{$resource}.selected_status_id");

                if (!$eventId) {
                    return $query->whereRaw('1=0');
                }

                $query->where('event_id', $eventId)
                    ->when($lotId, fn($q) => $q->where('animal_event_id', $lotId))
                    ->when($clientId, fn($q) => $q->where('user_id', $clientId))
                    ->when($statusId !== null && $statusId !== '', fn($q) => $q->where('status', $statusId));

                return $query;
            })

            ->columns(BidTable::columns())
            ->actions([
                Tables\Actions\ActionGroup::make([
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
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
