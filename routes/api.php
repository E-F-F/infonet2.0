<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\BankController;

// Route::get('/user', function (Request $request) {
//     return "Hello World!";
// });
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::prefix('branches')->group(function () {
        Route::get('/', [BranchController::class, 'index']);
        Route::post('/', [BranchController::class, 'store']);
        Route::get('{id}', [BranchController::class, 'show']);
        Route::put('{id}', [BranchController::class, 'update']);
        Route::delete('{id}', [BranchController::class, 'destroy']);
        Route::get('trashed/list', [BranchController::class, 'trashed']);
        Route::post('restore/{id}', [BranchController::class, 'restore']);
    });

    Route::prefix('banks')->group(function () {
        Route::get('/', [BankController::class, 'index']);
        Route::post('/', [BankController::class, 'store']);
        Route::get('{id}', [BankController::class, 'show']);
        Route::put('{id}', [BankController::class, 'update']);
        Route::delete('{id}', [BankController::class, 'destroy']);
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
