<?php

namespace App\Filament\Resources\DashboardResource\Widgets;

use App\Models\Ticket;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Log;

class RevenueMonthly extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        return [
            Stat::make('Monthly Revenue', function () {
                $data = $this->getMonthlyData();
                Log::debug($data);
                $labels = array_column($data, 'month');
                $values = array_column($data, 'total');

                return [
                    'type' => 'line',
                    'data' => [
                        'labels' => ["Jan", "adsd"],
                        'datasets' => [
                            [
                                'label' => 'Revenue',
                                'data' => $values,
                            ],
                        ],
                    ],
                ];
            }),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
    private function getMonthlyData(): array
    {
        $data = Ticket::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total) as total')
            ->whereMonth('created_at', '=', Carbon::now()->month)
            ->groupBy('month')
            ->get()
            ->toArray();
        Log::debug($data);

        return $data;
    }
}
