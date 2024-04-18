<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use App\Filament\Tables\ParcelsTable;
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
            ->columns(ParcelsTable::table())
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
