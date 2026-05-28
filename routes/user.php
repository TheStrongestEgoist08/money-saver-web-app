<?php

use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\ExpenseController;
use App\Http\Controllers\User\WalletController;
use App\Http\Controllers\User\GoalsController;
use App\Http\Controllers\User\ReportController;
use App\Http\Controllers\User\SuggestionController;
use App\Http\Controllers\User\TransactionController;

# Profile routes
Route::controller(ProfileController::class)->middleware(['auth', 'verified'])->prefix('user')->group(function() {
    Route::get('/profile', 'edit')
        ->name('user.profile.edit');

    Route::patch('/profile', 'update')
        ->name('user.profile.update');

    Route::delete('/profile', 'destroy')
        ->name('user.profile.destroy');
});

# Dashboard routes
Route::controller(DashboardController::class)->middleware(['auth', 'verified'])->prefix('user')->group(function() {
    Route::get('/dashboard', 'index')
        ->name('user.dashboard');
});

# Expense route
Route::controller(ExpenseController::class)->middleware(['auth', 'verified', 'track.transaction'])->prefix('user')->group(function() {
    Route::get('/expenses', 'index')
        ->name('user.expenses');

    Route::post('/expenses/add', 'addExpenses')
        ->name('user.expenses.add');
});

# Wallet route
Route::controller(WalletController::class)->middleware(['auth', 'verified', 'track.transaction'])->prefix('user')->group(function() {
    Route::get('/wallets', 'index')
        ->name('user.wallets');

    Route::post('/wallets/newWallet', 'newWallet')
        ->name('user.wallet.newWallet');

    Route::post('/wallets/add-balance', 'addBalance')
        ->name('user.wallets.add-balance');

    Route::post('/balance/transfer', 'transfer')
        ->name('user.wallets.transfer');

    Route::delete('/wallet/{wallet}', 'destroy')
        ->name('user.wallets.destroy');
});

# Goals route
Route::controller(GoalsController::class)->middleware(['auth', 'verified'])->prefix('user')->group(function() {
    Route::get('/goals', 'index')
        ->name('user.goals');

    Route::get('/goals/filter', 'filter')
        ->name('user.goals.filter');

    Route::post('goals/store', 'store')
        ->name('user.goals.store');

    Route::post('/goals/add-money', 'addMoney')
        ->name('user.goals.add-money');

    Route::patch('/goals/cancel/{id}', 'cancel')
        ->name('user.goals.cancel');

    Route::delete('/goals/delete/{id}', 'destroy')
        ->name('user.goals.destroy');
});

# Report route
Route::controller(ReportController::class)->middleware(['auth', 'verified'])->prefix('user')->group(function() {
    Route::get('/reports', 'index')
        ->name('user.reports');

    Route::post('/reports/filter', 'filter')
        ->name('user.reports.filter');

    Route::post('/reports/export-pdf', 'exportPDF')
        ->name('user.reports.export-pdf');
});

# Suggestion route
Route::controller(SuggestionController::class)->middleware(['auth', 'verified', 'premium'])->prefix('user')->group(function() {
    Route::get('/suggestions', 'index')
        ->name('user.suggestions');

    Route::get('/suggestions/conversations', 'getConversations')
        ->name('user.suggestions.conversations');

    Route::get('/suggestions/conversations/{id}', 'showConversation')
        ->name('user.suggestions.conversation.show');

    Route::post('/suggestions/get', 'getSuggestions')
        ->name('user.suggestions.ai');

    Route::delete('/suggestions/conversations/{id}', 'deleteConversation')
        ->name('user.suggestions.conversation.delete');
});

# Transaction route
Route::controller(TransactionController::class)->middleware(['auth', 'verified'])->prefix('user')->group(function() {
    Route::get('/transactions', 'index')
        ->name('user.transactions');

    Route::get('/transactions/filter', 'filter')
        ->name('user.transactions.filter');
});
