<?php

namespace App\Filament\Resources\TicketResource\RelationManagers;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Table;

class ServiciosRelationManager extends RelationManager
{
    protected static string $relationship = 'servicios';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->id('name')->required()->label(__('Nombre'))->disabled(),
                TextInput::make('price')
                    ->placeholder('00.00')
                    ->inputMode('decimal')->label(__('Precio'))->disabled()
                    ->required(),
                TextInput::make('discount')
                    ->placeholder('00.00')
                    ->inputMode('decimal')->label(__('Descuento'))
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('discount'),
                Tables\Columns\TextColumn::make('price'),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
