<?php

namespace App\Filament\Forms;

use App\Models\Event;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Section;

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

            FileUpload::make('banner_contract')
                ->label('Logo p/ Contrato')
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

            TextInput::make('city')
                ->label('Cidade e UF')
                ->hint('Ex: Santiago - RS')
                ->required()
                ->visible($operation != 'view')
                ->maxLength(255),

            // Textarea::make('note')
            //     ->label(__('fields.note'))
            //     ->visible($operation != 'view')
            //     ->columnSpanFull()
            //     ->rows(4)
            //     ->maxLength(65535), // limite do campo text

            RichEditor::make('note')
                ->label(__('fields.note'))
                ->required()
                ->toolbarButtons([
                    'bold',
                    'italic',
                    'underline',
                    'strike',
                    'textColor',
                    'bulletList',
                    'orderedList',
                    'undo',
                    'redo',
                ])
                ->columnSpanFull(),

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

            TextInput::make('auctioneer')
                ->label('Leiloeiro')
                ->columnSpanFull()
                ->visible($operation != 'view')
                ->maxLength(255),

            TextInput::make('witness_1_name')
                ->label('Nome da Testemunha 1')
                ->visible($operation != 'view')
                ->maxLength(255),

            TextInput::make('witness_2_name')
                ->label('Nome da Testemunha 2')
                ->visible($operation != 'view')
                ->maxLength(255),

            // 🔹 SEÇÃO 1: CLÁUSULAS DO CONTRATO
            Section::make('Cláusulas do Contrato')
                ->description('Gerencie as cláusulas e parágrafos do contrato de compra e venda específicos para este evento.')
                ->icon('heroicon-o-document-text')
                ->collapsible()
                ->schema([
                    Repeater::make('clauses')
                        ->relationship('clauses')
                        ->hiddenLabel() // Oculta o rótulo repetido já que a Section tem título
                        ->schema([
                            // TextInput::make('title')
                            //     ->label('Título / Identificador')
                            //     ->placeholder('Ex: CLÁUSULA PRIMEIRA')
                            //     ->columnSpanFull(),

                            RichEditor::make('content')
                                ->label('Texto da Cláusula')
                                ->required()
                                ->toolbarButtons([
                                    'bold',
                                    'italic',
                                    'underline',
                                    'strike',
                                    'textColor',
                                    'bulletList',
                                    'orderedList',
                                    'undo',
                                    'redo',
                                ])
                                ->columnSpanFull(),
                        ])
                        ->hintAction(
                            Action::make('importClauses')
                                ->label('Importar de outro Evento')
                                ->icon('heroicon-o-document-duplicate')
                                ->color('warning')
                                ->modalHeading('Importar Cláusulas de Contrato')
                                ->modalDescription('Selecione o evento de onde deseja copiar as cláusulas. As cláusulas atuais deste formulário serão substituídas.')
                                ->modalSubmitActionLabel('Importar')
                                ->form([
                                    Select::make('source_event_id')
                                        ->label('Evento de Origem')
                                        ->options(function ($record) {
                                            return Event::query()
                                                ->when($record, fn($q) => $q->where('id', '!=', $record->id))
                                                ->orderBy('id', 'desc')
                                                ->pluck('name', 'id');
                                        })
                                        ->searchable()
                                        ->required(),
                                ])
                                ->action(function (array $data, Repeater $component): void {
                                    $sourceEvent = Event::with('clauses')->find($data['source_event_id']);

                                    if (! $sourceEvent || $sourceEvent->clauses->isEmpty()) {
                                        return;
                                    }

                                    $importedData = $sourceEvent->clauses->map(fn($clause) => [
                                        'title' => $clause->title,
                                        'content' => $clause->content,
                                        'order' => $clause->order,
                                    ])->toArray();

                                    $component->state($importedData);
                                })
                        )
                        ->orderColumn('order')
                        ->reorderable()
                        ->collapsible()
                        ->cloneable()
                        ->addActionLabel('Adicionar Cláusula de Contrato')
                        ->columnSpanFull()
                        ->visible($operation != 'view'),
                ])
                ->columnSpanFull(),

            // 🔹 SEÇÃO 2: CLÁUSULAS DO REGULAMENTO
            Section::make('Regulamento do Evento')
                ->description('Defina as regras gerais do leilão/remate que compõem o documento de regulamento.')
                ->icon('heroicon-o-clipboard-document-list')
                ->collapsible()
                ->schema([
                    Repeater::make('regulationClauses')
                        ->relationship('regulationClauses')
                        ->hiddenLabel() // Oculta o rótulo repetido
                        ->schema([
                            // TextInput::make('title')
                            //     ->label('Título / Identificador')
                            //     ->placeholder('Ex: ARTIGO 1º')
                            //     ->columnSpanFull(),

                            RichEditor::make('content')
                                ->label('Texto do Regulamento')
                                ->required()
                                ->toolbarButtons([
                                    'bold',
                                    'italic',
                                    'underline',
                                    'strike',
                                    'textColor',
                                    'bulletList',
                                    'orderedList',
                                    'undo',
                                    'redo',
                                ])
                                ->columnSpanFull(),
                        ])
                        ->hintAction(
                            Action::make('importRegulationClauses')
                                ->label('Importar de outro Evento')
                                ->icon('heroicon-o-document-duplicate')
                                ->color('warning')
                                ->modalHeading('Importar Regulamento de outro Evento')
                                ->modalDescription('Selecione o evento de onde deseja copiar as regras do regulamento.')
                                ->modalSubmitActionLabel('Importar')
                                ->form([
                                    Select::make('source_event_id')
                                        ->label('Evento de Origem')
                                        ->options(function ($record) {
                                            return Event::query()
                                                ->when($record, fn($q) => $q->where('id', '!=', $record->id))
                                                ->orderBy('id', 'desc')
                                                ->pluck('name', 'id');
                                        })
                                        ->searchable()
                                        ->required(),
                                ])
                                ->action(function (array $data, Repeater $component): void {
                                    $sourceEvent = Event::with('regulationClauses')->find($data['source_event_id']);

                                    if (! $sourceEvent || $sourceEvent->regulationClauses->isEmpty()) {
                                        return;
                                    }

                                    $importedData = $sourceEvent->regulationClauses->map(fn($clause) => [
                                        'title' => $clause->title,
                                        'content' => $clause->content,
                                        'order' => $clause->order,
                                    ])->toArray();

                                    $component->state($importedData);
                                })
                        )
                        ->orderColumn('order')
                        ->reorderable()
                        ->collapsible()
                        ->cloneable()
                        ->addActionLabel('Adicionar Regra do Regulamento')
                        ->columnSpanFull()
                        ->visible($operation != 'view'),
                ])
                ->columnSpanFull(),

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

            Toggle::make('can_offer')
                ->label('Permitir Ofertas')
                ->default(true),
        ];
    }
}
