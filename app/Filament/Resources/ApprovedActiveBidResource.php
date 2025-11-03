<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApprovedActiveBidResource\Pages\ListApprovedActiveBids;
use App\Filament\Resources\BidResource\Pages;
use App\Filament\Resources\PendingBidResource\Pages\ListPendingBids;
use App\Filament\Tables\BidTable;
use App\Filament\Traits\HasBidFilters;
use App\Models\Bid;
use App\Models\Event;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Grouping\Group;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class ApprovedActiveBidResource extends Resource
{
    use HasBidFilters;

    protected static ?string $model = Bid::class;
    protected static ?string $navigationIcon = 'heroicon-o-check';
    protected static ?string $navigationLabel = 'Aprovados - Leilões ATIVOS';
    protected static ?string $pluralLabel = 'Lances Aprovados - Leilões ATIVOS';

    protected static ?string $slug = 'lances-aprovados-leiloes-ativos';

    protected static ?string $navigationGroup = 'Lances';

    protected static ?string $sessionPrefix = '_approved_active_';

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->header(fn() => view('filament.tables.headers.bid-filters', [
                'sessionPrefix' => self::$sessionPrefix,
                'events' => \App\Models\Event::where('published', true)->pluck('name', 'id'),
                'lots' => \App\Models\AnimalEvent::all(), // coleção completa, com event_id
                'users' => \App\Models\User::with('bids')->get(), // pegar objetos para poder filtrar por evento
                'statusOptions' => [0, 1, 2],
                'selectedEventId' => session(self::$sessionPrefix . "selected_event_id"),
                'selectedLotId' => session(self::$sessionPrefix . "selected_lot_id"),
                'selectedClientId' => session(self::$sessionPrefix . "selected_client_id"),
                'selectedStatusId' => session(self::$sessionPrefix . "selected_status_id"),
            ]))

            ->modifyQueryUsing(function (Builder $query) use ($table) {
                $eventId  = session(self::$sessionPrefix . "selected_event_id");
                $lotId    = session(self::$sessionPrefix . "selected_lot_id");
                $clientId = session(self::$sessionPrefix . "selected_client_id");
                $statusId = session(self::$sessionPrefix . "selected_status_id");

                // Se nenhum evento selecionado → retorna query vazia
                if (!$eventId) {
                    return $query->whereRaw('1=0');
                }

                // Aplica os filtros
                $query->where('event_id', $eventId)
                    ->when($lotId, fn($q) => $q->where('animal_event_id', $lotId))
                    ->when($clientId, fn($q) => $q->where('user_id', $clientId))
                    ->when($statusId !== null && $statusId !== '', fn($q) => $q->where('status', $statusId));

                return $query;
            })


            ->emptyStateHeading('Selecione um evento para visualizar os lances.')
            ->emptyStateIcon('heroicon-o-information-circle')

            ->columns(BidTable::columns())
            ->actions([], position: ActionsPosition::BeforeColumns)
            ->emptyStateHeading('Selecione um evento para visualizar os lances.')
            ->emptyStateIcon('heroicon-o-information-circle')
            ->defaultSort('created_at', 'desc')
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
            ->filters([
                // Tables\Filters\SelectFilter::make('event_id')
                //     ->label('Evento')
                //     ->searchable()
                //     ->relationship('event', 'name'),

                // Tables\Filters\SelectFilter::make('user_id')
                //     ->label('Cliente')
                //     ->searchable()
                //     ->relationship('user', 'name'),
            ])
            ->deferFilters()
            ->filtersApplyAction(
                fn(Action $action) => $action
                    ->link()
                    ->label('Aplicar Filtro(s)'),
            )
            ->groups([
                // Group::make('event.name')
                //     ->label('Evento')
                //     ->collapsible(),

                // Group::make('user.name')
                //     ->label('Cliente')
                //     ->collapsible(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListApprovedActiveBids::route('/'),
        ];
    }
}
