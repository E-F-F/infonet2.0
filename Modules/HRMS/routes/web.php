<?php

use Illuminate\Support\Facades\Route;
use Modules\HRMS\Http\Controllers\HRMSController;
use Modules\HRMS\Http\Controllers\HRMSStaffController;
use Modules\HRMS\Http\Controllers\HRMSLeaveRankController;

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
    });
});
