<?php

namespace App\Filament\Resources\ClientResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Auth;

class ClientNoteRelationManager extends RelationManager
{
    protected static string $relationship = 'notes';

    protected static ?string $title = 'Anotações';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Textarea::make('content')
                ->label('Anotação')
                ->required()
                ->maxLength(500)
                ->rows(4),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('content')
                    ->label('Conteúdo')
                    ->limit(60)
                    ->wrap(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Adicionado por'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Data')
                    ->dateTime('d/m/Y H:i'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['user_id'] = Auth::id();
                        return $data;
                    })
                    ->label('Nova Anotação'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn($record) => $record->user_id === Auth::id()),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn($record) => $record->user_id === Auth::id()),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
