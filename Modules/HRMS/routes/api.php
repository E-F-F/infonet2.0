<?php

use Illuminate\Support\Facades\Route;
use Modules\HRMS\Http\Controllers\HRMSController;
use Modules\HRMS\Http\Controllers\Staff\HRMSStaffController;
use Modules\HRMS\Http\Controllers\Training\HRMSTrainingAwardTypeController;
use Modules\HRMS\Http\Controllers\Training\HRMSTrainingTypeController;
use Modules\HRMS\Http\Controllers\Training\HRMSTrainingController;
use Modules\HRMS\Http\Controllers\Offence\HRMSOffenceTypeController;
use Modules\HRMS\Http\Controllers\Offence\HRMSOffenceController;
use Modules\HRMS\Http\Controllers\Event\HRMSEventTypeController;
use Modules\HRMS\Http\Controllers\Event\HRMSEventController;
use Modules\HRMS\Http\Controllers\Leave\HRMSLeaveRankController;
use Modules\HRMS\Http\Controllers\Leave\HRMSLeaveTypeController;
use Modules\HRMS\Http\Controllers\Leave\HRMSLeaveController;
use Modules\HRMS\Http\Controllers\Leave\HRMSLeaveEntitlementController;
use Modules\HRMS\Http\Controllers\Leave\HRMSLeaveModelController;
use Modules\HRMS\Http\Controllers\Leave\HRMSLeaveAdjustmentController;
use Modules\HRMS\Http\Controllers\Leave\HRMSLeaveAdjustmentReasonController;
use Modules\HRMS\Http\Controllers\Roster\HRMSOffdayController;
use Modules\HRMS\Http\Controllers\Roster\HRMSHolidayController;

// HRMS Module Routes - require 'hrms' access
Route::middleware('module.access:hrms')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/staff', [HRMSStaffController::class, 'index']);
        Route::get('/staff/{id}', [HRMSStaffController::class, 'index']);
        Route::post('/staff/create', [HRMSStaffController::class, 'createStaff']);
        Route::post('/staff/update/{id}', [HRMSStaffController::class, 'updateStaff']);

        Route::prefix('hrms')->group(function () {
            // Training Related API
            Route::get('training-award-types', [HRMSTrainingAwardTypeController::class, 'index']);
            Route::post('training-award-types', [HRMSTrainingAwardTypeController::class, 'store']);
            Route::get('training-award-types/{id}', [HRMSTrainingAwardTypeController::class, 'show']);
            Route::put('training-award-types/{id}', [HRMSTrainingAwardTypeController::class, 'update']);
            Route::delete('training-award-types/{id}', [HRMSTrainingAwardTypeController::class, 'destroy']);

            Route::get('training-types', [HRMSTrainingTypeController::class, 'index']);
            Route::post('training-types', [HRMSTrainingTypeController::class, 'store']);
            Route::get('training-types/{id}', [HRMSTrainingTypeController::class, 'show']);
            Route::put('training-types/{id}', [HRMSTrainingTypeController::class, 'update']);
            Route::delete('training-types/{id}', [HRMSTrainingTypeController::class, 'destroy']);

            Route::get('trainings', [HRMSTrainingController::class, 'index']);
            Route::post('trainings', [HRMSTrainingController::class, 'store']);
            Route::get('trainings/{id}', [HRMSTrainingController::class, 'show']);
            Route::put('trainings/{id}', [HRMSTrainingController::class, 'update']);
            Route::delete('trainings/{id}', [HRMSTrainingController::class, 'destroy']);


            // Offfences Related API
            Route::get('offence-types', [HRMSOffenceTypeController::class, 'index']);
            Route::post('offence-types', [HRMSOffenceTypeController::class, 'store']);
            Route::get('offence-types/{id}', [HRMSOffenceTypeController::class, 'show']);
            Route::put('offence-types/{id}', [HRMSOffenceTypeController::class, 'update']);
            Route::delete('offence-types/{id}', [HRMSOffenceTypeController::class, 'destroy']);

            Route::get('offences', [HRMSOffenceController::class, 'index']);
            Route::post('offences', [HRMSOffenceController::class, 'store']);
            Route::get('offences/{id}', [HRMSOffenceController::class, 'show']);
            Route::put('offences/{id}', [HRMSOffenceController::class, 'update']);
            Route::delete('offences/{id}', [HRMSOffenceController::class, 'destroy']);

            // Event Related API
            Route::get('event-types', [HRMSEventTypeController::class, 'index']);
            Route::post('event-types', [HRMSEventTypeController::class, 'store']);
            Route::get('event-types/{id}', [HRMSEventTypeController::class, 'show']);
            Route::put('event-types/{id}', [HRMSEventTypeController::class, 'update']);
            Route::delete('event-types/{id}', [HRMSEventTypeController::class, 'destroy']);

            Route::get('events', [HRMSEventController::class, 'index']);
            Route::post('events', [HRMSEventController::class, 'store']);
            Route::get('events/{id}', [HRMSEventController::class, 'show']);
            Route::put('events/{id}', [HRMSEventController::class, 'update']);
            Route::delete('events/{id}', [HRMSEventController::class, 'destroy']);

            // Leave Related API
            Route::apiResource('leave-ranks', HRMSLeaveRankController::class);

            Route::get('leave-types/model-based', [HRMSLeaveTypeController::class, 'listModelBasedLeaveTypes']);
            Route::apiResource('leave-types', HRMSLeaveTypeController::class);


            Route::apiResource('leave-models', HRMSLeaveModelController::class);

            Route::apiResource('leaves', HRMSLeaveController::class);

            Route::apiResource('leave-entitlements', HRMSLeaveEntitlementController::class);

            // HRMSLeaveAdjustment API Endpoints
            Route::apiResource('leave-adjustments', HRMSLeaveAdjustmentController::class);

            // HRMSLeaveAdjustmentReason API Endpoints
            Route::apiResource('leave-adjustment-reasons', HRMSLeaveAdjustmentReasonController::class);

            // // Additional routes for soft deletes (if needed for reasons)
            // Route::prefix('leave-adjustment-reasons')->group(function () {
            //     Route::patch('{id}/restore', [HRMSLeaveAdjustmentReasonController::class, 'restore'])->name('leave-adjustment-reasons.restore');
            //     Route::delete('{id}/force-delete', [HRMSLeaveAdjustmentReasonController::class, 'forceDelete'])->name('leave-adjustment-reasons.forceDelete');
            // });

            Route::apiResource('offdays', HRMSOffdayController::class);

            Route::apiResource('holidays', HRMSHolidayController::class);
        });
    });

    // IMS Module Routes - require 'ims' access
    Route::middleware('module.access:ims')->group(function () {
        // ... IMS routes
    });

    // FMS Module Routes - require 'fms' access
    Route::middleware('module.access:fms')->group(function () {
        // ... FMS routes
    });
});
