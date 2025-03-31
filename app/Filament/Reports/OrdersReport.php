<?php

namespace App\Filament\Reports;

use App\Filament\Reports\Headers\DefaultHeaderReport;
use App\Models\Order;
use App\Models\User;
use EightyNine\Reports\Report;
use EightyNine\Reports\Components\Body;
use EightyNine\Reports\Components\Footer;
use EightyNine\Reports\Components\Header;
use Filament\Forms\Form;
use EightyNine\Reports\Components\Text;
use EightyNine\Reports\Components\VerticalSpace;
use Filament\Forms\Components\DatePicker;

class OrdersReport extends Report
{
    public ?string $heading = "Relatório";

    public ?string $subHeading = "Resumo Faturas de Venda / OS";

    public $heads = ['id', 'numero'];


    public function header(Header $header): Header
    {
        return $header
            ->schema(DefaultHeaderReport::content(title: 'Faturas de Venda / OS'));
    }

    public function getGroup(): ?string
    {
        return 'Relatórios';
    }

    public function body(Body $body): Body
    {
        return $body
            ->schema([
                Body\Layout\BodyColumn::make()
                    ->schema([
                        Body\Table::make()
                            // ->columns(['id'])
                            ->data(
                                fn (?array $filters) => $this->registrationSummary($filters)
                            ),
                        VerticalSpace::make(),
                        Body\Layout\BodyColumn::make()
                            ->schema([
                                Text::make("Some title"),
                                Text::make("Some subtitle"),
                                Text::make("Some subtitle"),
                            ])->alignRight(),
                    ]),
            ]);
    }

    private function registrationSummary($filters)
    {
        if (count($filters) > 0) {
            return Order::whereDate('base_date', '>=', $filters['initial_base_date'])
                ->whereDate('base_date', '<=', $filters['final_base_date'])
                ->select('number', 'created_at')
                ->get();
        } else {
            return Order::get(['id', 'number']);
        }
    }

    private function verificationSummary($filters)
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
                DatePicker::make('initial_base_date')->label('Data de Negociação (Inicial)'),
                DatePicker::make('final_base_date')->label('Data de Negociação (Final)'),
            ]);
    }
}
