<?php

namespace App\Filament\Resources\TicketResource\RelationManagers;

use App\Models\User;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class ServiciosRelationManager extends RelationManager
{
    protected static string $relationship = 'servicios';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->id('name')->required()
                    ->label(__('Nombre'))->disabled(),
                TextInput::make('price')
                    ->placeholder('00.00')
                    ->inputMode('decimal')->label(__('Precio'))->disabled()
                    ->required(),
                TextInput::make('discount')
                    ->placeholder('00.00')
                    ->inputMode('decimal')->label(__('Descuento'))
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->preload()
                    ->required()

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
                Tables\Columns\TextColumn::make('user.name')->sortable(),


            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                    ->preloadRecordSelect()
                    ->form(fn (AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required()
                    ])
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([]),
            ]);
    }
}
