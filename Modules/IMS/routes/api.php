<?php

use Illuminate\Support\Facades\Route;
use Modules\IMS\Http\Controllers\IMSController;
use Modules\IMS\Http\Controllers\Marketing\IMSStockMarketingController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('ims', IMSController::class)->names('ims');
});

Route::prefix('ims/marketing')->group(function () {
    Route::get('stock', [IMSStockMarketingController::class, 'index']);
    Route::post('stock', [IMSStockMarketingController::class, 'store']);
    Route::get('stock/{id}', [IMSStockMarketingController::class, 'show']);

    Route::get('stocks', [IMSStockMarketingController::class, 'showStock']);
    Route::get('stocks-variants/{id}', [IMSStockMarketingController::class, 'showStockAllVariant']); //
});
