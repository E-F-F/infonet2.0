<?php

use Illuminate\Support\Facades\Route;
use Modules\CRMS\Http\Controllers\CRMSController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('crms', CRMSController::class)->names('crms');
});
