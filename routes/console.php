<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Carbon\Carbon;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// The Central Bank of Russia updates its official exchange rates every working day around 11:30 AM Moscow time.
Schedule::command('app:fetch-exchange-rates')
    ->dailyAt('11:59')
    ->timezone('Europe/Moscow')
    ->when(function () {
        // Excluded Sundays from the scheduled task
        return now()->dayOfWeek !== Carbon::SUNDAY;
    });
