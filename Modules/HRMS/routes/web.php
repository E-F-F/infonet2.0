<?php

use Illuminate\Support\Facades\Route;
use Modules\HRMS\Http\Controllers\HRMSController;
use Modules\HRMS\Http\Controllers\Staff\HRMSStaffController;
// use Modules\HRMS\Http\Controllers\HRMSLeaveRankController;
use Modules\HRMS\Http\Controllers\Event\HRMSEventTypeController;
use Modules\HRMS\Http\Controllers\Event\HRMSEventController;
use Modules\HRMS\Http\Controllers\Training\HRMSTrainingTypeController;
use Modules\HRMS\Http\Controllers\Training\HRMSTrainingAwardTypeController;
use Modules\HRMS\Http\Controllers\Leave\HRMSLeaveTypeController;
use Modules\HRMS\Http\Controllers\Leave\HRMSLeaveRankController;
use Modules\HRMS\Http\Controllers\Payroll\HRMSPayGroupController;
use Modules\HRMS\Http\Controllers\Payroll\HRMSAppraisalTypeController;

Route::middleware(['check.system.access:hrms'])->group(function () {
    Route::group(['prefix' => 'hrms', 'as' => 'hrms.'], function () {
        Route::get('/', [HRMSController::class, 'index'])->name('index');

        Route::controller(HRMSStaffController::class)->group(function () {
            Route::get('staff', 'index')->name('staff.index');
            Route::get('staff/{id}', 'show')->name('staff.show');
            Route::get('staff/create', 'create')->name('staff.create');
            Route::post('staff', 'store')->name('staff.store');
        });
        // Event
        Route::controller(HRMSEventController::class)
            ->prefix('event')
            ->name('event.')
            ->group(function () {
                Route::get('/',  'index')->name('index');
                // Route::post('/', 'store')->name('store');
                // Route::put('/{id}', 'update')->name('update');
                // Route::delete('/{id}', 'destroy')->name('destroy');
            });
        Route::controller(HRMSEventTypeController::class)
            ->prefix('event-types')
            ->name('event-types.')
            ->group(function () {
                Route::get('/',  'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::put('/{id}', 'update')->name('update');
                Route::delete('/{id}', 'destroy')->name('destroy');
            });
        // Training
        Route::controller(HRMSTrainingTypeController::class)
            ->prefix('training-types')
            ->name('training-types.')
            ->group(function () {
                Route::get('/',  'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::put('/{id}', 'update')->name('update');
                Route::delete('/{id}', 'destroy')->name('destroy');
            });
        Route::controller(HRMSTrainingAwardTypeController::class)
            ->prefix('training-award-types')
            ->name('training-award-types.')
            ->group(function () {
                Route::get('/',  'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::put('/{id}', 'update')->name('update');
                Route::delete('/{id}', 'destroy')->name('destroy');
            });
        // Leave
        Route::controller(HRMSLeaveTypeController::class)
            ->prefix('leave-types')
            ->name('leave-types.')
            ->group(function () {
                Route::get('/',  'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::put('/{id}', 'update')->name('update');
                Route::delete('/{id}', 'destroy')->name('destroy');
            });
        Route::controller(HRMSLeaveRankController::class)
            ->prefix('leave-ranks')
            ->name('leave-ranks.')
            ->group(function () {
                Route::get('/',  'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::put('/{id}', 'update')->name('update');
                Route::delete('/{id}', 'destroy')->name('destroy');
            });
        // Payroll
        Route::controller(HRMSPayGroupController::class)
            ->prefix('pay-groups')
            ->name('pay-groups.')
            ->group(function () {
                Route::get('/',  'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::put('/{id}', 'update')->name('update');
                Route::delete('/{id}', 'destroy')->name('destroy');
            });
        Route::controller(HRMSAppraisalTypeController::class)
            ->prefix('appraisal-types')
            ->name('appraisal-types.')
            ->group(function () {
                Route::get('/',  'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::put('/{id}', 'update')->name('update');
                Route::delete('/{id}', 'destroy')->name('destroy');
            });
    });
});
