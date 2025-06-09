<?php

use Illuminate\Support\Facades\Route;
use Modules\HRMS\Http\Controllers\HRMSController;
use Modules\HRMS\Http\Controllers\HRMSStaffController;
use Modules\HRMS\Http\Controllers\HRMSLeaveRankController;
use Modules\HRMS\Http\Controllers\Event\HRMSEventTypeController;
use Modules\HRMS\Http\Controllers\Event\HRMSEventController;

Route::middleware(['check.system.access:hrms'])->group(function () {
    Route::group(['prefix' => 'hrms', 'as' => 'hrms.'], function () {
        Route::controller(HRMSLeaveRankController::class)->group(function () {
            Route::get('leave-ranks', 'index')->name('leave_ranks.index');
            Route::get('leave-ranks/create', 'create')->name('leave_ranks.create');
            Route::post('leave-ranks', 'store')->name('leave_ranks.store');
            Route::get('leave-ranks/{leaveRank}/edit', 'edit')->name('leave_ranks.edit');
            Route::put('leave-ranks/{leaveRank}', 'update')->name('leave_ranks.update');
            Route::delete('leave-ranks/{leaveRank}', 'destroy')->name('leave_ranks.destroy');
        });

        Route::controller(HRMSStaffController::class)->group(function () {
            Route::get('staff', 'index')->name('staff.index');
            Route::get('staff/create', 'create')->name('staff.create');
            Route::post('staff', 'store')->name('staff.store');
        });

        // web.php or routes file
        Route::prefix('event-types')->name('event-types.')->group(function () {
            Route::get('/', [HRMSEventTypeController::class, 'index'])->name('index');
            Route::post('/', [HRMSEventTypeController::class, 'store'])->name('store');
            Route::put('/{id}', [HRMSEventTypeController::class, 'update'])->name('update');
            Route::delete('/{id}', [HRMSEventTypeController::class, 'destroy'])->name('destroy');
        });
        Route::prefix('event')->name('event')->group(function () {
            Route::get('/', [HRMSEventController::class, 'index'])->name('index');
            // Route::post('/', [HRMSEventController::class, 'store'])->name('store');
            // Route::put('/{id}', [HRMSEventController::class, 'update'])->name('update');
            // Route::delete('/{id}', [HRMSEventController::class, 'destroy'])->name('destroy');
        });
    });
});
