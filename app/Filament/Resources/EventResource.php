<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\RelationManagers;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = 'evento';

    protected static ?string $pluralModelLabel = 'eventos';

    // protected static ?string $navigationGroup = 'Parâmetros';

    protected static ?string $slug = 'eventos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('fields.name'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('start_date')
                    ->label(__('fields.start_date')),
                Forms\Components\DatePicker::make('finish_date')
                    ->label(__('fields.finish_date')),
                Forms\Components\TextInput::make('multiplier')
                    ->label(__('fields.multiplier'))
                    ->numeric(),
                Forms\Components\Textarea::make('note')
                    ->label(__('fields.note'))
                    ->columnSpanFull()
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
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable()
                    ->label(__('fields.start_date')),
                Tables\Columns\TextColumn::make('finish_date')
                    ->label(__('fields.finish_date'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('multiplier')
                    ->label(__('fields.multiplier'))
                    ->numeric()
                    ->sortable(),
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
            'index' => Pages\ManageEvents::route('/'),
        ];
    }
}
