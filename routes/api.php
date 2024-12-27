<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ReportsController;
use App\Http\Middleware\CheckApiKeySecret;
use Illuminate\Support\Facades\Route;



Route::middleware([CheckApiKeySecret::class])->group(function () {
    Route::post(
        '/reports/itemized',
        [ReportsController::class, 'getReportsByMonthItemized']
    );
    Route::post(
        '/reports',
        [ReportsController::class, 'getReportsByMonth']
    );
    Route::post(
        '/reports/quarter',
        [ReportsController::class, 'getReportsByQuarter']
    );
    Route::post(
        '/reports/quarter/itemized',
        [ReportsController::class, 'getReportsByQuarterItemized']
    );
});





