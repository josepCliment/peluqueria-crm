<?php

namespace App\Filament\Resources;

use App\Enums\Bills\BillState;
use App\Filament\Resources\BillResource\Pages;
use App\Filament\Resources\BillResource\RelationManagers;
use App\Models\Bill;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BillResource extends Resource
{
    protected static ?string $model = Bill::class;
    protected static ?string $slug = 'facturas';
    protected static ?string $navigationLabel = "Facturas";
    protected static ?string $navigationIcon = 'solar-bill-check-outline';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationGroup = 'Otros';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make()
                    ->columns(2)->schema([
                        TextInput::make('name')
                            ->required()
                            ->label('Razón Social'),
                        TextInput::make('amount')
                            ->numeric()
                            ->required()
                            ->label('Cantidad total (€)')
                            ->placeholder("00.00€"),
                        Textarea::make('description')
                            ->label('Descripción'),
                    ]),
                Split::make([

                    Select::make("state")
                        ->options(BillState::class)
                        ->default(BillState::PAGADO)
                        ->label("Estado de la factura")
                        ->required(),
                    DatePicker::make('payment_date')
                        ->default(Carbon::now())
                        ->label("Fecha de pago")
                        ->required()
                ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Razón Social'),
                TextColumn::make('amount')
                    ->label('Total abonado')
                    ->badge()
                    ->money('EUR'),
                TextColumn::make('state')
                    ->getStateUsing(function (Model $record) {
                        switch (BillState::from($record->state)) {
                            case BillState::DEVUELTA:
                                return 'Devuelta';
                            case BillState::PAGADO:
                                return 'Pagado';
                            case BillState::PAGADO_PARCIAL:
                                return 'Pagado parcialmente';
                        }
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        "Devuelta" => 'gray',
                        "Pagado" => 'success',
                        "Pagado parcialmente" => 'danger',
                    }),
                TextColumn::make('payment_date')
                    ->formatStateUsing(fn ($state) => $state->format('d/m/Y'))
                    ->label("Fecha de pago")
            ])
            ->filters([
                SelectFilter::make('state')
                    ->multiple()
                    ->options(BillState::class)
                    ->label('Estado de la factura'),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('Desde: '),
                        DatePicker::make('created_until')
                            ->default(now())
                            ->label("Hasta: "),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('payment_date', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('payment_date', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBills::route('/'),
            'create' => Pages\CreateBill::route('/create'),
            'edit' => Pages\EditBill::route('/{record}/edit'),
        ];
    }
}
