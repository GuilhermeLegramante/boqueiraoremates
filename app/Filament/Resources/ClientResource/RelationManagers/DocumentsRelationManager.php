<?php

namespace App\Filament\Resources\ClientResource\RelationManagers;

use App\Filament\Forms\DocumentForm;
use App\Tables\Columns\FileLink;
use App\Tables\Columns\UrlLink;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'documents';

    protected static ?string $title = 'Documentos';

    protected static ?string $label = 'Documento';

    protected static ?string $pluralLabel = 'Documentos';

    protected static bool $isLazy = false;

    public function form(Form $form): Form
    {
        return $form
            ->schema(DocumentForm::form());
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('documentType.name')
            ->columns([
                TextColumn::make('documentType.name')
                    ->searchable()
                    ->label(__('fields.document_type')),

                TextColumn::make('created_at')
                    ->label('Dta Inclusão')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                FileLink::make('path')
                    ->alignment(Alignment::Left)
                    ->label(__('fields.file')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
