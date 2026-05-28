<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('premium:check')->dailyAt('00:00')->withoutOverlapping;
Schedule::command('goals:check-overdue')->dailyAt('00:00')->withoutOverlapping;
