<?php

use Illuminate\Support\Facades\Route;
use Modules\IMS\Http\Controllers\IMSController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('ims', IMSController::class)->names('ims');
});
