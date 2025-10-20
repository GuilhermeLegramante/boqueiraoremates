<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LegadoLanceResource\Pages;
use App\Models\LegadoLance;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\DateFilter;
use Filament\Tables\Filters\SelectFilter;

class LegadoLanceResource extends Resource
{
    protected static ?string $model = LegadoLance::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack'; // ícone da sidebar
    protected static ?string $navigationLabel = 'Lances (Site antigo)'; // nome na sidebar
    protected static ?string $pluralModelLabel = 'Lances (Site antigo)';
    protected static ?string $modelLabel = 'Lances (Site antigo)';

    protected static ?string $navigationGroup = 'Dados Site Antigo';

    protected static ?string $slug = 'lances-site-antigo';


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('idlance')->label('ID'),
                TextColumn::make('cliente.nome')->label('Cliente'),
                TextColumn::make('leilao.nomeleilao')->label('Leilão'),
                TextColumn::make('animal.nome')->label('Animal'),
                TextColumn::make('datalance')
                    ->label('Data')
                    ->date('d/m/Y'), // formato brasileiro
                TextColumn::make('horalance')
                    ->label('Hora')
                    ->formatStateUsing(fn($state) => date('H:i:s', strtotime($state))), // hora formatada
                TextColumn::make('lance')->label('Valor')->money('BRL', true),
                TextColumn::make('situacao')->label('Situação'),
            ])
            ->filters([
                // filtro de intervalo de datas
                Filter::make('datalance')
                    ->form([
                        DatePicker::make('created_from')->label('Data do Lance (Inicial)'),
                        DatePicker::make('created_until')->label('Data do Lance (Final)'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'] ?? null,
                                fn(Builder $query, $date): Builder => $query->whereDate('datalance', '>=', $date),
                            )
                            ->when(
                                $data['created_until'] ?? null,
                                fn(Builder $query, $date): Builder => $query->whereDate('datalance', '<=', $date),
                            );
                    }),
                SelectFilter::make('situacao')
                    ->label('Situação')
                    ->options([
                        'aprovado' => 'Aprovado',
                        'reprovado' => 'Reprovado',
                    ]),
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(), // apenas visualização
            ])
            ->deferFilters()
            ->filtersApplyAction(
                fn(Action $action) => $action
                    ->link()
                    ->label('Aplicar Filtro(s)'),
            )
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLegadoLances::route('/'),
        ];
    }
}
