<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class SellerParcelsRelationManager extends RelationManager
{
    protected static string $relationship = 'sellerParcels';

    protected static ?string $title = 'Parcelas (Faturamento pelo Vendedor)';

    protected static ?string $label = 'Parcela';

    protected static ?string $pluralLabel = 'Parcelas (Faturamento pelo Vendedor)';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('number')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('number')
            ->columns([
                TextColumn::make('number')
                    ->label('Número'),
                TextColumn::make('date')
                    ->label('Data de Vencimento')
                    ->formatStateUsing(
                        fn (string $state): string => (date('d/m/Y', strtotime($state)))
                    ),
                TextColumn::make('value')
                    ->label('Valor')
                    ->money('BRL')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                ToggleColumn::make('paid')
                    ->sortable()
                    ->label('Paga'),
                TextInputColumn::make('note')
                    ->label('Observação')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
