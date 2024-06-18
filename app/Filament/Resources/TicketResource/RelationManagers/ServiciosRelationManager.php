<?php

namespace App\Filament\Resources\TicketResource\RelationManagers;

use App\Models\Servicio;
use App\Models\User;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Illuminate\Database\Query\Builder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Stmt\Label;

class ServiciosRelationManager extends RelationManager
{
    protected static ?string $inverseRelationship  = 'tickets';
    protected static string $relationship = 'servicios';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required()
                    ->label(__('Servicio'))->disabled(),
                TextInput::make('cprice')
                    ->placeholder('00.00')
                    ->inputMode('decimal')->label(__('Precio'))
                    ->required(),
                TextInput::make('discount')
                    ->placeholder('00.00')
                    ->inputMode('decimal')->label(__('Descuento'))
                    ->required(),
                Select::make('username')
                    ->label('Empleado')
                    ->relationship('user', 'name')
                    ->preload()
                    ->required(),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->allowDuplicates()
            ->recordTitleAttribute('name')
            ->paginated(false)
            ->columns([
                TextColumn::make('pivot_id')->label(__('ID')),
                TextColumn::make('name')->label(__('Servicio/Producto')),
                Tables\Columns\TextColumn::make('user.name')
                    ->formatStateUsing(function ($state, $record) {
                        $user = $record->user()->first();
                        return $user ? $user->name : '-';
                    })
                    ->label(__('Empleado')),
                Tables\Columns\TextColumn::make('discount')->money('EUR')->label(__('Descuento'))
                    ->badge()->color('danger')
                    ->summarize(Summarizer::make()
                        ->using(fn (Builder $builder) => $this->getOwnerRecord()->servicios()->sum('discount'))->money('EUR')),
                TextColumn::make('cprice')
                    ->label(__('Precio'))
                    ->summarize(Summarizer::make()
                        ->using(fn (Builder $builder) => $this->getOwnerRecord()->servicios()->sum('cprice'))
                        ->money('EUR'))
                    ->money('EUR')
                    ->badge()->color('success'),
                TextColumn::make('quantity')
                    ->label('Cantidad')
                    ->alignCenter(),
                TextColumn::make('total')
                    ->label('Total')
                    ->summarize(Summarizer::make()
                        ->using(fn (Builder $builder) => $this->getOwnerRecord()->getTotal())
                        ->money('EUR'))
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                    ->preloadRecordSelect()
                    ->form(fn (AttachAction $action): array => [
                        $action->getRecordSelect()
                            ->searchable(),
                        Grid::make(3)
                            ->schema([
                                Select::make('user_id')
                                    ->relationship('user', 'name')
                                    ->preload()
                                    ->required(),
                                TextInput::make('quantity')
                                    ->label('Cantidad')
                                    ->numeric()
                                    ->required()
                                    ->default(1),
                                TextInput::make('discount')
                                    ->label('Descuento')
                                    ->numeric()
                                    ->required()
                                    ->default(0),
                            ])

                    ])
                    ->before(function ($data) {
                        $servicio = Servicio::find($data['recordId']);
                        $data['cprice'] = $servicio->price;
                        $data['discount'] = $servicio->discount;
                        $data['quantity'] = $servicio->quantity;
                        return $data;
                    })->action(function (array $data) {
                        $ticket = $this->ownerRecord;
                        $servicio = Servicio::find($data['recordId']);

                        $ticket->servicios()->attach($data['recordId'], [
                            'user_id' => $data['user_id'],
                            'discount' => $data['discount'],
                            'quantity' => $data['quantity'],
                            'cprice' => $servicio->price, // Incluir el precio del servicio en la tabla pivot
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
