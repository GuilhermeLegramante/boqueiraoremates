<?php

namespace App\Filament\Resources\EventResource\RelationManagers;

use App\Filament\Forms\AnimalForm;
use App\Models\Animal;
use App\Models\AnimalEvent;
use App\Models\Bid;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use App\Models\Event;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Get;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;

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
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        // $this refere-se ao RelationManager; event id do ownerRecord
                        $eventId = $this->ownerRecord->id ?? null;

                        if (! $state || ! $eventId) {
                            $set('current_bid_display', 'R$ 0,00');
                            return;
                        }

                        // tenta achar o registro pivot (animal_event) para este animal+evento
                        $animalEvent = AnimalEvent::where('animal_id', $state)
                            ->where('event_id', $eventId)
                            ->first();

                        if ($animalEvent) {
                            // busca maior lance aprovado (status = 1)
                            $bid = Bid::where('animal_event_id', $animalEvent->id)
                                ->where('status', 1)
                                ->orderByDesc('amount')
                                ->first();

                            $current = $bid ? $bid->amount : ($animalEvent->min_value ?? 0);
                        } else {
                            // ainda não existe pivot: mostrar 0 (ou outra lógica se preferir)
                            $current = 0;
                        }

                        $set('current_bid_display', 'R$ ' . number_format($current, 2, ',', '.'));
                    })
                    ->afterStateHydrated(function ($state, callable $set) {
                        // mesma lógica ao hidratar (edição)
                        $eventId = $this->ownerRecord->id ?? null;

                        if (! $state || ! $eventId) {
                            $set('current_bid_display', 'R$ 0,00');
                            return;
                        }

                        $animalEvent = AnimalEvent::where('animal_id', $state)
                            ->where('event_id', $eventId)
                            ->first();

                        if ($animalEvent) {
                            $bid = Bid::where('animal_event_id', $animalEvent->id)
                                ->where('status', 1)
                                ->orderByDesc('amount')
                                ->first();

                            $current = $bid ? $bid->amount : ($animalEvent->min_value ?? 0);
                        } else {
                            $current = 0;
                        }

                        $set('current_bid_display', 'R$ ' . number_format($current, 2, ',', '.'));
                    })
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
                    ->label('Lance Inicial')
                    ->prefix('R$')
                    ->numeric()
                    ->live()
                    ->debounce(1000)
                    ->required(),

                TextInput::make('current_bid_display')
                    ->label('Lance atual')
                    ->disabled()
                    ->dehydrated(false) // 🔹 não salva no banco
                    ->columnSpanFull(),

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

                // 🔹 NOVO CAMPO: Lote Vinculado (Múltipla-escolha)
                Select::make('linked_animal_event_id')
                    ->label('Lote Vinculado (Múltipla-escolha)')
                    ->placeholder('Selecione um lote caso este seja de múltipla-escolha')
                    ->options(function ($record) {
                        $eventId = $this->ownerRecord->id ?? null;
                        if (!$eventId) return [];

                        return AnimalEvent::where('event_id', $eventId)
                            // Evita que o lote liste a si mesmo na edição
                            ->when($record, fn($query) => $query->where('id', '!=', $record->id))
                            ->get()
                            ->mapWithKeys(function ($item) {
                                return [$item->id => "Lote {$item->lot_number} - {$item->name}"];
                            });
                    })
                    ->searchable()
                    ->columnSpanFull()
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

                Toggle::make('visible')
                    ->label('Publicado')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordAction(null)
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\ViewColumn::make('photo')
                    ->label('Foto (Mini)')
                    ->view('filament.tables.columns.inline-uploader'),

                Tables\Columns\ViewColumn::make('photo_full')
                    ->label('Foto (Grande)')
                    ->view('filament.tables.columns.inline-uploader'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Animal')
                    ->sortable(query: fn($query, $direction) => $query->orderBy('animal_event.name', $direction))
                    ->searchable(query: fn($query, $search) => $query->where('animal_event.name', 'like', "%{$search}%"))
                    ->action(
                        Tables\Actions\Action::make('editAnimal')
                            ->label('Editar Animal')
                            ->icon('heroicon-o-pencil-square')
                            ->mountUsing(fn($form, $record) => $form->fill(
                                \App\Models\Animal::find($record->animal_id)->toArray()
                            ))
                            ->form(AnimalForm::form())
                            ->action(function (array $data, $record): void {
                                $animal = \App\Models\Animal::find($record->animal_id);
                                $animal->update($data);
                            })
                            ->modalHeading('Editar Animal')
                            ->modalWidth('xl')
                    )
                    ->color('primary')
                    ->weight('bold'),

                Tables\Columns\TextInputColumn::make('lot_number')
                    ->label('Lote')
                    ->rules(['required', 'max:255']) // Regras de validação opcionais
                    ->sortable(query: fn($query, $direction) => $query->orderBy('animal_event.lot_number', $direction)),

                Tables\Columns\TextColumn::make('min_value')
                    ->label('Lance Inicial')
                    ->money('BRL')
                    ->alignRight(),

                Tables\Columns\TextColumn::make('current_bid_display')
                    ->label('Lance atual')
                    ->sortable(false)
                    ->getStateUsing(function ($record) {
                        // tenta pegar o ID do pivot (animal_event)
                        $animalEventId = $record->id ?? $record->pivot?->id;

                        if (! $animalEventId) {
                            return 'R$ 0,00';
                        }

                        // busca o maior lance aprovado (status = 1)
                        $bid = Bid::where('animal_event_id', $animalEventId)
                            ->where('status', 1)
                            ->orderByDesc('amount')
                            ->first();

                        $currentBid = $bid?->amount ?? ($record->min_value ?? 0);

                        return 'R$ ' . number_format($currentBid, 2, ',', '.');
                    })
                    ->alignRight(),

                // 🔹 OPCONAL: Coluna na tabela para ver facilmente se há vínculo
                Tables\Columns\TextColumn::make('linkedLot.name')
                    ->label('Lote Vinculado')
                    ->placeholder('Nenhum')
                    ->description(fn($record) => $record->linkedLot ? "Lote: {$record->linkedLot->lot_number}" : null)
                    ->toggleable(),

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
                Action::make('importarLotes')
                    ->label('Importar Lotes')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('primary')
                    ->modalWidth('3xl') // Aumentamos a largura para acomodar melhor as abas e colunas
                    ->form([
                        Tabs::make('Abas de Importação')
                            ->tabs([

                                // 🔹 ABA 1: IMPORTAÇÃO POR EVENTO
                                Tabs\Tab::make('Por Evento')
                                    ->icon('heroicon-o-calendar')
                                    ->schema([
                                        Select::make('source_event_id')
                                            ->label('Evento de Origem')
                                            ->options(function () {
                                                return Event::orderBy('start_date', 'desc')
                                                    ->pluck('name', 'id');
                                            })
                                            ->live(),

                                        Placeholder::make('no_lots_warning')
                                            ->hiddenLabel()
                                            ->content('⚠️ Nenhum lote encontrado para o evento selecionado.')
                                            ->visible(function (Get $get) {
                                                $eventId = $get('source_event_id');
                                                return $eventId && AnimalEvent::where('event_id', $eventId)->count() === 0;
                                            }),

                                        CheckboxList::make('selected_lots_by_event')
                                            ->label('Selecione os Lotes para Clonar')
                                            ->options(function (Get $get) {
                                                $eventId = $get('source_event_id');
                                                if (! $eventId) return [];

                                                return AnimalEvent::where('event_id', $eventId)
                                                    ->get()
                                                    ->mapWithKeys(fn($lot) => [$lot->id => "Lote {$lot->lot_number} - {$lot->name}"])
                                                    ->toArray();
                                            })
                                            ->columns(2)
                                            ->visible(function (Get $get) {
                                                $eventId = $get('source_event_id');
                                                return $eventId && AnimalEvent::where('event_id', $eventId)->count() > 0;
                                            }),
                                    ]),

                                // 🔹 ABA 2: IMPORTAÇÃO POR BUSCA INDIVIDUAL (NOME)
                                Tabs\Tab::make('Por Busca Individual')
                                    ->icon('heroicon-o-magnifying-glass')
                                    ->schema([
                                        Select::make('selected_lots_by_search')
                                            ->label('Buscar Lotes pelo Nome')
                                            ->placeholder('Digite parte do nome do lote/animal...')
                                            ->helperText('Você pode selecionar múltiplos lotes digitando e adicionando um por um.')
                                            ->multiple() // Permite adicionar vários lotes específicos de eventos diferentes
                                            ->searchable()
                                            ->getSearchResultsUsing(function (string $search) {
                                                // Busca assíncrona no banco conforme o usuário digita
                                                return AnimalEvent::query()
                                                    ->with('event')
                                                    ->where('name', 'like', "%{$search}%")
                                                    ->limit(20)
                                                    ->get()
                                                    ->mapWithKeys(function ($lot) {
                                                        $eventName = $lot->event?->name ?? 'Sem Evento';
                                                        return [$lot->id => "{$lot->name} (Lote {$lot->lot_number} - Ref: {$eventName})"];
                                                    })
                                                    ->toArray();
                                            })
                                            ->getOptionLabelsUsing(function (array $values) {
                                                return AnimalEvent::query()
                                                    ->with('event')
                                                    ->whereIn('id', $values)
                                                    ->get()
                                                    ->mapWithKeys(fn($lot) => [$lot->id => "{$lot->name} (Lote {$lot->lot_number})"])
                                                    ->toArray();
                                            }),
                                    ]),
                            ])->columnSpanFull(),
                    ])
                    ->action(function (array $data) {
                        // Unifica os IDs selecionados de ambas as abas em uma única coleção única
                        $lotsFromEvent = $data['selected_lots_by_event'] ?? [];
                        $lotsFromSearch = $data['selected_lots_by_search'] ?? [];

                        $allSelectedIds = array_unique(array_merge($lotsFromEvent, $lotsFromSearch));

                        if (empty($allSelectedIds)) {
                            Notification::make()
                                ->title('Nenhum lote foi selecionado para importação.')
                                ->warning()
                                ->send();
                            return;
                        }

                        $destinationEvent = $this->getOwnerRecord();
                        $lotsToClone = AnimalEvent::whereIn('id', $allSelectedIds)->get();

                        foreach ($lotsToClone as $oldLot) {
                            $pivotData = $oldLot->toArray();

                            unset(
                                $pivotData['id'],
                                $pivotData['event_id'],
                                $pivotData['created_at'],
                                $pivotData['updated_at']
                            );

                            foreach (['photo', 'photo_full'] as $photoField) {
                                if (!empty($oldLot->$photoField) && Storage::disk('public')->exists($oldLot->$photoField)) {
                                    $newPath = 'animals/copies/' . basename($oldLot->$photoField);
                                    Storage::disk('public')->copy($oldLot->$photoField, $newPath);
                                    $pivotData[$photoField] = $newPath;
                                }
                            }

                            $destinationEvent->animals()->attach($oldLot->animal_id, $pivotData);
                        }

                        Notification::make()
                            ->title('Lotes importados com sucesso!')
                            ->success()
                            ->send();
                    }),
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

    // Adicione este método dentro da classe LotesRelationManager

    public function updated($property, $value)
    {
        // Verifica se a propriedade alterada vem do nosso uploader customizado
        // Formato esperado: mountedTableActionsData.{id}.{campo}
        if (str_starts_with($property, 'mountedTableActionsData.')) {
            $parts = explode('.', $property);

            if (count($parts) === 3) {
                $recordId = $parts[1];
                $fieldName = $parts[2]; // 'photo' ou 'photo_full'

                // Valida se é um dos nossos campos de imagem
                if (in_array($fieldName, ['photo', 'photo_full']) && $value instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {

                    // Busca o registro do lote (AnimalEvent)
                    $record = $this->getOwnerRecord()->lotes()->find($recordId);

                    if ($record) {
                        // Define o diretório baseado no campo
                        $directory = $fieldName === 'photo' ? 'animals/photos' : 'animals/photos_full';

                        // Salva o arquivo permanentemente no disco público
                        $path = $value->store($directory, 'public');

                        // Atualiza o banco de dados
                        $record->update([
                            $fieldName => $path
                        ]);

                        // Opcional: envia notificação toast discreta
                        $this->dispatch('notify', [
                            'status' => 'success',
                            'message' => 'Imagem do lote atualizada!',
                        ]);
                    }
                }
            }
        }
    }
}
