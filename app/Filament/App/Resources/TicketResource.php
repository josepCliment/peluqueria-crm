<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\TicketResource\Pages;
use App\Filament\App\Resources\TicketResource\RelationManagers;
use App\Filament\Resources\TicketResource\RelationManagers\ServiciosRelationManager;
use App\Models\Cliente;
use App\Models\Ticket;
use Filament\Forms;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        $total_debt = 0;
        $record = $form->getRecord();
        if ($record) {
            $client = $form->getRecord()->cliente_id;
            $total_debt = Cliente::find($client)->totalDebt();
        }
        return $form
            ->schema([
                Section::make([
                    Select::make('cliente_id')
                        ->required()
                        ->relationship(name: 'cliente', titleAttribute: 'name')
                        ->disabled(),
                    Radio::make('payment_method')
                        ->hidden(fn (string $operation): bool => $operation === 'create')
                        ->reactive()
                        ->disabled(fn (Get $get): bool => !filled($get('status')))
                        ->options([
                            'card' => 'Tarjeta',
                            'cash' => 'Efectivo'
                        ]),
                    Select::make('status')
                        ->hidden(fn (string $operation): bool => $operation === 'create')
                        ->label(__("Estado"))
                        ->options([
                            'paid' => 'Pagado',
                            'debt' => 'No pagado'
                        ])
                        ->live(),

                    Placeholder::make('total_debt')
                        ->hidden(fn (string $operation): bool => $operation === 'create')
                        ->label('Deuda acumulada')
                        ->content(new HtmlString("<span style='color: red; font-size: 16px;'>$total_debt €</span>"))

                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cliente.name')
                    ->searchable(),
                TextColumn::make('status')
                    ->label(__("Estado"))
                    ->badge()
                    ->getStateUsing(function (Model $record) {
                        return $record->status === "paid" ? 'Pagado' : 'Deuda';
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'Pagado' => 'success',
                        'Deuda' => 'danger',
                    })->sortable(),
                TextColumn::make('payment_method')
                    ->label(__("Forma de pago"))
                    ->getStateUsing(function (Model $record) {
                        return $record->payment_method === "card" ? 'Tarjeta' : ($record->payment_method === "cash" ? 'Efectivo' : '');
                    })
                    ->badge(),
                TextColumn::make('created_at')
                    ->label(__("Fecha"))
                    ->dateTime()

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ServiciosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }
}
