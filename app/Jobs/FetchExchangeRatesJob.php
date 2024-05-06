<?php

namespace App\Jobs;

use App\Models\ExchangeRate;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FetchExchangeRatesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $today = now()->format('Y-m-d');
        if (ExchangeRate::where('exchange_date', $today)->exists()) {
            info('Rates already fetched for today: ' . $today);
            return;
        }

        $retryCount = 0;
        $maxRetries = 5; // Maximum number of retries
        $retryDelay = 60; // Delay in seconds (1 minute)

        do {
            try {
                $response = Http::retry(3, 200)  // Retry up to 3 times with a 200ms delay between retries
                    ->get(config('services.cbr.url'));

                if ($response->successful()) {
                    $data = simplexml_load_string($response->body());
                    $exchangeDate = (string)$data['Date'];
                    $exchangeDate = !empty($exchangeDate) ? Carbon::parse($exchangeDate)->format('Y-m-d') : $today;
                    if (ExchangeRate::where('exchange_date', $exchangeDate)->exists()) {
                        info('Rates already fetched for date: ' . $exchangeDate);
                        return;
                    }
                    foreach ($data->Valute as $valute) {
                        ExchangeRate::create([
                            'valute_id' => (string)$valute['ID'],
                            'num_code' => (string)$valute->NumCode,
                            'char_code' => (string)$valute->CharCode,
                            'nominal' => (int)$valute->Nominal,
                            'name' => (string)$valute->Name,
                            'value' => (string)$valute->Value,
                            'vunit_rate' => (string)$valute->VunitRate,
                            'exchange_date' => $exchangeDate,
                        ]);
                    }
                    return; // Exit the loop if the request was successful
                }

                // Log failure and prepare for a possible retry
                Log::error('Failed to retrieve data: ' . $response->status());
                $retryCount++;
                sleep($retryDelay); // Sleep for $retryDelay seconds
                $retryDelay *= 2; // Exponential backoff
            } catch (\Exception $e) {
                Log::error('Error fetching exchange rates: ' . $e->getMessage());
                $retryCount++;
                sleep($retryDelay);
                $retryDelay *= 2;
            }
        } while ($retryCount < $maxRetries);
    }


    public function failed(\Exception $exception): void
    {
        Log::error('Failed to fetch exchange rates: ' . $exception->getMessage());
        $this->release(300); // Delay for 5 minutes
    }
}
