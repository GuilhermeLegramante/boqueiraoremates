<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientStatusResource\Pages;
use App\Models\ClientStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\TernaryFilter;

class ClientStatusResource extends Resource
{
    protected static ?string $model = ClientStatus::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationLabel = 'Status de Clientes';

    protected static ?string $modelLabel = 'Status de Cliente';

    protected static ?string $pluralModelLabel = 'Status de Clientes';

    protected static ?string $navigationGroup = 'Parâmetros';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nome')
                    ->required()
                    ->maxLength(255),

                ColorPicker::make('color')
                    ->label('Cor')
                    ->required()
                    ->default('#6B7280'),

                Toggle::make('active')
                    ->label('Ativo')
                    ->default(true)
                    ->helperText('Status inativos não devem ser utilizados para novos clientes.'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Status')
                    ->searchable()
                    ->sortable(),

                ColorColumn::make('color')
                    ->label('Cor'),

                TextColumn::make('color')
                    ->label('Código')
                    ->copyable()
                    ->copyMessage('Código da cor copiado!')
                    ->copyMessageDuration(1500),

                IconColumn::make('active')
                    ->label('Ativo')
                    ->boolean(),

                TextColumn::make('clients_count')
                    ->label('Clientes')
                    ->counts('clients')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('active')
                    ->label('Ativo'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClientStatuses::route('/'),
            'create' => Pages\CreateClientStatus::route('/create'),
            'edit' => Pages\EditClientStatus::route('/{record}/edit'),
        ];
    }
}
