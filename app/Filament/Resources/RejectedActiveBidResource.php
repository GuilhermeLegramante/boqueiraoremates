<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RejectedActiveBidResource\Pages\ListRejectedActiveBids;
use App\Filament\Tables\BidTable;
use App\Models\Bid;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Grouping\Group;
use Illuminate\Database\Eloquent\Builder;

class RejectedActiveBidResource extends Resource
{
    protected static ?string $model = Bid::class;
    protected static ?string $navigationIcon = 'heroicon-o-x-circle';
    protected static ?string $navigationLabel = 'Reprovados - Leilões ATIVOS';
    protected static ?string $pluralLabel = 'Lances Reprovados - Leilões ATIVOS';

    protected static ?string $slug = 'lances-reprovados-leiloes-ativos';

    protected static ?string $navigationGroup = 'Lances';

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
          ->modifyQueryUsing(function (Builder $query) {
                $eventId = session('selected_event_id');
                $lotId = session('selected_lot_id');
                $clientId = session('selected_client_id');
                $statusId = session('selected_status_id');

                // Se nenhum evento selecionado, não retorna nenhum lance
                if (!$eventId) {
                    return $query->whereRaw('1 = 0'); // força query vazia
                }

                $query->when($eventId, fn($q) => $q->where('event_id', $eventId))
                    ->when($lotId, fn($q) => $q->where('animal_event_id', $lotId))
                    ->when($clientId, fn($q) => $q->where('user_id', $clientId))
                    ->when($statusId !== null, fn($q) => $q->where('status', $statusId));

                return $query;
            })
            ->header(function () {
                return view('filament.tables.headers.bid-filters', [
                    'eventsQuery' => \App\Models\Event::query()->where('status', 2)->where('published', false),
                    'lotsQuery' => \App\Models\AnimalEvent::query(),
                    'usersQuery' => \App\Models\User::query(),
                    'statusOptions' => [0, 1, 2],
                ]);
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
            ->filters([
                Tables\Filters\SelectFilter::make('event_id')
                    ->label('Evento')
                    ->searchable()
                    ->relationship('event', 'name'),

                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Cliente')
                    ->searchable()
                    ->relationship('user', 'name'),
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

    public static function getPages(): array
    {
        return [
            'index' => ListRejectedActiveBids::route('/'),
        ];
    }
}
