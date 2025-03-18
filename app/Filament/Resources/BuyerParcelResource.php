<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BuyerParcelResource\Pages;
use App\Filament\Resources\BuyerParcelResource\RelationManagers;
use App\Filament\Tables\ParcelsTable;
use App\Models\BuyerParcel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BuyerParcelResource extends Resource
{
    protected static ?string $model = BuyerParcel::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $recordTitleAttribute = 'number';

    protected static ?string $modelLabel = 'parcela comissão comprador';

    protected static ?string $pluralModelLabel = 'parcelas comissão comprador';

    protected static ?string $slug = 'parcelas-comissao-comprador';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(ParcelsTable::table())
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->groups([
                Group::make('order.number')
                    ->label('Ordem de Serviço')
                    ->collapsible(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListBuyerParcels::route('/'),
            'create' => Pages\CreateBuyerParcel::route('/criar'),
            'edit' => Pages\EditBuyerParcel::route('/{record}/editar'),
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
