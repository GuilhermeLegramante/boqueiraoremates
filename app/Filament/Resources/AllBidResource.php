<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AllBidResource\Pages;
use App\Filament\Resources\AllBidResource\RelationManagers;
use App\Filament\Tables\BidTable;
use App\Models\Bid;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AllBidResource extends Resource
{
    protected static ?string $model = Bid::class;

    protected static ?string $navigationLabel = 'Lances por Cliente';
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $recordTitleAttribute = 'user.name';

    protected static ?string $modelLabel = 'lance-por-cliente';

    protected static ?string $pluralModelLabel = 'Lances por Cliente';

    protected static ?string $slug = 'lances-por-cliente';

    protected static ?string $navigationGroup = 'Lances';

    public static function form(Form $form): Form
    {
        // Não precisa de formulário, pois os lances vêm do site
        return $form;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(BidTable::columns())
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAllBids::route('/'),
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
