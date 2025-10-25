<?php

namespace App\Filament\Resources\EventResource\RelationManagers;

use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use App\Models\Animal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
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
                    ->options(fn() => Animal::query()
                        ->orderBy('name')
                        ->pluck('name', 'id'))
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

                Tables\Columns\TextColumn::make('pivot.name')
                    ->label('Animal')
                    ->sortable(query: function ($query, string $direction) {
                        $query->orderBy('animal_event.name', $direction); // <-- nome da tabela pivot
                    })
                    ->searchable(query: function ($query, string $search) {
                        $query->where('animal_event.name', 'like', "%{$search}%");
                    }),

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

                Tables\Columns\SelectColumn::make('pivot.status')
                    ->label('Status')
                    ->options([
                        'disponivel' => 'Disponível',
                        'reservado'  => 'Reservado',
                        'vendido'    => 'Vendido',
                    ])
                    ->selectablePlaceholder(false)
                    ->getStateUsing(fn($record) => $record->pivot?->status)
                    ->updateStateUsing(function ($state, $record) {
                        if ($record->pivot) {
                            DB::table('animal_event') // <-- coloque o nome real da tabela pivot aqui
                                ->where('id', $record->pivot->id)
                                ->update(['status' => $state]);
                        }
                    })
                // ->badge()
                // ->colors([
                //     'success' => 'disponivel',
                //     'warning' => 'reservado',
                //     'danger'  => 'vendido',
                // ])
            ])
            ->filters([])

            ->headerActions([
                Tables\Actions\CreateAction::make('criarLote')
                    ->label('Adicionar Lote ao Evento')
                    ->icon('heroicon-o-plus')
                    ->form(fn() => $this->getLoteForm())
                    ->action(fn($data) => $this->saveLote($data))
                    ->successNotificationTitle('Lote criado com sucesso!'),
            ])

            ->actions([
                Tables\Actions\EditAction::make('editarLote')
                    ->label('Editar Lote')
                    ->icon('heroicon-o-pencil')
                    ->form(fn() => $this->getLoteForm()) // <-- reutiliza o formulário centralizado
                    ->mountUsing(function ($form, $record) {
                        // Busca o pivot correto pelo ID passado
                        $pivot = DB::table('animal_event')
                            ->where('id', $record->pivot_id ?? $record->id) // record->id pode não ser o pivot_id
                            ->first();

                        if (!$pivot) return;

                        $form->fill([
                            'pivot_id'        => $pivot->id,
                            'animal_id'       => $pivot->animal_id,
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

                    ->action(function ($record, $data) {
                        $pivotId = $data['pivot_id'];

                        // Tratar uploads
                        if (isset($data['photo']) && $data['photo'] instanceof \Illuminate\Http\UploadedFile) {
                            $data['photo'] = $data['photo']->store('animals/photos', 'public');
                        }
                        if (isset($data['photo_full']) && $data['photo_full'] instanceof \Illuminate\Http\UploadedFile) {
                            $data['photo_full'] = $data['photo_full']->store('animals/photos_full', 'public');
                        }

                        // Tratar campos numéricos: vírgula -> ponto e string vazia -> null
                        $minValue       = $data['min_value'] !== '' ? str_replace(',', '.', $data['min_value']) : null;
                        $incrementValue = $data['increment_value'] !== '' ? str_replace(',', '.', $data['increment_value']) : null;
                        $targetValue    = $data['target_value'] !== '' ? str_replace(',', '.', $data['target_value']) : null;

                        DB::table('animal_event')
                            ->where('id', $pivotId)
                            ->update([
                                'animal_id'       => $data['animal_id'], // se quiser permitir trocar o animal
                                'name'            => $data['name'],
                                'situation'       => $data['situation'],
                                'lot_number'      => $data['lot_number'],
                                'min_value'       => $minValue,
                                'increment_value' => $incrementValue,
                                'target_value'    => $targetValue,
                                'status'          => $data['status'],
                                'photo'           => $data['photo'] ?? $record->pivot->photo,
                                'photo_full'      => $data['photo_full'] ?? $record->pivot->photo_full,
                                'note'            => $data['note'] ?? null,
                                'video_link'      => $data['video_link'] ?? null,
                            ]);
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

    /**
     * 🔹 Formulário do Lote (compartilhado entre Criar e Editar)
     */
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

            TextInput::make('min_value')
                ->prefix('R$')
                ->numeric()
                ->live()
                ->debounce(1000)
                ->label('Lance Mínimo'),

            TextInput::make('increment_value')
                ->prefix('R$')
                ->numeric()
                ->live()
                ->debounce(1000)
                ->label('Valor do Incremento'),

            TextInput::make('target_value')
                ->prefix('R$')
                ->numeric()
                ->live()
                ->debounce(1000)
                ->label('Lance Alvo'),

            Select::make('status')
                ->label('Status')
                ->options([
                    'disponivel' => 'Disponível',
                    'vendido'    => 'Vendido',
                    'reservado'  => 'Reservado',
                ])
                ->default('disponivel'),

            FileUpload::make('photo')
                ->label('Foto (Miniatura)')
                ->image()
                ->directory('animals/photos')
                ->visibility('public')
                ->preserveFilenames()
                ->nullable(),

            FileUpload::make('photo_full')
                ->label('Foto (Grande)')
                ->image()
                ->directory('animals/photos_full')
                ->visibility('public')
                ->preserveFilenames()
                ->nullable(),

            RichEditor::make('note')
                ->label('Comentário')
                ->columnSpanFull()
                ->maxLength(65535),

            TextInput::make('video_link')
                ->label('Link do Vídeo')
                ->url()
                ->placeholder('https://youtube.com/...')
                ->columnSpanFull(),
        ];
    }

    /**
     * 🔹 Cria o Lote (pivot attach)
     */
    protected function saveLote(array $data): void
    {
        $event = $this->getOwnerRecord();

        if (isset($data['photo']) && $data['photo'] instanceof \Illuminate\Http\UploadedFile) {
            $data['photo'] = $data['photo']->store('animals/photos', 'public');
        }

        if (isset($data['photo_full']) && $data['photo_full'] instanceof \Illuminate\Http\UploadedFile) {
            $data['photo_full'] = $data['photo_full']->store('animals/photos_full', 'public');
        }

        $event->animals()->attach($data['animal_id'], collect($data)->only([
            'name',
            'situation',
            'order',
            'lot_number',
            'min_value',
            'final_value',
            'increment_value',
            'target_value',
            'status',
            'photo',
            'photo_full',
            'note',
            'video_link',
        ])->toArray());
    }

    /**
     * 🔹 Atualiza o Lote (pivot update)
     */
    protected function updateLote($record, array $data): void
    {
        $pivot = $record->pivot;

        if (isset($data['photo']) && $data['photo'] instanceof \Illuminate\Http\UploadedFile) {
            $data['photo'] = $data['photo']->store('animals/photos', 'public');
        }

        if (isset($data['photo_full']) && $data['photo_full'] instanceof \Illuminate\Http\UploadedFile) {
            $data['photo_full'] = $data['photo_full']->store('animals/photos_full', 'public');
        }

        DB::table('animal_event')
            ->where('id', $record->pivot->id)
            ->update(collect($data)->only([
                'name',
                'situation',
                'order',
                'lot_number',
                'min_value',
                'final_value',
                'increment_value',
                'target_value',
                'status',
                'photo',
                'photo_full',
                'note',
                'video_link',
            ])->toArray());
    }
}
