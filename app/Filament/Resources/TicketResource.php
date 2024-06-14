<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketResource\Pages;
use App\Filament\Resources\TicketResource\RelationManagers;
use App\Filament\Resources\TicketResource\RelationManagers\ClienteRelationManager;
use App\Models\Ticket;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Step::make('Añadir Cliente')
                        ->schema([
                            Select::make('cliente_id')
                                ->relationship(name: 'cliente', titleAttribute: 'name')
                                ->searchable(env('APP_ENV') !== 'local')
                                ->required()
                        ]),
                    Step::make('Añadir Servicios')
                        ->schema([
                            Section::make()
                                ->columns([
                                    'sm' => 2,
                                    'xl' => 2,
                                    '2xl' => 2,
                                ])
                                ->schema([
                                    Select::make('servicios')
                                        ->multiple(),
                                    TextInput::make('uds')
                                        ->default(1)
                                        ->numeric()

                                ])

                        ]),
                    Step::make('Añadir Productos')
                        ->schema([]),
                    Step::make('Finalizar')
                        ->schema([])
                ])
                    ->columnSpan('full')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
