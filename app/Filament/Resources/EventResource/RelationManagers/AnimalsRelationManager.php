<?php

namespace App\Filament\Resources\EventResource\RelationManagers;

use App\Models\Animal;
use App\Models\AnimalEvent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Leandrocfe\FilamentPtbrFormFields\Money;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class AnimalsRelationManager extends RelationManager
{
    protected static string $relationship = 'animals';

    protected static ?string $title = 'Lotes';
    protected static ?string $label = 'Lote';
    protected static ?string $pluralLabel = 'Lotes';

    public function form(Form $form): Form
    {
        return $form
            ->schema($this->getLoteForm());
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo')
                    ->label('Foto')
                    ->square(),

                Tables\Columns\TextColumn::make('animal.name')
                    ->label('Animal')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('lot_number')
                    ->label('Lote')
                    ->sortable(),

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
            ->filters([])
            ->headerActions([
                Tables\Actions\CreateAction::make('criarLote')
                    ->label('Adicionar Lote ao Evento')
                    ->form(fn() => $this->getLoteForm())
                    ->action(function ($data) {
                        $event = $this->getOwnerRecord();

                        // Tratar uploads
                        if (isset($data['photo']) && $data['photo'] instanceof \Illuminate\Http\UploadedFile) {
                            $data['photo'] = $data['photo']->store('animals/photos', 'public');
                        }
                        if (isset($data['photo_full']) && $data['photo_full'] instanceof \Illuminate\Http\UploadedFile) {
                            $data['photo_full'] = $data['photo_full']->store('animals/photos_full', 'public');
                        }

                        $event->animals()->attach($data['animal_id'], collect($data)->only([
                            'name',
                            'situation',
                            'lot_number',
                            'min_value',
                            'increment_value',
                            'target_value',
                            'final_value',
                            'status',
                            'photo',
                            'photo_full',
                            'note',
                            'video_link',
                        ])->toArray());
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make('editarLote')
                    ->label('Editar Lote')
                    ->form(fn() => $this->getLoteForm())
                    ->mountUsing(function ($form, AnimalEvent $record) {
                        // Agora $record é o pivot correto
                        $form->fill([
                            'pivot_id'        => $record->id,
                            'animal_id'       => $record->animal_id,
                            'name'            => $record->name,
                            'situation'       => $record->situation,
                            'lot_number'      => $record->lot_number,
                            'min_value'       => $record->min_value,
                            'increment_value' => $record->increment_value,
                            'target_value'    => $record->target_value,
                            'final_value'     => $record->final_value,
                            'status'          => $record->status,
                            'photo'           => $record->photo,
                            'photo_full'      => $record->photo_full,
                            'note'            => $record->note,
                            'video_link'      => $record->video_link,
                        ]);
                    })
                    ->action(function (AnimalEvent $record, array $data) {
                        // Tratar uploads
                        if (isset($data['photo']) && $data['photo'] instanceof \Illuminate\Http\UploadedFile) {
                            $data['photo'] = $data['photo']->store('animals/photos', 'public');
                        }
                        if (isset($data['photo_full']) && $data['photo_full'] instanceof \Illuminate\Http\UploadedFile) {
                            $data['photo_full'] = $data['photo_full']->store('animals/photos_full', 'public');
                        }

                        $record->update(collect($data)->only([
                            'animal_id',
                            'name',
                            'situation',
                            'lot_number',
                            'min_value',
                            'increment_value',
                            'target_value',
                            'final_value',
                            'status',
                            'photo',
                            'photo_full',
                            'note',
                            'video_link',
                        ])->toArray());
                    })
                    ->successNotificationTitle('Lote atualizado com sucesso!'),

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

    protected function getLoteForm(): array
    {
        return [
            Forms\Components\Hidden::make('pivot_id')->required(),

            Select::make('animal_id')
                ->label('Animal')
                ->options(fn() => Animal::orderBy('name')->pluck('name', 'id'))
                ->searchable()
                ->required(),

            TextInput::make('name')
                ->label('Nome do Animal p/ o Lote')
                ->required()
                ->maxLength(255),

            TextInput::make('situation')
                ->label('Situação do Animal (inteiro, castrado, etc.)')
                ->maxLength(255),

            TextInput::make('lot_number')
                ->label('Número do Lote')
                ->required(),

            Money::make('min_value')->label('Lance Mínimo')->required(),
            Money::make('increment_value')->label('Valor do Incremento')->nullable(),
            Money::make('target_value')->label('Lance Alvo')->nullable(),
            Money::make('final_value')->label('Valor Final')->nullable(),

            Select::make('status')
                ->label('Status')
                ->options([
                    'disponivel' => 'Disponível',
                    'vendido'    => 'Vendido',
                    'reservado'  => 'Reservado',
                ])
                ->default('disponivel'),

            FileUpload::make('photo')->label('Foto (Miniatura)')->image()->directory('animals/photos')->nullable(),
            FileUpload::make('photo_full')->label('Foto (Grande)')->image()->directory('animals/photos_full')->nullable(),
            RichEditor::make('note')->label('Comentário')->nullable(),
            TextInput::make('video_link')->label('Link do Vídeo')->url()->nullable(),
        ];
    }
}
