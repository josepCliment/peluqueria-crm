<?php

namespace App\Filament\Resources\DefaultersResource\Pages;

use App\Filament\Resources\DefaultersResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDefaulters extends ListRecords
{
    protected static string $resource = DefaultersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
