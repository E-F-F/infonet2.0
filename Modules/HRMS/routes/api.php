<?php

use Illuminate\Support\Facades\Route;
use Modules\HRMS\Http\Controllers\Attendance\HRMSAttendanceController;
use Modules\HRMS\Http\Controllers\HRMSController;
use Modules\HRMS\Http\Controllers\Staff\HRMSStaffController;
use Modules\HRMS\Http\Controllers\Training\HRMSTrainingAwardTypeController;
use Modules\HRMS\Http\Controllers\Training\HRMSTrainingTypeController;
use Modules\HRMS\Http\Controllers\Training\HRMSTrainingController;
use Modules\HRMS\Http\Controllers\Offence\HRMSOffenceTypeController;
use Modules\HRMS\Http\Controllers\Offence\HRMSOffenceActionTakenController;
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
use Modules\HRMS\Http\Controllers\Roster\HRMSRosterShiftController;
use Modules\HRMS\Http\Controllers\Roster\HRMSRosterGroupController;
use Modules\HRMS\Http\Controllers\Roster\HRMSRosterController;
use Modules\HRMS\Http\Controllers\Staff\HRMSDesignationController;
use Modules\HRMS\Http\Controllers\Staff\HRMSDepartmentController;
use Modules\HRMS\Http\Controllers\Staff\HRMSResignOptionController;
use Modules\HRMS\Models\HRMSAttendance;
use Modules\HRMS\Models\HRMSAttendanceStation;
use Modules\HRMS\Http\Controllers\Staff\HRMSMaritalstatusController;
use Modules\HRMS\Http\Controllers\Payroll\HRMSAppraisalTypeController;
use Modules\HRMS\Http\Controllers\Payroll\HRMSPayGroupController;
use Modules\HRMS\Http\Controllers\Staff\HRMSStaffRosterGroupAssignmentController;
use Modules\HRMS\Http\Controllers\Staff\HRMSStaffQualificationController;

// HRMS Module Routes - require 'hrms' access
Route::middleware('module.access:hrms')->group(function () {

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/test', [HRMSController::class, 'index']);
        Route::get('/staff', [HRMSStaffController::class, 'index']);
        Route::get('/staff/{id}', [HRMSStaffController::class, 'show']);
        Route::post('/staff/create', [HRMSStaffController::class, 'createStaff']);
        Route::post('/staff/update/{id}', [HRMSStaffController::class, 'updateStaff']);
        Route::get('/staff-list', [HRMSStaffController::class, 'simpleStaffList']);
        Route::get('/staff/{staffId}/leaves', [HRMSLeaveController::class, 'getLeavesByStaff']);
        Route::get('/staff/{staffId}/leave-adjustments', [HRMSLeaveAdjustmentController::class, 'getLeaveAdjustmentsByStaff']);
        Route::get('/staff/{staffId}/leave-entitlements', [HRMSLeaveEntitlementController::class, 'getByStaff']);
        Route::get('/staff/{staffId}/roster-groups', [HRMSStaffRosterGroupAssignmentController::class, 'getByStaff']);
        Route::prefix('/staff/{staffId}/qualifications')->group(function () {
            Route::get('/', [HRMSStaffQualificationController::class, 'getByStaff']);
            Route::post('/', [HRMSStaffQualificationController::class, 'storeForStaff']);
        });


        Route::prefix('hrms')->group(function () {
            // Staff Related Api
            Route::apiResource('designations', HRMSDesignationController::class);

            Route::apiResource('departments', HRMSDepartmentController::class);

            Route::apiResource('marital-status', HRMSMaritalStatusController::class);

            Route::apiResource('resign-options', HRMSResignOptionController::class);
            // Attendance Related API
            Route::apiResource('attendance', HRMSAttendanceController::class);
            Route::get('attendance', [HRMSAttendanceController::class, 'index']);
            Route::post('attendance', [HRMSAttendanceController::class, 'store']);
            Route::get('attendance/{id}', [HRMSAttendanceController::class, 'show']);
            // Route::put('training-award-types/{id}', [HRMSAttendance::class, 'update']);
            // Route::delete('training-award-types/{id}', [HRMSAttendance::class, 'destroy']);
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
            Route::get('/training/type/{id}/employees', [HRMSTrainingController::class, 'employeesByTrainingType']);


            // Offfences Related API
            Route::get('offence-types', [HRMSOffenceTypeController::class, 'index']);
            Route::post('offence-types', [HRMSOffenceTypeController::class, 'store']);
            Route::get('offence-types/{id}', [HRMSOffenceTypeController::class, 'show']);
            Route::put('offence-types/{id}', [HRMSOffenceTypeController::class, 'update']);
            Route::delete('offence-types/{id}', [HRMSOffenceTypeController::class, 'destroy']);

            Route::apiResource('offence-action-taken', HRMSOffenceActionTakenController::class);

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

            // Roster
            Route::apiResource('offdays', HRMSOffdayController::class);

            Route::apiResource('holidays', HRMSHolidayController::class);

            Route::apiResource('roster-group', HRMSRosterGroupController::class);

            Route::apiResource('roster-shift', HRMSRosterShiftController::class);

            Route::apiResource('roster-group-assignments', HRMSStaffRosterGroupAssignmentController::class);


            Route::post('roster/generate', [HRMSRosterController::class, 'generateForYear']);
            Route::get('roster/staff-shift/{id}', [HRMSRosterController::class, 'getStaffShift']);
            Route::apiResource('roster', HRMSRosterController::class);

            Route::apiResource('appraisal-type', HRMSAppraisalTypeController::class);
            Route::apiResource('pay-groups', HRMSPayGroupController::class);
        });
    });
});
