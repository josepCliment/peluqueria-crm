<?php

namespace App\Filament\Widgets;

use App\Models\Cliente;
use App\Models\Servicio;
use App\Models\Ticket;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $tickets = Ticket::whereMonth('created_at', '=', Carbon::now()->month)->sum('total');


        return [
            Stat::make('Clientes totales', Cliente::all()->count()),
            Stat::make('Caja total este mes', $tickets . "€"),
        ];
    }


    private function getTotalPerUser()
    {
        return Ticket::select('user_id', DB::raw('SUM(total) as total'))
            ->groupBy('user_id')
            ->get();
    }
}
