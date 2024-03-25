<?php

namespace App\Filament\Reports\Headers;

use EightyNine\Reports\Components\Header;
use EightyNine\Reports\Components\Header\Layout\HeaderColumn;
use EightyNine\Reports\Components\Header\Layout\HeaderRow;
use EightyNine\Reports\Components\Text;
use EightyNine\Reports\Components\Image;

class DefaultHeaderReport
{
    public static function content(string $title = ''): array
    {
        return [
            HeaderRow::make()
                ->schema([
                    HeaderColumn::make()
                        ->schema([
                            Image::make('https://boqueiraoremates.com/public/vendor/adminlte/dist/img/b.png'),
                        ])
                        ->alignLeft(),
                    HeaderColumn::make()
                        ->schema([
                            Text::make($title)
                                ->title()
                                ->primary(),
                            Text::make("Boqueirão Remates e Negócios Rurais")
                                ->subtitle(),
                        ]),
                    HeaderColumn::make()
                        ->schema([
                            Text::make("contato@boqueiraoremates.com")
                                ->subtitle(),
                            Text::make("www.boqueiraoremates.com.br")
                                ->subtitle(),
                        ]),

                ]),
        ];
    }
}
