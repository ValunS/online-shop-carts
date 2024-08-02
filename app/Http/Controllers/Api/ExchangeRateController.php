<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExchangeRateRequest;
use App\Http\Resources\ExchangeRateResourse;
use App\Models\ExchangeRate;
use Illuminate\Http\Request;
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
        $exchangeRate = ExchangeRate::all();
        return ExchangeRateResourse::collection($exchangeRate);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExchangeRateRequest $request)
    {
        try {
            DB::beginTransaction();
            $rate = ExchangeRate::create($request->validated());
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
     * @param ExchangeRateRequest $request
     * @param  \App\Models\ExchangeRate  $exchangeRate
     * @return \Illuminate\Http\Response
     */
    public function update(ExchangeRateRequest $request, ExchangeRate $exchangeRate)
    {
        try {
            DB::beginTransaction();
            $exchangeRate->update($request->validated());
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return response(['message' => $exception->getMessage()], 500);
        }
        return (new ExchangeRateResourse($exchangeRate))->response()->setStatusCode(201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ExchangeRate  $exchangeRate
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExchangeRate $exchangeRate)
    {
        try {
            DB::beginTransaction();
            $exchangeRate->delete();
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return response(['message' => $exception->getMessage()], 500);
        }
        return response()->json(null, 204);
    }
}