<?php
namespace App\Http\Services\Reports;

use Illuminate\Support\Facades\DB;

class FilterReportsBetweenMonths
{
    public function filterBeetweenMonths(string $beginDate, string $endDate)
    {
        $total = DB::table('tickets')
            ->whereBetween(
                'created_at',
                [$beginDate, $endDate]
            )
            ->sum('total');
        return $total;
    }

    public function filterBetweenDatesItemized(string $beginDate, string $endDate)
    {
        $total = DB::table('tickets')
            ->select(DB::raw('SUM(total) as total_sum'), 'payment_method')
            ->whereBetween(
                'created_at',
                [$beginDate, $endDate]
            )
            ->groupBy('payment_method')
            ->get();
        return $total;
    }
}