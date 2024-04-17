<?php

namespace App\Filament\Resources;

use App\Filament\Forms\DocumentForm;
use App\Filament\Resources\DocumentResource\Pages;
use App\Filament\Resources\DocumentResource\RelationManagers;
use App\Models\Document;
use App\Tables\Columns\FileLink;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document';

    protected static ?string $recordTitleAttribute = 'documentType.name';

    protected static ?string $modelLabel = 'documento';

    protected static ?string $pluralModelLabel = 'documentos';

    protected static ?string $navigationGroup = 'Parâmetros';

    protected static ?string $slug = 'documentos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(DocumentForm::form());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('documentType.name')
                    ->label(__('fields.document_type'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('client.name')
                    ->label(__('fields.client'))
                    ->searchable()
                    ->sortable(),
                FileLink::make('path')
                    ->label(__('fields.file'))
                    ->alignment(Alignment::Center),
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
                SelectFilter::make('documentType')
                    ->label(__('fields.document_type'))
                    // ->multiple()
                    ->preload()
                    ->relationship('documentType', 'name')
            ])
            ->groups([
                Group::make('documentType.name')
                    ->label(__('fields.document_type'))
                    ->collapsible(),
                Group::make('client.name')
                    ->label(__('fields.client'))
                    ->collapsible(),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ], position: ActionsPosition::BeforeColumns)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageDocuments::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
