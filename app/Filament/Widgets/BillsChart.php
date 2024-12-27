<?php

namespace App\Filament\Widgets;

use App\Models\Bill;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class BillsChart extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'Facturas';
    protected static ?int $sort = 1;
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
        $data =  $this->fillData($this->buildQuerys());

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
    private function fillData(array $ticket_monthly)
    {
        $data = array_fill(0, 12, 0);
        foreach ($ticket_monthly as $item) {
            $item = (array) $item;
            $monthIndex = array_search(substr($item['month'], 0, 3), $this->getMonthLabels());
            if ($monthIndex !== false) {
                $data[$monthIndex] = (float) $item['total'];
            }
        }
        return $data;
    }

    private function buildQuerys(): array
    {

        // if (App::environment(['dev'])) {
        //     return Bill::selectRaw("strftime('%Y', payment_date) year, substr('JanFebMarAprMayJunJulAugSepOctNovDec', 1 + 3*strftime('%m', date('now')), -3) as month, SUM(amount) as total ")
        //         ->groupBy('year', 'month')
        //         ->get()
        //         ->toArray();
        // }
        return
            Bill::selectRaw("year(payment_date) year, monthname(payment_date) month, SUM(amount) as total ")
            ->groupBy('year', 'month')
            ->get()
            ->toArray();
    }
}
