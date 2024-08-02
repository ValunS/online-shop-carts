<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExchangeRate;

class CurrencyController extends Controller
{
    public function index()
    {
        $exchangeRates = ExchangeRate::all();

        $currencies = $exchangeRates->pluck('currency')->unique()->values();

        return response()->json(['data' => $currencies]);
    }
}