<?php

namespace App\Filament\Resources\EventResource\RelationManagers;

use App\Filament\Forms\AnimalForm;
use App\Models\Animal;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Illuminate\Support\Str;
use Leandrocfe\FilamentPtbrFormFields\Money;
use Filament\Support\RawJs;

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
                Placeholder::make('animal_details')
                    ->label('')
                    ->columnSpanFull()
                    ->content(function ($get) {
                        $animal = Animal::with(['breed', 'coat', 'animalType'])->find($get('animal_id'));

                        if (!$animal) return 'Nenhum animal selecionado';

                        $photoUrl = $animal->photo ? asset('storage/' . $animal->photo) : 'https://via.placeholder.com/150';
                        $status = $get('status') ? $get('status') : '-';

                        // cores do status
                        $bgColor = '#6b7280'; // cinza padrão
                        $textColor = '#fff';

                        if ($status === 'disponivel') $bgColor = '#22c55e'; // verde
                        if ($status === 'vendido') $bgColor = '#ef4444'; // vermelho
                        if ($status === 'reservado') {
                            $bgColor = '#facc15';
                            $textColor = '#000';
                        } // amarelo

                        // campos do animal com ternário (compatível PHP < 8)
                        $breed = $animal->breed ? $animal->breed->name : '-';
                        $coat = $animal->coat ? $animal->coat->name : '-';
                        $type = $animal->animalType ? $animal->animalType->name : '-';
                        $gender = $animal->gender == 'male' ? 'Macho' : 'Fêmea';
                        $sbb = $animal->sbb ? $animal->sbb : '-';
                        $rb = $animal->rb ? $animal->rb : '-';
                        $register = $animal->register ? $animal->register : '-';
                        $bloodLevel = $animal->blood_level ? $animal->blood_level : '-';
                        $bloodPercent = $animal->blodd_percentual ? $animal->blodd_percentual : '-';
                        $breeding = $animal->breeding ? $animal->breeding : '-';
                        $quantity = $animal->quantity ? $animal->quantity : '-';
                        $birthDate = $animal->birth_date ? Carbon::parse($animal->birth_date)->format('d/m/Y') : '-';

                        // dados do lote
                        $lotNumber = $get('lot_number') ? $get('lot_number') : '-';
                        $minValue = $get('min_value') ? 'R$ ' . number_format($get('min_value'), 2, ',', '.') : '-';
                        $finalValue = $get('final_value') ? 'R$ ' . number_format($get('final_value'), 2, ',', '.') : '-';

                        return new HtmlString("
        <div class='flex flex-col md:flex-row gap-6 p-6 bg-white shadow-lg rounded-xl'>
            <!-- Foto com carimbo diagonal -->
            <div class='relative flex-shrink-0'>
                <img src='{$photoUrl}' alt='{$animal->name}' class='w-36 h-36 object-cover rounded-lg border border-gray-200'>
                <span style='position:absolute; top:8px; right:-16px; transform: rotate(-20deg); 
                             background-color:{$bgColor}; color:{$textColor}; 
                             padding:4px 12px; font-weight:bold; font-size:0.875rem; border-radius:4px; z-index:50; box-shadow:0 2px 6px rgba(0,0,0,0.2);'>
                    {$status}
                </span>
            </div>

            <!-- Dados do animal e lote -->
            <div class='flex-1 grid grid-cols-1 md:grid-cols-2 gap-2'>
                <h2 class='col-span-2 font-bold text-2xl text-gray-800'>{$animal->name}</h2>
                <br>        
                <!-- Seção Animal -->
                <h3 class='col-span-2 font-semibold text-gray-600 mt-2'>Dados do Animal</h3>
                <br>
                <p><strong>Raça:</strong> {$breed}</p>
                <p><strong>Pelagem:</strong> {$coat}</p>
                <p><strong>Tipo:</strong> {$type}</p>
                <p><strong>Sexo:</strong> {$gender}</p>
                <p><strong>SBB:</strong> {$sbb}</p>
                <p><strong>RB:</strong> {$rb}</p>
                <p><strong>Registro:</strong> {$register}</p>
                <p><strong>Nível de sangue:</strong> {$bloodLevel}</p>
                <p><strong>% Sangue:</strong> {$bloodPercent}</p>
                <p><strong>Cruza:</strong> {$breeding}</p>
                <p><strong>Quantidade:</strong> {$quantity}</p>
                <p><strong>Data Nascimento:</strong> {$birthDate}</p>

                <!-- Seção Lote -->
                <h3 class='col-span-2 font-semibold text-gray-600 mt-4'>Dados do Lote</h3>
                <br>
                <p><strong>Número do Lote:</strong> {$lotNumber}</p>
                <p><strong>Lance Mínimo:</strong> {$minValue}</p>
                <!-- <p><strong>Valor Final:</strong> {$finalValue}</p> -->
            </div>
        </div>
        ");
                    }),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->contentGrid(['md' => 2, 'xl' => 4])
            ->columns([
                Grid::make()
                    ->columns(1)
                    ->schema([
                        ViewColumn::make('animal_card')
                            ->label('')
                            ->view('animal-card')
                            ->getStateUsing(fn($record) => [
                                'name' => $record->name,
                                'photo' => $record->photo ? asset('storage/' . $record->photo) : null,
                                'record' => $record,
                            ]),
                        // Estes campos ficam escondidos, só para poder ver na ViewAction
                        TextColumn::make('lot_number')->label('Número do Lote')->visible(false),
                        TextColumn::make('min_value')->label('Lance Mínimo')->money('BRL')->visible(false),
                        TextColumn::make('final_value')->label('Valor Final')->money('BRL')->visible(false),
                        TextColumn::make('status')->label('Status')->visible(false),
                        TextColumn::make('animal.name')->label('Animal')->visible(false),
                        ImageColumn::make('animal.photo')->label('Foto')->circular()->visible(false),
                    ])
            ])
            ->filters([
                SelectFilter::make('breed')
                    ->label(__('fields.breed'))
                    ->relationship('breed', 'name')
                    ->preload(),
                SelectFilter::make('coat')
                    ->label(__('fields.coat'))
                    ->relationship('coat', 'name')
                    ->preload(),
                SelectFilter::make('animalType')
                    ->label(__('fields.animal_type'))
                    ->relationship('animalType', 'name')
                    ->preload(),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make('adicionarLote')
                    ->label('Adicionar Lote ao Evento')
                    ->icon('heroicon-o-plus')
                    ->preloadRecordSelect()
                    ->form(function (Tables\Actions\AttachAction $action) {
                        return [
                            $action->getRecordSelect(), // Select do animal

                            TextInput::make('lot_number')
                                ->label('Número do Lote')
                                ->required(),

                            Money::make('min_value')
                                ->label('Lance Mínimo')
                                ->required(),

                            Money::make('final_value')
                                ->label('Valor Final'),

                            Money::make('increment_value')
                                ->label('Valor do Incremento'),

                            Money::make('target_value')
                                ->label('Lance Alvo'),

                            Select::make('status')
                                ->label('Status')
                                ->options([
                                    'disponivel' => 'Disponível',
                                    'vendido'    => 'Vendido',
                                    'reservado'  => 'Reservado',
                                ])
                                ->default('disponivel'),
                        ];
                    })
                    ->action(function (array $data) {
                        // Aqui pegamos o Event usando getOwnerRecord()
                        $event = $this->getOwnerRecord();

                        $event->animals()->attach(
                            $data['recordId'], // animal selecionado
                            [
                                'lot_number'      => $data['lot_number'],
                                'min_value'       => $data['min_value'],
                                'final_value'     => $data['final_value'],
                                'increment_value' => $data['increment_value'],
                                'target_value'    => $data['target_value'],
                                'status'          => $data['status'],
                            ]
                        );
                    }),

                ExportAction::make()
                    ->label('Download')
                    ->exports([
                        ExcelExport::make()
                            ->fromTable()
                            ->withFilename(date('d-m-Y') . ' - Animais')
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label(''),
                Tables\Actions\EditAction::make('editarLote')
                    ->label('')
                    ->modalHeading('Editar Lote')
                    // Preenche o form com os valores atuais do PIVOT
                    ->fillForm(fn($record) => [
                        'animal_id'       => $record->getKey(),              // para o Placeholder
                        'lot_number'      => $record->pivot->lot_number,
                        'min_value'       => $record->pivot->min_value,
                        'final_value'     => $record->pivot->final_value,
                        'increment_value' => $record->pivot->increment_value,
                        'target_value'    => $record->pivot->target_value,
                        'status'          => $record->pivot->status,
                    ])
                    ->form(function ($record) {
                        return [
                            // Mantém o card com foto/carimbo lendo do próprio form
                            Hidden::make('animal_id')->default($record->getKey()),

                            // --- OPCIONAL: card moderno com foto + carimbo (se você estiver usando) ---
                            Placeholder::make('animal_details')
                                ->label('')
                                ->columnSpanFull()
                                ->content(function ($get) {
                                    $animal = Animal::with(['breed', 'coat', 'animalType'])->find($get('animal_id'));
                                    if (!$animal) return 'Nenhum animal selecionado';

                                    $photoUrl   = $animal->photo ? asset('storage/' . $animal->photo) : 'https://via.placeholder.com/150';
                                    $status     = $get('status') ? $get('status') : '-';
                                    $bgColor    = '#6b7280';
                                    $textColor = '#fff';
                                    if ($status === 'disponivel') $bgColor = '#22c55e';
                                    if ($status === 'vendido')    $bgColor = '#ef4444';
                                    if ($status === 'reservado') {
                                        $bgColor = '#facc15';
                                        $textColor = '#000';
                                    }

                                    $breed       = $animal->breed ? $animal->breed->name : '-';
                                    $coat        = $animal->coat ? $animal->coat->name : '-';
                                    $type        = $animal->animalType ? $animal->animalType->name : '-';
                                    $gender      = $animal->gender == 'male' ? 'Macho' : 'Fêmea';
                                    $sbb         = $animal->sbb ? $animal->sbb : '-';
                                    $rb          = $animal->rb ? $animal->rb : '-';
                                    $register    = $animal->register ? $animal->register : '-';
                                    $bloodLevel  = $animal->blood_level ? $animal->blood_level : '-';
                                    $bloodPct    = $animal->blodd_percentual ? $animal->blodd_percentual : '-';
                                    $breeding    = $animal->breeding ? $animal->breeding : '-';
                                    $quantity    = $animal->quantity ? $animal->quantity : '-';
                                    $birthDate   = $animal->birth_date ? Carbon::parse($animal->birth_date)->format('d/m/Y') : '-';

                                    $lotNumber   = $get('lot_number') ? $get('lot_number') : '-';
                                    $minValue    = $get('min_value')
                                        ? 'R$ ' . number_format((float) $get('min_value'), 2, ',', '.')
                                        : '-';
                                    $finalValue  = $get('final_value')
                                        ? 'R$ ' . number_format((float) $get('final_value'), 2, ',', '.')
                                        : '-';

                                    return new HtmlString("
                        <div class='flex flex-col md:flex-row gap-6 p-6 bg-white shadow-lg rounded-xl'>
                            <div class='relative flex-shrink-0'>
                                <img src='{$photoUrl}' alt='{$animal->name}' class='w-36 h-36 object-cover rounded-lg border border-gray-200'>
                                <span style='position:absolute; top:8px; right:-16px; transform: rotate(-20deg);
                                             background-color:{$bgColor}; color:{$textColor};
                                             padding:4px 12px; font-weight:bold; font-size:0.875rem; border-radius:4px; z-index:50; box-shadow:0 2px 6px rgba(0,0,0,0.2);'>
                                    {$status}
                                </span>
                            </div>
                            <div class='flex-1 grid grid-cols-1 md:grid-cols-2 gap-2'>
                                <h2 class='col-span-2 font-bold text-2xl text-gray-800'>{$animal->name}</h2>

                                <h3 class='col-span-2 font-semibold text-gray-600 mt-2'>Dados do Animal</h3>
                                <p><strong>Raça:</strong> {$breed}</p>
                                <p><strong>Pelagem:</strong> {$coat}</p>
                                <p><strong>Tipo:</strong> {$type}</p>
                                <p><strong>Sexo:</strong> {$gender}</p>
                                <p><strong>SBB:</strong> {$sbb}</p>
                                <p><strong>RB:</strong> {$rb}</p>
                                <p><strong>Registro:</strong> {$register}</p>
                                <p><strong>Nível de sangue:</strong> {$bloodLevel}</p>
                                <p><strong>% Sangue:</strong> {$bloodPct}</p>
                                <p><strong>Cruza:</strong> {$breeding}</p>
                                <p><strong>Quantidade:</strong> {$quantity}</p>
                                <p><strong>Data Nascimento:</strong> {$birthDate}</p>

                                <h3 class='col-span-2 font-semibold text-gray-600 mt-4'>Dados do Lote</h3>
                                <p><strong>Número do Lote:</strong> {$lotNumber}</p>
                                <p><strong>Lance Mínimo:</strong> {$minValue}</p>
                                <p><strong>Valor Final:</strong> {$finalValue}</p>
                            </div>
                        </div>
                    ");
                                }),

                            // --- os MESMOS campos do seu AttachAction ---
                            TextInput::make('lot_number')
                                ->label('Número do Lote')
                                ->required(),

                            Money::make('min_value')
                                ->label('Lance Mínimo')
                                ->required()
                                ->afterStateUpdated(function ($state, callable $set) {
                                    if ($state !== null) {
                                        // força conversão para float e corrige escala
                                        $set('min_value', (float) $state * 10);
                                    }
                                })
                                ->dehydrateStateUsing(function ($state) {
                                    if ($state === null) {
                                        return null;
                                    }

                                    // Se já for número (usuário editou), retorna direto
                                    if (is_numeric($state)) {
                                        return (float) $state;
                                    }

                                    // Se for string formatada, normaliza
                                    return (float) str_replace(',', '.', str_replace('.', '', $state));
                                }),

                            Money::make('final_value')
                                ->label('Valor Final')
                                ->afterStateUpdated(function ($state, callable $set) {
                                    if ($state !== null) {
                                        // força conversão para float e corrige escala
                                        $set('final_value', (float) $state * 10);
                                    }
                                })
                                ->dehydrateStateUsing(function ($state) {
                                    if ($state === null) {
                                        return null;
                                    }

                                    // Se já for número (usuário editou), retorna direto
                                    if (is_numeric($state)) {
                                        return (float) $state;
                                    }

                                    // Se for string formatada, normaliza
                                    return (float) str_replace(',', '.', str_replace('.', '', $state));
                                }),

                            Money::make('increment_value')
                                ->label('Valor do Incremento')
                                ->afterStateUpdated(function ($state, callable $set) {
                                    if ($state !== null) {
                                        // força conversão para float e corrige escala
                                        $set('increment_value', (float) $state * 10);
                                    }
                                })
                                ->dehydrateStateUsing(function ($state) {
                                    if ($state === null) {
                                        return null;
                                    }

                                    // Se já for número (usuário editou), retorna direto
                                    if (is_numeric($state)) {
                                        return (float) $state;
                                    }

                                    // Se for string formatada, normaliza
                                    return (float) str_replace(',', '.', str_replace('.', '', $state));
                                }),

                            Money::make('target_value')
                                ->label('Lance Alvo')
                                ->afterStateUpdated(function ($state, callable $set) {
                                    if ($state !== null) {
                                        // força conversão para float e corrige escala
                                        $set('target_value', (float) $state * 10);
                                    }
                                })
                                ->dehydrateStateUsing(function ($state) {
                                    if ($state === null) {
                                        return null;
                                    }

                                    // Se já for número (usuário editou), retorna direto
                                    if (is_numeric($state)) {
                                        return (float) $state;
                                    }

                                    // Se for string formatada, normaliza
                                    return (float) str_replace(',', '.', str_replace('.', '', $state));
                                }),

                            Select::make('status')
                                ->label('Status')
                                ->options([
                                    'disponivel' => 'Disponível',
                                    'vendido'    => 'Vendido',
                                    'reservado'  => 'Reservado',
                                ])
                                ->default('disponivel'),
                        ];
                    })
                    // Salva no PIVOT
                    ->action(function ($record, array $data) {
                        $record->pivot->update([
                            'lot_number'      => $data['lot_number'],
                            'min_value'       => $data['min_value'] ?? null,
                            'final_value'     => $data['final_value'] ?? null,
                            'increment_value' => $data['increment_value'] ?? null,
                            'target_value'    => $data['target_value'] ?? null,
                            'status'          => $data['status'],
                        ]);
                    }),
                Tables\Actions\Action::make('removerLote')
                    ->label('')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Remover Lote do Evento')
                    // ->modalContent(new HtmlString('Tem certeza que deseja remover este lote do evento? <strong>Essa ação não apagará o animal</strong>, apenas desvinculará do evento.'))
                    ->action(function ($record) {
                        $this->getOwnerRecord()->animals()->detach($record->getKey());
                    }),



            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()->label('Download'),
                ]),
            ]);
    }
}
