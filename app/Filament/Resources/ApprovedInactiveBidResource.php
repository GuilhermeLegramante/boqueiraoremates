<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApprovedActiveBidResource\Pages\ListApprovedActiveBids;
use App\Filament\Resources\ApprovedInactiveBidResource\Pages\ListApprovedInactiveBids;
use App\Filament\Resources\BidResource\Pages;
use App\Filament\Resources\PendingBidResource\Pages\ListPendingBids;
use App\Filament\Tables\BidTable;
use App\Models\Bid;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Grouping\Group;
use Illuminate\Database\Eloquent\Builder;

class ApprovedInactiveBidResource extends Resource
{
    protected static ?string $model = Bid::class;
    protected static ?string $navigationIcon = 'heroicon-o-check';
    protected static ?string $navigationLabel = 'Aprovados - Leilões INATIVOS';
    protected static ?string $pluralLabel = 'Lances Aprovados - Leilões INATIVOS';

    protected static ?string $slug = 'lances-aprovados-leiloes-inativos';

    protected static ?string $navigationGroup = 'Lances';

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query
                ->where('status', 1)
                ->whereHas('event', fn($q) => $q->where('published', false)))
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
            'index' => ListApprovedInactiveBids::route('/'),
        ];
    }
}
