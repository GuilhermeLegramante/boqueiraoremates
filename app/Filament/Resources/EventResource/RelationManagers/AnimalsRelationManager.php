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
                Tables\Actions\CreateAction::make('criarLote')
                    ->label('Adicionar Lote')
                    ->modalHeading('Criar Lote')
                    ->form([
                        Select::make('animal_id')
                            ->label('Animal')
                            ->relationship('animals', 'name')
                            ->required(),

                        TextInput::make('lot_number')->label('Número do Lote')->required(),
                        Money::make('min_value')->label('Lance Mínimo')->required(),
                        Money::make('final_value')->label('Valor Final'),
                        Money::make('increment_value')->label('Valor do Incremento'),
                        Money::make('target_value')->label('Lance Alvo'),
                        Textarea::make('note')->label('Comentário')->rows(4)->columnSpanFull(),
                        TextInput::make('video_link')->label('Link do Vídeo')->url()->columnSpanFull(),
                        Select::make('status')->label('Status')->options([
                            'disponivel' => 'Disponível',
                            'vendido' => 'Vendido',
                            'reservado' => 'Reservado',
                        ])->default('disponivel'),
                    ])
                    ->action(function ($data) {
                        $event = $this->getOwnerRecord();

                        // Cria o lote diretamente no pivot (ou na tabela intermediária)
                        $event->animals()->attach($data['animal_id'], [
                            'lot_number'      => $data['lot_number'],
                            'min_value'       => $data['min_value'],
                            'final_value'     => $data['final_value'],
                            'increment_value' => $data['increment_value'],
                            'target_value'    => $data['target_value'],
                            'status'          => $data['status'],
                            'note'            => $data['note'],
                            'video_link'      => $data['video_link'],
                        ]);
                    })
                    ->successNotificationTitle('Lote criado com sucesso!'),
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
