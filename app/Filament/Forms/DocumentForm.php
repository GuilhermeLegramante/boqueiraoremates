<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class DocumentForm
{
    public static function form(): array
    {
        return [
            Select::make('document_type_id')
                ->label(__('fields.document_type'))
                ->relationship('documentType', 'name')
                ->createOptionForm(DocumentTypeForm::form())
                ->required(),
            FileUpload::make('path')
                ->label(__('fields.file')),
        ];
    }
}
