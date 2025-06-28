<?php

use Illuminate\Support\Facades\Route;
use Modules\IMS\Http\Controllers\IMSController;
use Modules\IMS\Http\Controllers\Marketing\IMSStockMarketingController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('ims', IMSController::class)->names('ims');
});

Route::prefix('ims/marketing')->group(function () {
    Route::get('stocks', [IMSStockMarketingController::class, 'index']);
    Route::get('stocks/{id}', [IMSStockMarketingController::class, 'show']);
    Route::get('stocks-variants/{id}', [IMSStockMarketingController::class, 'showStockAllVariant']); //
});
