<?php

use App\Http\Controllers\WebAuthController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return redirect()->route('web.login');
// });
Route::get('/', function () {
    return view('home');
});

Route::get('/how-it-work', function () {
    return view('inner-pages.how-it-work');
})->name('how-it-work');

Route::get('/features', function () {
    return view('inner-pages.features');
})->name('features');

Route::get('/security', function () {
    return view('inner-pages.security');
})->name('security');

Route::get('/about', function () {
    return view('inner-pages.about');
})->name('about');

Route::get('/contact', function () {
    return view('inner-pages.contact');
})->name('contact');

// Route::get('/', function () {
//     return view('');
// });

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
        Route::get('/employees/{employee}/create-payroll', [\App\Http\Controllers\Client\EmployeeController::class, 'createPayroll'])->name('employees.create-payroll');
        Route::post('/employees/{employee}/create-payroll', [\App\Http\Controllers\Client\EmployeeController::class, 'storePayroll'])->name('employees.create-payroll.store');
        Route::post('/employees/{employee}/bank-accounts/{bankAccount}/verify', [\App\Http\Controllers\Client\EmployeeController::class, 'verifyBankAccount'])->name('employees.bank-accounts.verify');
        Route::get('/payroll/register', [\App\Http\Controllers\Client\PayrollRegisterController::class, 'register'])->name('payroll.register');
        Route::get('/payroll/request', [\App\Http\Controllers\Client\PayrollRegisterController::class, 'requestPayroll'])->name('payroll.request');
        Route::resource('payroll', \App\Http\Controllers\Client\PayrollController::class);
        Route::get('/ach', [\App\Http\Controllers\Client\AchController::class, 'index'])->name('ach.index');
        Route::post('/payroll/{payrollRun}/process-ach', [\App\Http\Controllers\Client\AchController::class, 'processPayroll'])->name('payroll.process-ach');
        Route::get('/employees/{employee}/show-payroll/{payrollItem}', [\App\Http\Controllers\Client\EmployeeController::class, 'showPayroll'])->name('employees.show.payroll');
        Route::get('/employees/{employee}/show-payroll/{payrollItem}/pdf', [\App\Http\Controllers\Client\EmployeeController::class, 'downloadPayrollPaystub'])->name('employees.show.payroll.pdf');
        Route::get('/employees/{employee}/payroll/{payrollItem}/edit', [\App\Http\Controllers\Client\EmployeeController::class, 'editPayroll'])->name('employees.payroll.edit');
        Route::put('/employees/{employee}/payroll/{payrollItem}', [\App\Http\Controllers\Client\EmployeeController::class, 'updatePayroll'])->name('employees.payroll.update');
        Route::delete('/employees/{employee}/payroll/{payrollItem}', [\App\Http\Controllers\Client\EmployeeController::class, 'destroyPayroll'])->name('employees.payroll.destroy');
        Route::post('/payroll/{payroll}/calculate', [\App\Http\Controllers\Client\PayrollController::class, 'calculate'])->name('payroll.calculate');
        Route::post('/payroll/{payroll}/approve', [\App\Http\Controllers\Client\PayrollController::class, 'approve'])->name('payroll.approve');
        Route::post('/payroll/{payroll}/finalize', [\App\Http\Controllers\Client\PayrollController::class, 'finalize'])->name('payroll.finalize');
        Route::get('/reports', [\App\Http\Controllers\Client\ReportController::class, 'index'])->name('reports');
        Route::get('/federal-payroll-eftps', function () {
            return view('client.federal-payroll-eftps');
        })->name('federal-payroll-eftps');
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
