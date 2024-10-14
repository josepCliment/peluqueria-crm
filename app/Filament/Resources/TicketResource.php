<?php

namespace App\Filament\Resources;

use App\Enums\Ticket\TicketPayment;
use App\Enums\Ticket\TicketState;
use App\Filament\Resources\TicketResource\Pages;
use App\Filament\Resources\TicketResource\RelationManagers\ServiciosRelationManager;
use App\Models\Cliente;
use App\Models\Ticket;
use Closure;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

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
                        ->searchable()
                        ->filled()
                        ->preload()
                        ->required()
                        ->reactive()
                        ->relationship(name: 'cliente', titleAttribute: 'name')
                       ,
                    Radio::make('payment_method')
                        ->reactive()
                        ->disabled(fn(Get $get): bool => !filled($get('status')))
                        ->options([
                            TicketPayment::CARD => 'Tarjeta',
                            TicketPayment::CASH => 'Efectivo'
                        ]),
                    Select::make('status')
                        ->label(__("Estado"))
                        ->options(TicketState::class)
                        ->live(),

                    Placeholder::make('total_debt')
                        ->label('Deuda acumulada')
                        ->reactive()
                        ->content(function (Get $get) {
                            $clienteId = $get('cliente_id');
                            $cliente = Cliente::find($clienteId);
                            $totalDebt = $cliente ? $cliente->totalDebt() : 0;
                            return new HtmlString("<span style='color: red; font-size: 16px;'>$totalDebt €</span>");
                        }),

                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cliente.name')
                    ->searchable(),
                TextColumn::make('total')
                    ->label('Total')
                    ->money('EUR'),
                TextColumn::make('status')
                    ->label(__("Estado"))
                    ->badge()
                    ->getStateUsing(function (Model $record) {
                        return $record->status === "paid" ? 'Pagado' : 'Deuda';
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'Pagado' => 'success',
                        'Deuda' => 'danger',
                    })->sortable(),
                TextColumn::make('payment_method')
                    ->label(__("Método de pago"))
                    ->getStateUsing(function (Model $record) {
                        return $record->payment_method === TicketPayment::CARD ? 'Tarjeta' : ($record->payment_method === TicketPayment::CASH ? 'Efectivo' : '');
                    })
                    ->badge()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__("Fecha"))
                    ->dateTime('d-m-Y H:i')
                    ->toggleable()
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
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

    public static function getRelations(): array
    {
        return [
            ServiciosRelationManager::class,
        ];
    }

    public static function getWidgets(): array
    {
        return [];
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
