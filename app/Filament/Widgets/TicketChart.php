<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TicketChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';
    protected int|string|array $columnSpan = 'full';
    protected function getData(): array
    {
        $start = Carbon::now()->subMonths(6)->startOfMonth();
        $end = Carbon::now()->endOfMonth();

        // Generar etiquetas de los últimos 6 meses incluyendo el mes actual
        $labels = [];
        for ($date = $start->copy(); $date <= $end; $date->addMonth()) {
            $labels[] = $date->format('M Y');
        }
        // Inicializar el array con 0 tickets por cada mes en el rango
        $ticketsPorMesArray = array_fill(0, count($labels), 0);
        
        // Realizar la consulta filtrando por rango de fechas
        $ticketsPorMes = Ticket::select(DB::raw('strftime(\'%Y-%m\', created_at) as year_month, COUNT(*) as count'))
            ->whereBetween('created_at', [$start->format('Y-m-d'), $end->format('Y-m-d')])
            ->groupBy('year_month')
            ->orderBy('year_month')
            ->get();


        // Rellenar el array de tickets por mes con los resultados de la consulta
        foreach ($ticketsPorMes as $ticket) {
            $yearMonth = Carbon::createFromFormat('Y-m', $ticket->year_month);
            $index = $yearMonth->diffInMonths($start);
            dd($index);
            $ticketsPorMesArray[$index] = $ticket->count;
        }
        dd($ticketsPorMesArray);
        return [
            'datasets' => [
                [
                    'label' => 'Tickets creados',
                    'data' => array_values($ticketsPorMesArray),
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
