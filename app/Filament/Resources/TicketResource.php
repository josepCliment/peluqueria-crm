<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketResource\Pages;
use App\Filament\Resources\TicketResource\RelationManagers\ServiciosRelationManager;
use App\Filament\Resources\TicketResource\Widgets\TotalTicket;
use App\Models\Ticket;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Panel;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)
                    ->schema([
                        Select::make('cliente_id')
                            ->searchable()
                            ->filled()
                            ->preload()
                            ->required()
                            ->relationship(name: 'cliente', titleAttribute: 'name'),
                        // Select::make('status')
                        //     ->label(__("Estado"))
                        //     ->options([
                        //         'paid' => 'Pagado',
                        //         'debt' => 'Deudor'
                        //     ])
                        //     ->live(),

                        // Select::make('payment_method')
                        //     ->label(__("Método de pago"))
                        //     ->options([
                        //         'card' => 'Tarjeta',
                        //         'cash' => 'Efectivo'
                        //     ])
                        //     ->reactive()
                        //     ->disabled(fn (Get $get): bool => !filled($get('status'))),
                    ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cliente.name'),
                TextColumn::make('total')
                    ->label('Total')
                    ->money('EUR'),
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
                    ->label(__("Estado"))
                    ->getStateUsing(function (Model $record) {
                        return $record->payment_method === "card" ? 'Tarjeta' : ($record->payment_method === "cash" ? 'Efectivo' : '');
                    })
                    ->badge()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
