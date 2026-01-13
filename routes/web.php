<?php

use App\Http\Controllers\WebAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('web.login');
});

// Authentication Routes
Route::get('/login', [WebAuthController::class, 'showLoginForm'])->name('web.login');
Route::post('/login', [WebAuthController::class, 'login']);
Route::get('/register', [WebAuthController::class, 'showRegisterForm'])->name('web.register');
Route::post('/register', [WebAuthController::class, 'register']);
Route::post('/logout', [WebAuthController::class, 'logout'])->name('web.logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [WebAuthController::class, 'dashboard'])->name('web.dashboard');
    
    // Admin Routes
    Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('companies', \App\Http\Controllers\Admin\CompanyController::class);
        Route::resource('employees', \App\Http\Controllers\Admin\EmployeeController::class);
        Route::resource('payroll', \App\Http\Controllers\Admin\PayrollController::class);
        Route::get('/ach', [\App\Http\Controllers\Admin\AchController::class, 'index'])->name('ach.index');
        Route::post('/payroll/{payrollRun}/process-ach', [\App\Http\Controllers\Admin\AchController::class, 'processPayroll'])->name('payroll.process-ach');
        Route::post('/payroll/{payroll}/calculate', [\App\Http\Controllers\Admin\PayrollController::class, 'calculate'])->name('payroll.calculate');
        Route::post('/payroll/{payroll}/approve', [\App\Http\Controllers\Admin\PayrollController::class, 'approve'])->name('payroll.approve');
        Route::post('/payroll/{payroll}/finalize', [\App\Http\Controllers\Admin\PayrollController::class, 'finalize'])->name('payroll.finalize');
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
        Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports');
    });
});
