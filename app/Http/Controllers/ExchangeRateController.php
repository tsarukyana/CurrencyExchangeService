<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExchangeRateRequest;
use App\Models\ExchangeRate;

class ExchangeRateController extends Controller
{
    /**
     * Retrieves exchange rates for a specified date from the database and returns them in a JSON response.
     *
     * @param ExchangeRateRequest $request The request object containing the date parameter.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the exchange rates.
     */
    public function index(ExchangeRateRequest $request)
    {
        // Get the date from the request or use the current date as default
        $date = $request->input('date', now()->format('Y-m-d'));

        // Retrieve rates for the specified date
        $rates = ExchangeRate::where('exchange_date', $date)->get();

        // Return the rates in a JSON response
        return response()->json($rates);
    }
}

