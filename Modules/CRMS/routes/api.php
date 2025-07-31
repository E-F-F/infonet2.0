<?php

use Illuminate\Support\Facades\Route;
use Modules\CRMS\Http\Controllers\CRMSController;
use Modules\CRMS\Http\Controllers\People\CRMSPeopleController;

// Route::middleware(['auth:sanctum'])->group(function () {
    // Route::apiResource('crms', CRMSController::class)->names('crms');
    Route::prefix('crms')->group(function () {
        Route::apiResource('people', CRMSPeopleController::class);
    });
// });
