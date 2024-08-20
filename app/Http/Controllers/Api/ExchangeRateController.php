<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExchangeRateRequest;
use App\Http\Resources\ExchangeRateResource;
use App\Models\ExchangeRate;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class ExchangeRateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $exchangeRate = ExchangeRate::pluck('currency');
        return ExchangeRateResource::collection($exchangeRate);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(ExchangeRateRequest $request): JsonResponse
    {
        $validated_request = $request->validated();
        try {
            $exchangeRate = ExchangeRate::createOrFirst($validated_request['currency'], $validated_request);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
        return response()->json(new ExchangeRateResource($exchangeRate), 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ExchangeRateRequest $request
     * @param  ExchangeRate  $exchangeRate
     * @return JsonResponse
     */
    public function update(ExchangeRateRequest $request, ExchangeRate $exchangeRate): JsonResponse
    {
        try {
            $exchangeRate->updateOrCreate($request->validated());
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
        return response()->json(new ExchangeRateResource($exchangeRate), 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  ExchangeRate  $exchangeRate
     * @return JsonResponse
     */
    public function destroy(ExchangeRate $exchangeRate): JsonResponse
    {
        try {
            $exchangeRate->delete();
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
        return response()->json(null, 204);
    }
}