<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Log;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class TotalCharts extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'totalCharts';
    protected static ?int $sort = 1;
    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Caja Total';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected static ?string $pollingInterval = null;
    protected function getOptions(): array
    {
        $userrevenue = env('DB_CONNECTION') === 'mysql' ? Ticket::selectRaw("year(created_at) year, monthname(created_at) month, SUM(cprice* quantity) as total  ")
            ->join('ticket_servicio', 'id', '=', 'ticket_servicio.ticket_id')
            ->where('user_id', '=', $this->filterFormData)
            ->groupBy('year', 'month')
            ->get()
            ->toArray() : [];

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'Caja total',
                    'data' => $this->getDataFromUserRevenue($userrevenue),
                ],
            ],
            'xaxis' => [
                'categories' => $this->getMonthLabels(),
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'colors' => ['#f59e0b'],
        ];
    }
    protected function getType(): string
    {
        return 'bar';
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('user_id')
                ->options(User::all()->pluck('name', 'id')->toArray())
                ->default(auth()->user()->id)
        ];
    }

    protected function getDataFromUserRevenue(array $userrevenue): array
    {
        $data = array_fill(0, 12, 0);

        foreach ($userrevenue as $item) {
            $monthIndex = array_search(substr($item['month'], 0, 3), $this->getMonthLabels());
            if ($monthIndex !== false) {
                $data[$monthIndex] = (float) $item['total'];
            }
        }
        return $data;
    }

    protected function getMonthLabels(): array
    {
        return ['Jan', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    }
}
