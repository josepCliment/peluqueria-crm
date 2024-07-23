<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DefaultersResource\Pages;
use App\Filament\Resources\DefaultersResource\RelationManagers;
use App\Models\Cliente;
use App\Models\Defaulters;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DefaultersResource extends Resource
{
    protected static ?string $model = Cliente::class;
    protected static ?string $navigationLabel = 'Deudas';
    protected static ?string $slug = 'defaulters';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationGroup = 'Otros';
    protected static ?string $label = "Deudores";
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->selectRaw('clientes.*, SUM(total) as total')
            ->join('tickets', 'clientes.id', '=', 'tickets.cliente_id')
            ->where('status', '=', 'debt');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->heading('Deudas')
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre'),
                TextColumn::make('total')
                    ->label('Deuda')
                    ->money('EUR')
                    ->badge()
                    ->color('danger')
            ])
            ->filters([
                //
            ])
            ->actions([])
            ->bulkActions([]);
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDefaulters::route('/')
        ];
    }
}
