<?php

namespace App\Filament\Widgets;

use App\Models\Bill;
use Illuminate\Log\Logger;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class BillsChart extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'Facturas';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Facturas';
    protected static ?string $footer = 'Registro de gastos por més de las facturas';
    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected static ?string $pollingInterval = null;
    
    protected function getOptions(): array
    {

        $ticket_monthly = Bill::selectRaw("year(payment_date) year, monthname(payment_date) month, SUM(amount) as total ")
            ->groupBy('year', 'month')
            ->get()
            ->toArray();
        $data = array_fill(0, 12, 0);

        foreach ($ticket_monthly as $item) {
            $monthIndex = array_search(substr($item['month'], 0, 3), $this->getMonthLabels());
            if ($monthIndex !== false) {
                $data[$monthIndex] = (float) $item['total'];
            }
        }

        return [
            'chart' => [
                'type' => 'line',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'BillsChart',
                    'data' => $data,
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
            'stroke' => [
                'curve' => 'smooth',
            ],
        ];
    }

    protected function getMonthLabels(): array
    {
        return ['Jan', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    }
}
