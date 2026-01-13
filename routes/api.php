<?php

use App\Http\Controllers\AchController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PayrollController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware(['auth:api'])->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/refresh', [AuthController::class, 'refresh']);

    // Company routes
    Route::apiResource('companies', CompanyController::class);

    // Employee routes
    Route::apiResource('employees', EmployeeController::class);
    Route::get('/companies/{company}/employees', [EmployeeController::class, 'getByCompany']);

    // Payroll routes
    Route::apiResource('payroll-runs', PayrollController::class);
    Route::post('/payroll-runs/{payrollRun}/calculate', [PayrollController::class, 'calculate']);
    Route::post('/payroll-runs/{payrollRun}/approve', [PayrollController::class, 'approve']);
    Route::post('/payroll-runs/{payrollRun}/finalize', [PayrollController::class, 'finalize']);

    // ACH routes
    Route::post('/payroll-runs/{payrollRun}/process-ach', [AchController::class, 'processPayroll']);
    Route::get('/ach-transactions', [AchController::class, 'index']);
    Route::get('/ach-transactions/{transaction}', [AchController::class, 'show']);
});
