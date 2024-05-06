<?php

use Illuminate\Support\Facades\Route;

Route::get('/rates', [\App\Http\Controllers\ExchangeRateController::class, 'index'])->name('exchange-rates.index');
