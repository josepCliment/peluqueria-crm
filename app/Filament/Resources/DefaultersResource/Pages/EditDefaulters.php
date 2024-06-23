<?php

namespace App\Filament\Resources\DefaultersResource\Pages;

use App\Filament\Resources\DefaultersResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDefaulters extends EditRecord
{
    protected static string $resource = DefaultersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
