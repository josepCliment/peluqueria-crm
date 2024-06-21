<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use App\Models\TicketServicio;
use App\Models\User;
use Carbon\Carbon;
use Filament\Tables\Filters\Filter;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserAdminChart extends ChartWidget
{
    protected static ?string $heading = 'Total Caja';
    public ?string $filter = '1';
    protected static ?string $pollingInterval = null;
    protected $maxYValue = 4000;
    protected function getData(): array
    {

        $activeFilter = $this->filter;
        $userrevenue = Ticket::selectRaw("year(created_at) year, monthname(created_at) month, SUM(cprice* quantity) as total  ")
            ->leftJoin('ticket_servicio', 'id', '=', 'ticket_servicio.ticket_id')
            ->where('user_id', '=', $activeFilter)
            ->groupBy('year', 'month')
            ->get()
            ->toArray();


        return
            [
                'datasets' => [
                    [
                        'label' => 'Caja Total',
                        'data' => $this->getDataFromUserRevenue($userrevenue),
                        'backgroundColor' => '#36A2EB',
                        'borderColor' => '#9BD0F5',
                    ],
                ],
                'labels' => $this->getMonthLabels(),
                'options' => [
                    'scales' => [
                        'y' => [
                            'max' => $this->maxYValue,
                            'beginAtZero' => true,
                        ],
                    ],
                ],
                'plugins' => [
                    'datalabels' => [
                        'display' => true,
                        'borderColor' => 'white',
                        'borderRadius' => 25,
                        'borderWidth' => 2,
                        'color' => 'white',
                    ],
                ]
            ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getFilters(): array|null
    {
        $users = User::all(['id', 'name'])->pluck('name', 'id')->toArray();
        return
            $users;
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
