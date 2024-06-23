<?php

namespace App\Filament\Pages;

use Filament\Tables\Columns\Layout\Panel;
use Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin;

class Dashboard extends \Filament\Pages\Dashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->pages([]);
    }
}
