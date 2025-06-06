<?php

use Illuminate\Support\Facades\Route;
use Modules\HRMS\Http\Controllers\HRMSController;
use Modules\HRMS\Http\Controllers\HRMSStaffController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('hrms', HRMSController::class)->names('hrms');
    Route::group(['prefix' => 'hrms', 'as' => 'hrms.'], function () {

        // Route to display the form for creating a new staff member.
        // This maps to the `create` method in `StaffController`.
        // URL: /hrms/staff/create
        // Name: hrms.staff.create
        Route::get('staff/create', [HRMSStaffController::class, 'create'])->name('staff.create');

        // Route to store a newly created staff member in the database.
        // This uses a POST request and maps to the `store` method in `StaffController`.
        // URL: /hrms/staff
        // Name: hrms.staff.store
        Route::post('staff', [HRMSStaffController::class, 'store'])->name('staff.store');

        // Optional: If you want a route for the index method (to display a list of staff),
        // you would add it like this:
        // Route::get('staff', [StaffController::class, 'index'])->name('staff.index');

        

    });
});
