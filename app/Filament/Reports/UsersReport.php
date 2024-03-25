<?php

namespace App\Filament\Reports;

use App\Models\User;
use EightyNine\Reports\Report;
use EightyNine\Reports\Components\Body;
use EightyNine\Reports\Components\Body\CustomTable;
use EightyNine\Reports\Components\Body\TextColumn;
use EightyNine\Reports\Components\Footer;
use EightyNine\Reports\Components\Header;
use EightyNine\Reports\Components\Text;
use EightyNine\Reports\Components\Image;
use EightyNine\Reports\Components\VerticalSpace;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Illuminate\Support\Facades\DB;

class UsersReport extends Report
{
    public ?string $heading = "Relatórios";

    public ?string $subHeading = "A great report";

    protected bool $hasColumns = true;

    public function mount(): void
    {
        dd('a');
    }

    public function header(Header $header): Header
    {
        return $header
            ->schema([
                Header\Layout\HeaderRow::make()
                    ->schema([
                        Header\Layout\HeaderColumn::make()
                            ->schema([
                                Text::make("User registration report")
                                    ->title()
                                    ->primary(),
                                Text::make("A user registration report")
                                    ->subtitle(),
                            ]),
                        Header\Layout\HeaderColumn::make()
                            ->schema([
                                Image::make('https://hardsoft.s3.sa-east-1.amazonaws.com/assets_marcas/logo-no-bg.png'),
                            ])
                            ->alignRight(),
                    ]),
            ]);
    }


    public function body(Body $body): Body
    {
        return $body
            ->schema([
                Body\Layout\BodyColumn::make()
                    ->schema([
                        CustomTable::make()
                            ->data(
                                fn (?array $filters) => $this->registrationSummary($filters)
                            ),
                        // VerticalSpace::make(),
                        // Body\Table::make()
                        //     ->data(
                        //         fn (?array $filters) => $this->verificationSummary($filters)
                        //     ),
                    ]),
            ]);
    }

    private function verificationSummary($filters)
    {
        return User::where('name', 'like', '%GUILHERME%')->get();
    }

    private function registrationSummary($filters)
    {
        return User::where('name', 'like', '%GUILHERME%')->get();
    }

    public function footer(Footer $footer): Footer
    {
        return $footer
            ->schema([
                Footer\Layout\FooterRow::make()
                    ->schema([
                        Footer\Layout\FooterColumn::make()
                            ->schema([
                                Text::make("Footer title")
                                    ->title()
                                    ->primary(),
                                Text::make("Footer subtitle")
                                    ->subtitle(),
                            ]),
                        Footer\Layout\FooterColumn::make()
                            ->schema([
                                Text::make("Generated on: " . now()->format('Y-m-d H:i:s')),
                            ])
                            ->alignRight(),
                    ]),
            ]);
    }

    public function filterForm(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('search')
                    ->placeholder('Search')
                    ->autofocus(),
                Select::make('status')
                    ->placeholder('Status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ]),
            ]);
    }
}
