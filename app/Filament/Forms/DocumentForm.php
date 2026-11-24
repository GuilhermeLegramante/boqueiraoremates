<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
class DocumentForm
{
    public static function form(): array
    {
        return [
            Select::make('document_type_id')
                ->label(__('fields.document_type'))
                ->relationship(
                    name: 'documentType',
                    titleAttribute: 'name',
                    modifyQueryUsing: fn(Builder $query) => $query
                        ->orderByRaw('CASE WHEN `order` = 0 THEN 1 ELSE 0 END')
                        ->orderBy('order')
                        ->orderBy('id'),
                )
                ->createOptionForm(DocumentTypeForm::form())
                ->required(),

            FileUpload::make('path')
                ->required()
                ->label(__('fields.file')),
        ];
    }
}
