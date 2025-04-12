<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use App\Filament\Tables\ParcelsTable;
use App\Models\PaymentMethod;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class BuyerParcelsRelationManager extends RelationManager
{
    protected static string $relationship = 'buyerParcels';

    protected static ?string $title = 'Parcelas (Faturamento pelo Comprador)';

    protected static ?string $label = 'Parcela';

    protected static ?string $pluralLabel = 'Parcelas (Faturamento pelo Comprador)';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('number')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('number')
            ->columns(ParcelsTable::table())
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    $this->customBulkAction(),
                ]),
            ]);
    }

    protected function customBulkAction(): BulkAction
    {
        return BulkAction::make('batchPayment')
            ->label('Pagamento em Lote')
            ->form([
                Forms\Components\Select::make('payment_method_id')
                    ->label('Método de Pagamento')
                    ->options(PaymentMethod::all()->pluck('name', 'id'))
                    ->default(7), // COBRANÇA VIA CABANHA
                    // ->required(),
                Forms\Components\Toggle::make('paid')
                    ->label('Parcela Paga')
                    ->inline(false)
                    ->default(true),
                Forms\Components\Textarea::make('note')
                    ->label('Observação')
                    ->maxLength(255),
            ])
            ->action(function (array $data, Collection $records) {
                foreach ($records as $record) {
                    $record->update([
                        'payment_method_id' => $data['payment_method_id'],
                        'paid' => $data['paid'],
                        'note' => $data['note'],
                    ]);
                }

                Notification::make()
                    ->title('Pagamento de Parcelas atualizado com sucesso!')
                    ->success()
                    ->send();
            })
            ->modalHeading('Pagamento de Parcelas em Lote')
            ->modalSubmitActionLabel('Salvar')
            ->requiresConfirmation();
    }
}
