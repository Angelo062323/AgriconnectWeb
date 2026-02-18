<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LguController;
use App\Http\Controllers\FarmerController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdminUserController;

Route::get('/', function () {
    return view('user/login');
})->name('user.login');

// Admin routes
Route::prefix('sys-admin')->group(function () {

    // Show login form
    Route::get('/', [LoginController::class, 'index'])->name('sys-admin.login');

    // Validate login credentials
    Route::post('/validate', [LoginController::class, 'validateLogin'])->name('sys-admin.validate');

    // Admin content routes (protected by auth middleware)
    Route::get('/dashboard', function () {
        return view('admin/dashboard');
    })->name('sys-admin.dashboard')->middleware('auth');

    // LGU management
    Route::get('/lgu', [LguController::class, 'index'])
        ->name('sys-admin.lgu')
        ->middleware('auth');

    Route::post('/lgu', [LguController::class, 'store'])
        ->name('sys-admin.lgu.store')
        ->middleware('auth');

    Route::put('/lgu/{lgu}', [LguController::class, 'update'])
        ->name('sys-admin.lgu.update')
        ->middleware('auth');

    Route::delete('/lgu/bulk-delete', [LguController::class, 'bulkDelete'])
        ->name('sys-admin.lgu.bulk-delete')
        ->middleware('auth');

    // Farmer management
    Route::get('/farmers', [FarmerController::class, 'index'])
        ->name('sys-admin.farmers')
        ->middleware('auth');

    Route::post('/farmers', [FarmerController::class, 'store'])
        ->name('sys-admin.farmers.store')
        ->middleware('auth');

    Route::put('/farmers/{farmer}', [FarmerController::class, 'update'])
        ->name('sys-admin.farmers.update')
        ->middleware('auth');

    Route::delete('/farmers/bulk-delete', [FarmerController::class, 'bulkDelete'])
        ->name('sys-admin.farmers.bulk-delete')
        ->middleware('auth');

    // User management
    Route::get('/users', [AdminUserController::class, 'index'])
        ->name('sys-admin.users')
        ->middleware('auth');

    Route::get('/users/create', [AdminUserController::class, 'create'])
        ->name('sys-admin.users.create')
        ->middleware('auth');

    Route::post('/users', [AdminUserController::class, 'store'])
        ->name('sys-admin.users.store')
        ->middleware('auth');

    Route::post('/users/{user}/reset-password', [AdminUserController::class, 'resetPassword'])
        ->name('sys-admin.users.reset-password')
        ->middleware('auth');

    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])
        ->name('sys-admin.users.destroy')
        ->middleware('auth');

    Route::get('/requests', [RequestController::class, 'index'])
        ->name('sys-admin.requests')
        ->middleware('auth');

    Route::get('/reports', [ReportController::class, 'index'])
        ->name('sys-admin.reports')
        ->middleware('auth');
    
    Route::get('/settings', function () {
        return view('admin/settings');
    })->name('sys-admin.settings')->middleware('auth');

    // Logout route
    Route::get('/logout', [LoginController::class, 'logout'])->name('sys-admin.logout');

    // Redirect default login route to admin login
    Route::get('/login', function () {
        return redirect()->route('sys-admin.login');
    })->name('login');
});

