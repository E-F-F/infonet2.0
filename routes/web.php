<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

Route::get('/staff/login', [AuthController::class, 'login'])->name('staff.login');
Route::post('/staff/authenticate', [AuthController::class, 'authenticate'])->name('staff.authenticate');
Route::post('/staff/logout', [AuthController::class, 'logout'])->name('staff.logout');

Route::middleware(['check.system.access:inventory'])->group(function () {
    Route::get('/', [DashboardController::class, 'dashboard']);
});
