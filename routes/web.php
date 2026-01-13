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
    
    // Employee Routes
    Route::middleware(['role:employee'])->prefix('employee')->name('employee.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Employee\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/payroll', [\App\Http\Controllers\Employee\PayrollController::class, 'index'])->name('payroll.index');
        Route::get('/payroll/{payrollItem}', [\App\Http\Controllers\Employee\PayrollController::class, 'show'])->name('payroll.show');
        Route::get('/bank-accounts', [\App\Http\Controllers\Employee\BankAccountController::class, 'index'])->name('bank-accounts.index');
        Route::post('/bank-accounts', [\App\Http\Controllers\Employee\BankAccountController::class, 'store'])->name('bank-accounts.store');
        Route::post('/bank-accounts/{bankAccount}/verify', [\App\Http\Controllers\Employee\BankAccountController::class, 'verify'])->name('bank-accounts.verify');
        Route::get('/profile', [\App\Http\Controllers\Employee\ProfileController::class, 'index'])->name('profile');
        Route::put('/profile', [\App\Http\Controllers\Employee\ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [\App\Http\Controllers\Employee\ProfileController::class, 'updatePassword'])->name('profile.password');
    });
    
    // Client Routes
    Route::middleware(['role:client'])->prefix('client')->name('client.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Client\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('companies', \App\Http\Controllers\Client\CompanyController::class);
        Route::resource('employees', \App\Http\Controllers\Client\EmployeeController::class);
        Route::post('/employees/{employee}/bank-accounts', [\App\Http\Controllers\Client\EmployeeController::class, 'storeBankAccount'])->name('employees.bank-accounts.store');
        Route::post('/employees/{employee}/bank-accounts/{bankAccount}/verify', [\App\Http\Controllers\Client\EmployeeController::class, 'verifyBankAccount'])->name('employees.bank-accounts.verify');
        Route::resource('payroll', \App\Http\Controllers\Client\PayrollController::class);
        Route::get('/ach', [\App\Http\Controllers\Client\AchController::class, 'index'])->name('ach.index');
        Route::post('/payroll/{payrollRun}/process-ach', [\App\Http\Controllers\Client\AchController::class, 'processPayroll'])->name('payroll.process-ach');
        Route::post('/payroll/{payroll}/calculate', [\App\Http\Controllers\Client\PayrollController::class, 'calculate'])->name('payroll.calculate');
        Route::post('/payroll/{payroll}/approve', [\App\Http\Controllers\Client\PayrollController::class, 'approve'])->name('payroll.approve');
        Route::post('/payroll/{payroll}/finalize', [\App\Http\Controllers\Client\PayrollController::class, 'finalize'])->name('payroll.finalize');
        Route::get('/reports', [\App\Http\Controllers\Client\ReportController::class, 'index'])->name('reports');
    });
    
    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('companies', \App\Http\Controllers\Admin\CompanyController::class);
        Route::resource('employees', \App\Http\Controllers\Admin\EmployeeController::class);
        Route::post('/employees/{employee}/bank-accounts', [\App\Http\Controllers\Admin\EmployeeController::class, 'storeBankAccount'])->name('employees.bank-accounts.store');
        Route::post('/employees/{employee}/bank-accounts/{bankAccount}/verify', [\App\Http\Controllers\Admin\EmployeeController::class, 'verifyBankAccount'])->name('employees.bank-accounts.verify');
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
