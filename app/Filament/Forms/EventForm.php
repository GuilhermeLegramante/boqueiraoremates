<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ViewField;

class EventForm
{
    public static function form($operation = ''): array
    {
        return [
            ViewField::make('banner_preview')
                ->label('Banner do Evento')
                ->columnSpanFull()
                ->visible($operation == 'view')
                ->view('banner-preview'),
            FileUpload::make('banner')
                ->label('Banner')
                ->image()
                ->previewable()
                ->openable()
                ->downloadable()
                ->visible($operation != 'view')
                ->directory('events/banners')
                ->visibility('public')
                ->columnSpanFull()
                ->maxSize(4096),
            FileUpload::make('banner_min')
                ->label('Selo')
                ->image()
                ->previewable()
                ->openable()
                ->downloadable()
                ->visible($operation != 'view')
                ->directory('events/banners')
                ->visibility('public')
                ->columnSpanFull()
                ->maxSize(4096),
            ViewField::make('event_info')
                ->label('Evento')
                ->columnSpanFull()
                ->visible($operation == 'view')
                ->view('event-info'),
            TextInput::make('name')
                ->label(__('fields.name'))
                ->columnSpanFull()
                ->required()
                ->visible($operation != 'view')
                ->unique(ignoreRecord: true)
                ->maxLength(255),
            DateTimePicker::make('start_date')
                ->label('Data do Evento')
                ->visible($operation != 'view')
                ->required()
                ->columnSpan(1),

            DateTimePicker::make('finish_date')
                ->label('Fim do Evento')
                ->visible($operation != 'view')
                ->required()
                ->columnSpan(1),

            // Coluna 2: Pré-lance
            DateTimePicker::make('pre_start_date')
                ->label('Início Pré-lance')
                ->visible($operation != 'view')
                ->nullable()
                ->helperText('Data e hora de início do pré-lance online')
                ->columnSpan(1),

            DateTimePicker::make('pre_finish_date')
                ->label('Fim Pré-lance')
                ->visible($operation != 'view')
                ->nullable()
                ->helperText('Data e hora do término do pré-lance online')
                ->columnSpan(1),
            TextInput::make('multiplier')
                ->label(__('fields.multiplier'))
                ->visible($operation != 'view')
                ->numeric(),
            // Select::make('animals')
            //     ->label('Animais')
            //     ->multiple()
            //     ->visible($operation != 'view')
            //     ->relationship('animals', 'name')
            //     ->preload()
            //     ->columnSpanFull()
            //     ->searchable(),
            Textarea::make('note')
                ->label(__('fields.note'))
                ->visible($operation != 'view')
                ->columnSpanFull()
                ->rows(4)
                ->maxLength(65535), // limite do campo text

            FileUpload::make('regulation')
                ->label('Regulamento Completo (PDF)')
                ->directory('events/regulations')
                ->visibility('public')
                ->acceptedFileTypes(['application/pdf'])
                ->downloadable()
                ->openable()
                ->previewable()
                ->columnSpanFull()
                ->visible($operation != 'view')
                ->nullable(),

            FileUpload::make('regulation_image_path')
                ->label('Condições de Pgto')
                ->image()
                ->directory('events/regulations')
                ->imageEditor() // permite cortar, ajustar, etc.
                ->imagePreviewHeight('150')
                ->openable()
                ->downloadable()
                ->maxSize(2048) // 2 MB
                ->helperText('Envie uma imagem ilustrando a parte mais importante do regulamento do evento.'),

            FileUpload::make('benefits_image_path')
                ->label('Benefícios do Pré-lance')
                ->image()
                ->directory('events/benefits')
                ->imageEditor()
                ->imagePreviewHeight('150')
                ->openable()
                ->downloadable()
                ->maxSize(2048)
                ->helperText('Envie uma imagem ilustrando os benefícios do evento.'),

            Toggle::make('published')
                ->label('Publicado')
                ->default(false),

            Toggle::make('closed')
                ->label('Encerrado')
                ->default(false),

            Toggle::make('show_lots')
                ->label('Mostrar lotes')
                ->default(false),

            Toggle::make('is_permanent')
                ->label('Venda Permanente')
                ->default(false),
        ];
    }
}
