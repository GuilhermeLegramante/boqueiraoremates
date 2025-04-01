<?php

namespace App\Filament\Resources;

use App\Filament\Forms\EventForm;
use App\Filament\Resources\SalesMapResource\Pages;
use App\Filament\Resources\SalesMapResource\RelationManagers;
use App\Models\SalesMap;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class SalesMapResource extends Resource
{
    protected static ?string $model = SalesMap::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'event.name';

    protected static ?string $modelLabel = 'mapa de vendas';

    protected static ?string $pluralModelLabel = 'mapas de vendas';

    protected static ?string $slug = 'mapas-de-vendas';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('event_id')
                    ->label(__('fields.event'))
                    ->required()
                    ->unique()
                    ->preload()
                    ->searchable()
                    ->preload()
                    ->live()
                    ->hiddenOn('view')
                    ->relationship(name: 'event', titleAttribute: 'name')
                    ->createOptionForm(EventForm::form())
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('event.name')
                    ->label(__('fields.event'))
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make()
                        ->mutateRecordDataUsing(function (array $data): array {
                            $data['name'] = Str::upper($data['name']);

                            return $data;
                        }),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ], position: ActionsPosition::BeforeColumns)
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

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSalesMaps::route('/'),
            'create' => Pages\CreateSalesMap::route('/criar'),
            'edit' => Pages\EditSalesMap::route('/{record}/editar'),
            'view' => Pages\ViewSalesMap::route('/{record}'),
        ];
    }
}
