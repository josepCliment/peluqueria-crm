<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\BillsChart;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\TotalCharts;
use Filament\Facades\Filament;
use Filament\Pages\BasePage;
use Filament\Pages\Dashboard as PagesDashboard;

class Dashboard extends PagesDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.pages.dashboard';


    protected function getHeaderWidgets(): array
    {
        $isAdmin = auth()->user()->isAdmin();

        return $isAdmin ?
            [
                StatsOverview::class,
                BillsChart::class,
                TotalCharts::class,
            ] : [];
    }
}
