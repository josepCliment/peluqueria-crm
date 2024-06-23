<?php

namespace App\Filament\Pages;

use Filament\Tables\Columns\Layout\Panel;

class Dashboard extends \Filament\Pages\Dashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->pages([]);
    }
}
