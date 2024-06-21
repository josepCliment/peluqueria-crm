<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\DashboardResource\Widgets\RevenueMonthly;
use App\Models\Cliente;
use App\Models\Servicio;
use App\Models\Ticket;
use App\Models\TicketServicio;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class StatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = null;
    protected function getStats(): array
    {

        $statMonthly = $this->getTotalRevenueMonthlyStat();
        return [
            Stat::make('Clientes totales', $this->getTotalClients()),
            $statMonthly,
            Stat::make('Tickets totales', $this->getTotalTicketsPerMonth()),

        ];
    }

    protected function getTotalRevenueMonthlyStat(): Stat
    {
        $ticket =  Ticket::whereMonth('created_at', '=', Carbon::now())->sum('total');
        $tickets_month_before = Ticket::whereMonth('created_at', '=', Carbon::now()->subMonth())->sum('total');
        $diff_total_between_months = $ticket - $tickets_month_before;
        return Stat::make('Caja total este mes', $ticket . "€")
            ->description($this->checkRevenue($diff_total_between_months) ?
                $diff_total_between_months . "€ más que el més pasado" :
                $diff_total_between_months . "€ menos que el més pasado")
            ->descriptionIcon($this->checkRevenue($diff_total_between_months) ?
                'heroicon-m-arrow-trending-up' :
                'heroicon-m-arrow-trending-down')
            ->color($this->checkRevenue($diff_total_between_months) ? 'success' : 'danger');
    }

    protected function getTotalTicketsPerMonth(): int
    {
        return Ticket::whereMonth('created_at', '=', Carbon::now()->month)->count();
    }


    protected function getTotalClients(): string
    {
        return Cliente::all()->count();
    }

    protected function checkRevenue($diff_total_between_months): bool
    {
        return $diff_total_between_months > 0;
    }

    protected function getWidgets(): array
    {
        return [
            RevenueMonthly::class
        ];
    }
}
