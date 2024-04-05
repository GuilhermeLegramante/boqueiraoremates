<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderStatusResource\Pages;
use App\Filament\Resources\OrderStatusResource\Pages\ManageOrderStatuses;
use App\Filament\Resources\OrderStatusResource\RelationManagers;
use App\Models\OrderStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class OrderStatusResource extends Resource
{
    protected static ?string $model = OrderStatus::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = 'status da OS';

    protected static ?string $pluralModelLabel = 'status da OS';

    protected static ?string $navigationGroup = 'Parâmetros';

    protected static ?string $slug = 'status-da-os';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('field.name'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextArea::make('note')
                    ->label(__('field.note'))
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('fields.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('note')
                    ->label(__('fields.note'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('fields.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('fields.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->mutateRecordDataUsing(function (array $data): array {
                        $data['name'] = Str::upper($data['name']);
                        $data['note'] = Str::upper($data['note']);

                        return $data;
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageOrderStatuses::route('/'),
        ];
    }
}
