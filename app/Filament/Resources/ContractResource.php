<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContractResource\Pages;
use App\Filament\Resources\ContractResource\Pages\ViewContract;
use App\Models\Contract;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ContractResource extends Resource
{
    protected static ?string $model = Contract::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Vendas';

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $modelLabel = 'Contrato';

    protected static ?string $pluralModelLabel = 'Contratos';

    protected static ?string $slug = 'contratos';
    
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('Contrato')
                    ->sortable(),

                TextColumn::make('order.number')
                    ->label('OS')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('order.event.name')
                    ->label('Evento')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('order.seller.name')
                    ->label('Vendedor')
                    ->searchable(),

                TextColumn::make('order.buyer.name')
                    ->label('Comprador')
                    ->searchable(),

                TextColumn::make('order.animal.name')
                    ->label('Animal')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'generated' => 'Emitido',
                        'cancelled' => 'Cancelado',
                        default => ucfirst($state),
                    }),

                TextColumn::make('generated_at')
                    ->label('Emitido em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('generatedBy.name')
                    ->label('Emitido por')
                    ->toggleable(),

                TextColumn::make('version')
                    ->label('Versão')
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            ->defaultSort('id', 'desc')

            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'generated' => 'Emitido',
                        'cancelled' => 'Cancelado',
                    ]),

                SelectFilter::make('seller')
                    ->label('Vendedor')
                    ->relationship('order.seller', 'name')
                    ->searchable(),

                SelectFilter::make('buyer')
                    ->label('Comprador')
                    ->relationship('order.buyer', 'name')
                    ->searchable(),

                Filter::make('generated_at')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('from')
                            ->label('Emitido a partir de'),

                        \Filament\Forms\Components\DatePicker::make('until')
                            ->label('Emitido até'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'] ?? null,
                                fn(Builder $query, $date) =>
                                $query->whereDate('generated_at', '>=', $date)
                            )
                            ->when(
                                $data['until'] ?? null,
                                fn(Builder $query, $date) =>
                                $query->whereDate('generated_at', '<=', $date)
                            );
                    }),
            ])

            ->actions([
                Tables\Actions\ViewAction::make(),

                Tables\Actions\Action::make('order')
                    ->label('Abrir OS')
                    ->icon('heroicon-o-document-text')
                    ->url(
                        fn(Contract $record) =>
                        \App\Filament\Resources\OrderResource::getUrl(
                            'edit',
                            ['record' => $record->order_id]
                        )
                    ),
            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContracts::route('/'),
            'view' => ViewContract::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }
}
