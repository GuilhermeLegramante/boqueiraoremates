<?php

namespace App\Filament\Resources\EventResource\RelationManagers;

use App\Models\Animal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
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
        return $form->schema($this->getLoteForm());
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('pivot.lot_number') // <--- aqui define a ordenação padrão
            ->columns([
                Tables\Columns\ImageColumn::make('pivot.photo')
                    ->label('Foto')
                    ->square(),

                Tables\Columns\TextColumn::make('pivot.name')
                    ->label('Animal')
                    ->sortable(query: fn($query, $direction) => $query->orderBy('animal_event.name', $direction))
                    ->searchable(query: fn($query, $search) => $query->where('animal_event.name', 'like', "%{$search}%")),

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

                        // Cria ou atualiza pivot (evita duplicidade)
                        $event->animals()->syncWithoutDetaching([
                            $data['animal_id'] => collect($data)->only([
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
                            ])->toArray()
                        ]);
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make('editarLote')
                    ->label('Editar Lote')
                    ->form(fn() => $this->getLoteForm())
                    ->mountUsing(function ($form, $record) {
                        $pivot = $record->pivot;
                        if (!$pivot) return;

                        $form->fill([
                            'animal_id'       => $record->id,
                            'name'            => $pivot->name,
                            'situation'       => $pivot->situation,
                            'lot_number'      => $pivot->lot_number,
                            'min_value'       => $pivot->min_value,
                            'increment_value' => $pivot->increment_value,
                            'target_value'    => $pivot->target_value,
                            'final_value'     => $pivot->final_value,
                            'status'          => $pivot->status,
                            'photo'           => $pivot->photo,
                            'photo_full'      => $pivot->photo_full,
                            'note'            => $pivot->note,
                            'video_link'      => $pivot->video_link,
                        ]);
                    })
                    ->action(function ($record, array $data) {
                        $event = $this->getOwnerRecord();

                        // Tratar uploads
                        if (isset($data['photo']) && $data['photo'] instanceof \Illuminate\Http\UploadedFile) {
                            $data['photo'] = $data['photo']->store('animals/photos', 'public');
                        }
                        if (isset($data['photo_full']) && $data['photo_full'] instanceof \Illuminate\Http\UploadedFile) {
                            $data['photo_full'] = $data['photo_full']->store('animals/photos_full', 'public');
                        }

                        // Atualiza o pivot
                        $event->animals()->updateExistingPivot($data['animal_id'], collect($data)->only([
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
