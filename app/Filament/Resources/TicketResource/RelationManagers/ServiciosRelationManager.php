<?php

namespace App\Filament\Resources\TicketResource\RelationManagers;

use App\Models\Servicio;
use Illuminate\Database\Query\Builder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use PhpParser\Node\Stmt\Label;

class ServiciosRelationManager extends RelationManager
{
    protected static string $relationship = 'servicios';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->id('name')->required()
                    ->label(__('Servicio'))->disabled(),
                TextInput::make('pivot.price')
                    ->placeholder('00.00')
                    ->inputMode('decimal')->label(__('Precio'))->disabled()
                    ->required(),
                TextInput::make('discount')
                    ->placeholder('00.00')
                    ->inputMode('decimal')->label(__('Descuento'))
                    ->required(),
                Select::make('pivot.user_id')
                    ->label('Empleado')
                    ->relationship('user', 'name')
                    ->disabled()
                    ->preload()
                    ->required(),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('name')->label(__('Servicio')),
                Tables\Columns\TextColumn::make('user.name')->label(__('Empleado')),
                Tables\Columns\TextColumn::make('discount')->money('EUR')->label(__('Descuento')),
                TextColumn::make('price')
                    ->label(__('Precio'))
                    ->summarize(
                        Summarizer::make()
                        ->label('Total')
                        ->using(fn (Builder $query): string =>$query->sum('price') - $query->sum('discount'))
                        ->money('EUR')
                    )
                    ->money('EUR'),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                    ->preloadRecordSelect()
                    ->form(fn(AttachAction $action): array => [
                        $action->getRecordSelect()
                            ->searchable(),
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->preload()
                            ->required(),
                        TextInput::make('discount')
                            ->label('Descuento')
                            ->numeric()
                            ->required()
                            ->default(0),

                    ])
                    ->before(function ($data) {
                        $servicio = Servicio::find($data['recordId']);
                        $data['price'] = $servicio->price;
                        return $data;
                    })->action(function (array $data) {
                        $ticket = $this->ownerRecord;
                        $servicio = Servicio::find($data['recordId']);

                        $ticket->servicios()->attach($data['recordId'], [
                            'user_id' => $data['user_id'],
                            'discount' => $data['discount'],
                            'price' => $servicio->price, // Incluir el precio del servicio en la tabla pivot
                        ]);
                        $ticket->calcularTotal();
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make()
                    ->after(function () {
                        $ticket = $this->ownerRecord;
                        $ticket->calcularTotal();
                    })
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([]),
            ]);
    }
}
