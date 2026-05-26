<?php

use App\Http\Controllers\Payment\PremiumController as Premium;

Route::controller(Premium::class)->prefix('user')->middleware(['auth', 'verified'])->group(function() {

    Route::get('/premium/upgrade', 'index')
        ->name('premium.choose');

    Route::post('/premium/upgrade', 'upgrade')
        ->name('premium.upgrade');

    Route::get('/premium/success', 'success')
        ->name('premium.success');

    Route::get('/premium/cancel', 'cancel')
        ->name('premium.cancel');
});
