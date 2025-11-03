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
        // Não precisa de formulário, pois os lances vêm do site
        return $form;
    }

    public static function table(Table $table): Table
    {
        return $table
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
                Tables\Filters\Filter::make('status')
                    ->query(fn($query) => $query->where('status', 1))
                    ->label('Aprovados'),

                Tables\Filters\Filter::make('pendente')
                    ->query(fn($query) => $query->where('status', 0))
                    ->label('Pendentes'),

                Tables\Filters\Filter::make('rejeitado')
                    ->query(fn($query) => $query->where('status', 2))
                    ->label('Rejeitados'),

                Tables\Filters\Filter::make('published_events')
                    ->label('Somente eventos publicados')
                    ->toggle() // transforma em checkbox
                    ->query(fn($query) => $query->whereHas('event', fn($q) => $q->where('published', true))),

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
