<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExchangeRateRequest;
use App\Http\Resources\ExchangeRateResourse;
use App\Models\Exchange_rate;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ExchangeRateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $query = Exchange_rate::query();

        if ($request->has('date')) {
            $query->whereDate('created_at', Carbon::parse($request->input('date'))->toDateString());
        } else {
            $query->latest();
        }

        $exchangeRate = $query->firstOrFail();
        return (new ExchangeRateResourse($exchangeRate))->response();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ExchangeRateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExchangeRateRequest $request)
    {
        try {
            DB::beginTransaction();
            $rate = Exchange_rate::create($request->validated());
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return response(['message' => $exception->getMessage()], 500);
        }
        return (new ExchangeRateResourse($rate))->response()->setStatusCode(201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Exchange_rate  $exchange_rate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Exchange_rate $exchange_rate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Exchange_rate  $exchange_rate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Exchange_rate $exchange_rate)
    {
        //
    }
}
