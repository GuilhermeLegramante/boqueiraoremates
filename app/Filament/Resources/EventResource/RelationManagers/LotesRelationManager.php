<?php

namespace App\Filament\Resources\EventResource\RelationManagers;

use App\Models\Animal;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Leandrocfe\FilamentPtbrFormFields\Money;

class LotesRelationManager extends RelationManager
{
    protected static string $relationship = 'lotes';

    protected static ?string $title = 'Lotes';
    protected static ?string $label = 'Lote';
    protected static ?string $pluralLabel = 'Lotes';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('animal_id')
                    ->label('Animal')
                    ->options(Animal::all()->pluck('name', 'id'))
                    ->searchable()
                    ->columnSpanFull()
                    ->required(),

                TextInput::make('name')
                    ->label('Nome do Animal p/ o Lote')
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255),

                TextInput::make('situation')
                    ->columnSpanFull()
                    ->label('Situação do Animal (inteiro, castrado, etc.)')
                    ->maxLength(255),

                TextInput::make('lot_number')
                    ->label('Número do Lote')
                    ->columnSpanFull()
                    ->required(),

                TextInput::make('min_value')
                    ->label('Lance Mínimo')
                    ->prefix('R$')
                    ->numeric()
                    ->live()
                    ->debounce(1000)
                    ->required(),

                TextInput::make('increment_value')
                    ->label('Valor do Incremento')
                    ->prefix('R$')
                    ->numeric()
                    ->live()
                    ->debounce(1000)
                    ->nullable(),

                TextInput::make('target_value')
                    ->label('Lance-alvo')
                    ->prefix('R$')
                    ->numeric()
                    ->live()
                    ->debounce(1000)
                    ->nullable(),

                TextInput::make('final_value')
                    ->label('Valor Final')
                    ->prefix('R$')
                    ->numeric()
                    ->live()
                    ->debounce(1000)
                    ->nullable(),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'disponivel' => 'Disponível',
                        'vendido'    => 'Vendido',
                        'reservado'  => 'Reservado',
                    ])
                    ->columnSpanFull()
                    ->default('disponivel'),

                FileUpload::make('photo')->columnSpanFull()->label('Foto (Miniatura)')->image()->directory('animals/photos')->nullable(),
                FileUpload::make('photo_full')->columnSpanFull()->label('Foto (Grande)')->image()->directory('animals/photos_full')->nullable(),
                RichEditor::make('note')->columnSpanFull()->label('Comentário')->nullable(),
                TextInput::make('video_link')->columnSpanFull()->label('Link do Vídeo')->url()->nullable(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\ImageColumn::make('photo')
                    ->label('Foto')
                    ->square(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Animal')
                    ->sortable(query: fn($query, $direction) => $query->orderBy('animal_event.name', $direction))
                    ->searchable(query: fn($query, $search) => $query->where('animal_event.name', 'like', "%{$search}%")),

                Tables\Columns\TextColumn::make('lot_number')
                    ->label('Lote')
                    ->sortable(query: fn($query, $direction) => $query->orderBy('animal_event.lot_number', $direction)),

                Tables\Columns\TextColumn::make('min_value')
                    ->label('Lance Mínimo')
                    ->money('BRL'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'success' => 'disponivel',
                        'warning' => 'reservado',
                        'danger'  => 'vendido',
                    ]),
            ])
            ->defaultSort('lot_number')
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
