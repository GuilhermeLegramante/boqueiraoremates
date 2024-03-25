<?php

namespace App\Filament\Reports;

use App\Filament\Reports\Headers\DefaultHeaderReport;
use App\Models\Client;
use App\Models\User;
use EightyNine\Reports\Report;
use EightyNine\Reports\Components\Body;
use EightyNine\Reports\Components\Body\CustomTable;
use EightyNine\Reports\Components\Footer;
use EightyNine\Reports\Components\Header;
use EightyNine\Reports\Components\Text;
use Filament\Forms\Form;
use EightyNine\Reports\Components\Image;
use EightyNine\Reports\Components\VerticalSpace;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class ClientDetails extends Report
{
    public ?string $heading = "Ficha Cadastral";

    public ?string $subHeading = "Dados do cliente";

    public function header(Header $header): Header
    {
        return $header
            ->schema(DefaultHeaderReport::content(title: 'Ficha Cadastral'));
    }


    public function body(Body $body): Body
    {
        return $body
            ->schema([
                Body\Layout\BodyRow::make()
                    ->schema([
                        CustomTable::make()
                            ->data(
                                fn (?array $filters) => $this->registrationSummary($filters)
                            ),
                        VerticalSpace::make(),
                    ]),
            ]);
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
                                Text::make("www.boqueiraoremates.com.br - Santiago/RS")
                                    ->subtitle(),
                            ]),
                        Footer\Layout\FooterColumn::make()
                            ->schema([
                                Text::make("Emitido em: " . now()->format('d/m/Y H:i:s')),
                            ])
                            ->alignRight(),
                    ]),
            ]);
    }


    public function filterForm(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('client')
                    ->label(__('fields.client'))
                    ->placeholder('client')
                    ->options(Client::all()->pluck('name', 'id')->toArray()),
            ]);
    }
}
