<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Route::get('/user', function (Request $request) {
//     return "Hello World!";
// });
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // HRMS Module Routes - require 'hrms' access
    Route::middleware('module.access:hrms')->group(function () {
        Route::get('/users', function (Request $request) {
            return "Hello World!";
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
