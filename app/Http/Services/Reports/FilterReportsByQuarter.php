<?php
namespace App\Http\Services\Reports;

use DateTime;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class FilterReportsByQuarter
{
    public function filterByQuarterNumber(int $quarter)
    {
        $dates = $this->getQuarterDates($quarter);

        $quarterResult = DB::table('tickets')
            ->select(
                DB::raw('SUM(total) as total_sum'),
                DB::raw('MONTH(created_at) as month')
            )
            ->whereBetween('created_at', [$dates['start'], $dates['end']])
            ->groupBy(DB::raw('month'))
            ->get();
        return $quarterResult;
    }
    public function filterByQuarterItemized(int $quarter)
    {
        $dates = $this->getQuarterDates($quarter);

        $quarterResult = DB::table('tickets')
            ->select(
                DB::raw('SUM(total) as total_sum'),
                DB::raw('MONTH(created_at) as month'),
                'payment_method'
            )
            ->whereBetween('created_at', [$dates['start'], $dates['end']])
            ->groupBy('payment_method', DB::raw('month'))
            ->get();

        return $quarterResult;
    }

    private function getQuarterDates(int $quarter)
    {
        if ($quarter < 1 || $quarter > 4) {
            throw new InvalidArgumentException("El trimestre debe estar entre 1 y 4.");
        }

        switch ($quarter) {
            case 1:
                // Trimestre 1: Enero 1 - Marzo 31
                return ['start' => '2024-01-01 00:00:00', 'end' => '2024-03-31 23:59:59'];
            case 2:
                // Trimestre 2: Abril 1 - Junio 30
                return ['start' => '2024-04-01 00:00:00', 'end' => '2024-06-30 23:59:59'];
            case 3:
                // Trimestre 3: Julio 1 - Septiembre 30
                return ['start' => '2024-07-01 00:00:00', 'end' => '2024-09-30 23:59:59'];
            case 4:
                // Trimestre 4: Octubre 1 - Diciembre 31
                return ['start' => '2024-10-01 00:00:00', 'end' => '2024-12-31 23:59:59'];
            default:
                // Si se pasa un valor incorrecto
                return null;
        }
    }
}