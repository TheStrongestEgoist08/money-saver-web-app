<?php

use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\ExpenseController;
use App\Http\Controllers\User\WalletController;
use App\Http\Controllers\User\GoalsController;
use App\Http\Controllers\User\ReportController;
use App\Http\Controllers\User\SuggestionController;
use App\Models\Wallet;

# profile routes
Route::controller(ProfileController::class)->middleware(['auth', 'verified'])->prefix('user')->group(function() {
    Route::get('/profile', 'edit')
        ->name('user.profile.edit');

    Route::patch('/profile', 'update')
        ->name('user.profile.update');

    Route::delete('/profile', 'destroy')
        ->name('user.profile.destroy');
});

# dashboard routes
Route::controller(DashboardController::class)->middleware(['auth', 'verified'])->prefix('user')->group(function() {
    Route::get('/dashboard', 'index')
        ->name('user.dashboard');
});

# expense route
Route::controller(ExpenseController::class)->middleware(['auth', 'verified'])->prefix('user')->group(function() {
    Route::get('/expenses', 'index')
        ->name('user.expenses');

    Route::post('/expenses/add', 'addExpenses')
        ->name('user.expenses.add');
});

# wallet route
Route::controller(WalletController::class)->middleware(['auth', 'verified'])->prefix('user')->group(function() {
    Route::get('/wallets', 'index')
        ->name('user.wallets');

    Route::post('/wallets/newWallet', 'newWallet')
        ->name('user.wallet.newWallet');

    Route::post('/wallets/add-balance', 'addBalance')
        ->name('user.wallets.add-balance');

    Route::post('/balance/transfer', 'transfer')
        ->name('user.wallets.transfer');

    Route::delete('/wallet/{wallet}', 'destroy')
        ->name('user.wallet.destroy');
});

# goals route
Route::controller(GoalsController::class)->middleware(['auth', 'verified'])->prefix('user')->group(function() {
    Route::get('/goals', 'index')
        ->name('user.goals');
});

# report route
Route::controller(ReportController::class)->middleware(['auth', 'verified'])->prefix('user')->group(function() {
    Route::get('/reports', 'index')
        ->name('user.reports');

    Route::post('/reports/filter', 'filter')
        ->name('user.reports.filter');

    Route::post('/reports/export-pdf', 'exportPDF')
        ->name('user.reports.export-pdf');
});

# Suggestion route
Route::controller(SuggestionController::class)->middleware(['auth', 'verified'])->prefix('user')->group(function() {
    Route::get('/suggestions', 'index')
        ->name('user.suggestions');

    Route::get('/suggestions/ai', 'aiSuggestions')
        ->name('user.suggestions.ai');
});
