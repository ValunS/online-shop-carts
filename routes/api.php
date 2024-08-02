<?php

use App\Http\Controllers\Api\CurrencyController;
use App\Http\Controllers\Api\ExchangeRateController;
use App\Http\Controllers\Api\PurshaseController;
use App\Http\Controllers\Api\StoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('purshase', PurshaseController::class);
Route::apiResource('stores', StoreController::class);
Route::apiResource('exchange_rates', ExchangeRateController::class);

Route::get('/currencies', [CurrencyController::class, 'index']);