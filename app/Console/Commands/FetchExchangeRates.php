<?php

namespace App\Console\Commands;

use App\Jobs\FetchExchangeRatesJob;
use Illuminate\Console\Command;

class FetchExchangeRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-exchange-rates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch exchange rates from the Central Bank of Russian Federation.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fetching exchange rates...');

        FetchExchangeRatesJob::dispatch();

        $this->info('Dispatched job to fetch exchange rates.');
    }
}
