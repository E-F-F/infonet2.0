<?php

use Illuminate\Support\Facades\Route;
use Modules\HRMS\Http\Controllers\HRMSController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('hrms', HRMSController::class)->names('hrms');
});
