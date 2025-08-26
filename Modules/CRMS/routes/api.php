<?php

use Illuminate\Support\Facades\Route;
use Modules\CRMS\Http\Controllers\CRMSController;
use Modules\CRMS\Http\Controllers\People\CRMSBusinessNatureController;
use Modules\CRMS\Http\Controllers\People\CRMSPeopleController;
use Modules\CRMS\Http\Controllers\People\CRMSPeopleIncomeController;
use Modules\CRMS\Http\Controllers\People\CRMSPeopleOccupationController;
use Modules\CRMS\Http\Controllers\People\CRMSPeopleRaceController;

// Route::middleware(['auth:sanctum'])->group(function () {
    // Route::apiResource('crms', CRMSController::class)->names('crms');
    Route::prefix('crms')->group(function () {
        Route::apiResource('people', CRMSPeopleController::class);
        Route::apiResource('race', CRMSPeopleRaceController::class);
        Route::apiResource('occupation', CRMSPeopleOccupationController::class);
        Route::apiResource('income', CRMSPeopleIncomeController::class);
        Route::apiResource('business-nature', CRMSBusinessNatureController::class);
    });
// });
