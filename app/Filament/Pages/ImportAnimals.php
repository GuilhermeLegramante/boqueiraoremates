<?php

namespace App\Filament\Pages;

use App\Models\Animal;
use App\Models\AnimalType;
use App\Models\Breed;
use App\Models\Coat;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Exception;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Storage;

class ImportAnimals extends Page
{
    use HasPageShield;
    
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.import-animals';

    protected static ?int $navigationSort = 3;

    protected static ?string $title = 'Importar Animais';

    protected static ?string $slug = 'importar-animais';

    protected ?string $heading = 'Importar Dados';

    protected ?string $subheading = 'Importação de dados a partir de planilha .csv';

    public ?array $data = [];

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Importar dados da planilha')
                    ->columns([
                        'sm' => 2,
                        'xl' => 2,
                        '2xl' => 2,
                    ])
                    ->description('Envie um arquivo .csv com os dados nessa ordem: Nome do Animal, Raça, Pelagem, Tipo do Animal, Mãe, Pai, Sexo, SBB, RP, Data de Nascimento')
                    ->schema([
                        FileUpload::make('file')
                            ->label('Arquivo')
                            ->columnSpanFull()
                            ->previewable()
                            ->openable()
                            ->downloadable()
                            ->moveFiles()
                    ]),
            ])->statePath('data');
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        try {
            $file = file_get_contents('https://boqueiraoremates.com/v2/public/storage/' . $data['file']);

            $animals = explode(PHP_EOL, $file);

            /**
             * Nome do Animal, Raça, Pelagem, Tipo do Animal, Mãe, Pai, Sexo, SBB, RP, Data de Nascimento
             */
            for ($i = 1; $i < count($animals); $i++) { // i=1 para eliminar o cabeçalho
                $data = explode(';', $animals[$i]);

                if ($data[0] != '') {
                    $name = trim($data[0]);
                    $breedName = trim($data[1]);
                    $coatName = trim($data[2]);
                    $animalTypeName = trim($data[3]);
                    $mother = trim($data[4]);
                    $father = trim($data[5]);
                    $gender = trim($data[6]);
                    $sbb = trim($data[7]);
                    $rb = trim($data[8]);

                    $exploitBirthDate = explode('/', trim($data[9]));
                    $day = $exploitBirthDate[0];
                    $month = str_pad($exploitBirthDate[1], 2, '0', STR_PAD_LEFT);
                    $year = $exploitBirthDate[2];

                    $birthDate = $year . '-' . $month . '-' . $day;

                    // Cria ou recupera a raça
                    $breed = Breed::firstOrCreate([
                        'name' => $breedName
                    ]);

                    // Cria ou recupera a pelagem
                    $coat = Coat::firstOrCreate([
                        'name' => $coatName
                    ]);

                    // Cria ou recupera o tipo de animal
                    $animalType = AnimalType::firstOrCreate([
                        'name' => $animalTypeName
                    ]);

                    Animal::create([
                        'name' => strtoupper($name),
                        'breed_id' => $breed->id,
                        'animal_type_id' => $animalType->id,
                        'coat_id' => $coat->id,
                        'gender' => $gender == 'M' ? 'male' : 'female',
                        'sbb' => $sbb,
                        'rb' => $rb,
                        'mother' => $mother,
                        'father' => $father,
                        'birth_date' => $birthDate,
                    ]);
                }
            }

            Notification::make()
                ->title('Sucesso!')
                ->body('Dados importados')
                ->success()
                ->send();
        } catch (Exception $e) {
            Notification::make()
                ->title('Erro ao importar os dados!')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
