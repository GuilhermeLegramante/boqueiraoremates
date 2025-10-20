<?php

namespace App\Filament\Resources\EventResource\RelationManagers;

use App\Models\Animal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Leandrocfe\FilamentPtbrFormFields\Money;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class AnimalsRelationManager extends RelationManager
{
    protected static string $relationship = 'animals';

    protected static ?string $title = 'Lotes';

    protected static ?string $label = 'Lote';

    protected static ?string $pluralLabel = 'Lotes';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('animal_id')
                    ->label('Animal')
                    ->relationship('animals', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('lot_number')
                    ->label('Número do Lote')
                    ->required(),

                Money::make('min_value')
                    ->label('Lance Mínimo')
                    ->required(),

                Money::make('final_value')
                    ->label('Valor Final')
                    ->nullable(),

                Money::make('increment_value')
                    ->label('Valor do Incremento')
                    ->nullable(),

                Money::make('target_value')
                    ->label('Lance Alvo')
                    ->nullable(),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'disponivel' => 'Disponível',
                        'reservado'  => 'Reservado',
                        'vendido'    => 'Vendido',
                    ])
                    ->default('disponivel'),

                FileUpload::make('photo')
                    ->label('Foto Miniatura')
                    ->image()
                    ->directory('animals/photos')
                    ->visibility('public')
                    ->dehydrated(true)
                    ->nullable(),

                FileUpload::make('photo_full')
                    ->label('Foto Grande')
                    ->image()
                    ->directory('animals/photos_full')
                    ->visibility('public')
                    ->dehydrated(true)
                    ->nullable(),

                Textarea::make('note')
                    ->label('Comentário')
                    ->rows(4)
                    ->columnSpanFull()
                    ->nullable(),

                TextInput::make('video_link')
                    ->label('Link do Vídeo')
                    ->url()
                    ->columnSpanFull()
                    ->nullable(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('pivot.photo')
                    ->label('Foto')
                    ->square(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Animal')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('pivot.lot_number')
                    ->label('Lote')
                    ->sortable(),

                Tables\Columns\TextColumn::make('pivot.min_value')
                    ->label('Lance Mínimo')
                    ->money('BRL'),

                Tables\Columns\TextColumn::make('pivot.status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'success' => 'disponivel',
                        'warning' => 'reservado',
                        'danger'  => 'vendido',
                    ]),
            ])
            ->filters([])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Adicionar Lote')
                    ->modalHeading('Adicionar Lote')
                    ->form($this->form(app(Forms\Form::class))->getSchema())
                    ->using(function ($livewire, array $data) {
                        // Cria a relação pivot manualmente, salvando todos os dados
                        $livewire->getOwnerRecord()->animals()->attach(
                            $data['animal_id'],
                            collect($data)->except('animal_id')->toArray()
                        );
                    })
                    ->successNotificationTitle('Lote adicionado com sucesso!'),

                ExportAction::make()
                    ->label('Exportar')
                    ->exports([
                        ExcelExport::make()
                            ->fromTable()
                            ->withFilename(now()->format('d-m-Y') . ' - Lotes'),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make()
                    ->label('Remover')
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make()->label('Exportar Selecionados'),
                ]),
            ]);
    }
}
