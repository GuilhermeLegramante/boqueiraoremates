<?php

namespace App\Filament\Resources;

use App\Filament\Forms\EventForm;
use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\RelationManagers;
use App\Filament\Resources\EventResource\RelationManagers\AnimalsRelationManager;
use App\Filament\Resources\OrderResource\Pages\CreateEvent;
use App\Filament\Resources\OrderResource\Pages\EditEvent;
use App\Filament\Resources\OrderResource\Pages\ListEvents;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = 'evento';

    protected static ?string $pluralModelLabel = 'eventos';

    // protected static ?string $navigationGroup = 'Parâmetros';

    protected static ?string $slug = 'eventos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Dados do Evento')
                    ->description(
                        fn(string $operation): string =>
                        $operation === 'create' || $operation === 'edit'
                            ? 'Informe os campos solicitados'
                            : ''
                    )
                    ->schema(function (string $operation) {
                        return EventForm::form($operation);
                    })->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->contentGrid(['md' => 2, 'xl' => 3])
            ->columns([
                Grid::make()
                    ->columns(columns: 1)
                    ->schema([
                        ImageColumn::make('banner')
                            ->label('Banner')
                            ->height(90)
                            ->columnSpanFull()
                            ->getStateUsing(fn($record) => $record->banner ? asset('storage/' . $record->banner) : null),

                        ViewColumn::make('event_info')
                            ->label('Evento')
                            ->view('event-info-to-list'),
                    ])

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Detalhes'),
                Action::make('viewRegulation')
                    ->label('Regulamento')
                    ->icon('heroicon-o-document-text')
                    ->url(fn($record) => $record->regulation ? asset('storage/' . $record->regulation) : null)
                    ->openUrlInNewTab()
                    ->visible(fn($record) => $record->regulation !== null),
                Tables\Actions\EditAction::make()
                    ->mutateRecordDataUsing(function (array $data): array {
                        $data['name'] = Str::upper($data['name']);

                        return $data;
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            AnimalsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEvents::route('/'),
            'create' => CreateEvent::route('/criar'),
            'edit' => EditEvent::route('/{record}/editar'),
            'view' => Pages\ViewEvent::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
