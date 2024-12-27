<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\Reports\FilterReportsBetweenMonths;
use App\Http\Services\Reports\FilterReportsByQuarter;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function getReportsByMonth(
        Request $request,
        FilterReportsBetweenMonths $filterReportsBetweenMonths
    ) {

        $request->validate([
            'startdate' => 'required|date',
            'enddate' => 'required|date'
        ]);

        $total = $filterReportsBetweenMonths
            ->filterBeetweenMonths(
                $request->startdate,
                $request->enddate
            );

        return response()->json([
            'total' => $total
        ]);
    }
    public function getReportsByMonthItemized(
        Request $request,
        FilterReportsBetweenMonths $filterReportsBetweenMonths
    ) {

        $request->validate([
            'startdate' => 'required|date',
            'enddate' => 'required|date'
        ]);

        $total = $filterReportsBetweenMonths
            ->filterBetweenDatesItemized(
                $request->startdate,
                $request->enddate
            );

        return response()->json([
            'total' => $total
        ]);
    }
    public function getReportsByQuarter(
        Request $request,
        FilterReportsByQuarter $filterReportsByQuarter
    ) {

        $request->validate([
            'quarter' => 'required|integer',
        ]);

        $total = $filterReportsByQuarter
            ->filterByQuarterNumber($request->quarter);

        return $total;
    }
    public function getReportsByQuarterItemized(
        Request $request,
        FilterReportsByQuarter $filterReportsByQuarter
    ) {

        $request->validate([
            'quarter' => 'required|integer',
        ]);

        $total = $filterReportsByQuarter
            ->filterByQuarterItemized($request->quarter);

        return $total;
    }
}