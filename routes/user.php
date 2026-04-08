<?php

use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\ExpenseController;
use App\Http\Controllers\User\BalanceController;
use App\Http\Controllers\User\GoalsController;
use App\Http\Controllers\User\ReportController;

Route::controller(ProfileController::class)->middleware(['auth', 'verified'])->prefix('user')->group(function() {
    Route::get('/profile', 'edit')
        ->name('user.profile.edit');

    Route::patch('/profile', 'update')
        ->name('user.profile.update');

    Route::delete('/profile', 'destroy')
        ->name('user.profile.destroy');
});

Route::controller(DashboardController::class)->middleware(['auth', 'verified'])->prefix('user')->group(function() {
    Route::get('/dashboard', 'index')
        ->name('user.dashboard');
});

Route::controller(ExpenseController::class)->middleware(['auth', 'verified'])->prefix('user')->group(function() {
    Route::get('/expenses', 'index')
        ->name('user.expenses');
});

Route::controller(BalanceController::class)->middleware(['auth', 'verified'])->prefix('user')->group(function() {
    Route::get('/balance', 'index')
        ->name('user.expenses');
});

Route::controller(GoalsController::class)->middleware(['auth', 'verified'])->prefix('user')->group(function() {
    Route::get('/goals', 'index')
        ->name('user.goals');
});

Route::controller(ReportController::class)->middleware(['auth', 'verified'])->prefix('user')->group(function() {
    Route::get('/reports', 'index')
        ->name('user.reports');
});
