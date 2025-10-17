<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BidResource\Pages;
use App\Filament\Resources\BidResource\RelationManagers;
use App\Models\Bid;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BidResource extends Resource
{
    protected static ?string $model = Bid::class;

    protected static ?string $navigationLabel = 'Lances';
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $recordTitleAttribute = 'user.name';

    protected static ?string $modelLabel = 'lance';

    protected static ?string $pluralModelLabel = 'lances';

    protected static ?string $slug = 'lances';

    public static function form(Form $form): Form
    {
        // Não precisa de formulário, pois os lances vêm do site
        return $form;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                // Tables\Columns\TextColumn::make('user.name')->label('Cliente')->sortable(),
                TextColumn::make('cliente')
                    ->label('Cliente')
                    ->formatStateUsing(
                        fn($record) =>
                        $record->user?->client?->name ?? '—'
                    )
                    ->url(
                        fn($record) =>
                        $record->user?->client
                            ? route('client-details-pdf', $record->user->client->id)
                            : null
                    )
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-document-text')
                    ->color('info')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('event.name')->label('Evento')->sortable(),
                Tables\Columns\TextColumn::make('animal_name')->label('Animal')->sortable(),
                Tables\Columns\TextColumn::make('amount')->label('Valor')->money('BRL')->sortable(),
                Tables\Columns\IconColumn::make('status')
                    ->label('Status')
                    ->sortable()
                    ->icon(fn($state) => match ($state) {
                        1 => 'heroicon-o-check-circle',   // aprovado
                        2 => 'heroicon-o-x-circle',       // rejeitado
                        default => 'heroicon-o-clock',    // pendente
                    })
                    ->color(fn($state) => match ($state) {
                        1 => 'success',   // verde
                        2 => 'danger',    // vermelho
                        default => 'warning', // amarelo
                    }),
                Tables\Columns\TextColumn::make('approvedBy.name')->label('Aprovado por')->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')->label('Data')->dateTime()->sortable(),
            ])
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
            ])
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

                Tables\Filters\SelectFilter::make('event_id')
                    ->label('Evento')
                    ->relationship('event', 'name'),

                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Cliente')
                    ->relationship('user', 'name'),
            ])
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
