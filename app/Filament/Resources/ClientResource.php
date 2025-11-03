<?php

namespace App\Filament\Resources;

use App\Filament\Forms\ClientForm;
use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers;
use App\Models\Client;
use Filament\Tables\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = 'cliente';

    protected static ?string $pluralModelLabel = 'clientes';

    protected static ?string $slug = 'clientes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(ClientForm::form());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->label(__('fields.code'))
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('name')
                    ->label('Nome')
                    ->getStateUsing(function ($record) {
                        return $record->name ?? '—';
                    })
                    ->searchable()
                    ->sortable()
                    ->url(function ($record) {
                        return route('client-details-pdf', $record->id);
                    })
                    ->openUrlInNewTab()
                    ->color('info')
                    ->icon('heroicon-o-document-text')
                    ->sortable(),
                TextColumn::make('cpf_cnpj')
                    ->label(__('fields.cpf_cnpj'))
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                // TextColumn::make('name')
                //     ->label(__('fields.name'))
                //     ->searchable(),
                TextColumn::make('email')
                    ->label(__('fields.email'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->icon('heroicon-m-envelope'),
                TextColumn::make('situation')
                    ->label(__('fields.situation'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->alignment(Alignment::Center)
                    ->badge()
                    ->formatStateUsing(
                        fn(string $state): string => (($state == 'able' ? 'HABILITADO' : ($state == 'disabled' ? 'INABILITADO' : 'INATIVO')))
                    )
                    ->color(fn(string $state): string => match ($state) {
                        'able' => 'success',
                        'disabled' => 'warning',
                        'inactive' => 'danger',
                    }),
                TextColumn::make('register_origin')
                    ->label(__('fields.register_origin'))
                    ->alignment(Alignment::Center)
                    ->badge()
                    ->formatStateUsing(
                        fn(string $state): string => (($state == 'marketing' ? 'MARKETING' : ($state == 'local' ? 'RECINTO' : 'SITE')))
                    )
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->color(fn(string $state): string => match ($state) {
                        'marketing' => 'info',
                        'local' => 'gray',
                        'site' => 'primary',
                    }),
                TextColumn::make('profile')
                    ->label(__('fields.profile'))
                    ->alignment(Alignment::Center)
                    ->badge()
                    ->formatStateUsing(
                        fn(string $state): string => (($state == 'purchase' ? 'COMPRA' : ($state == 'sale' ? 'VENDA' : 'AMBOS')))
                    )
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->color(fn(string $state): string => match ($state) {
                        'purchase' => 'primary',
                        'sale' => 'gray',
                        'both' => 'success',
                    }),
                TextColumn::make('whatsapp')
                    ->label('Whatsapp')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('cel_phone')
                    ->label('Celular')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('business_phone')
                    ->label('Tel. Comercial')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('home_phone')
                    ->label('Tel. Res.')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label(__('fields.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('fields.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('situation')
                    ->label(__('fields.situation'))
                    ->options([
                        'able' => 'Habilitado',
                        'disabled' => 'Inabilitado',
                        'inactive' => 'Inativo'
                    ]),
                SelectFilter::make('register_origin')
                    ->label(__('fields.register_origin'))
                    ->options([
                        'marketing' => 'Divulgação',
                        'local' => 'Recinto',
                        'site' => 'Site'
                    ]),
                SelectFilter::make('profile')
                    ->label(__('fields.profile'))
                    ->options([
                        'purchase' => 'Compra',
                        'sale' => 'Venda',
                        'both' => 'Ambos'
                    ]),
            ], layout: FiltersLayout::Dropdown)
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Action::make('report')
                        ->label('Ficha')
                        ->icon('heroicon-o-document-text')
                        ->color('info')
                        ->url(fn(Client $record): string => route('client-details-pdf', $record->id))
                        ->openUrlInNewTab()
                ]),
            ], position: ActionsPosition::BeforeColumns)
            ->headerActions([
                ExportAction::make()
                    ->label('Download')
                    ->exports([
                        ExcelExport::make()
                            ->fromTable()
                            ->withFilename(date('d-m-Y') . ' - Clientes')
                    ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make()->label('Download'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ClientNoteRelationManager::class,
            RelationManagers\DocumentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/criar'),
            'edit' => Pages\EditClient::route('/{record}/editar'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\CreateClient::class,
            Pages\EditClient::class,
            Pages\ListClients::class,
        ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
