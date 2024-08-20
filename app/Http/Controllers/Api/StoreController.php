<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRequest;
use App\Http\Resources\StoreResource;
use App\Models\Store;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        /** @var Collection $store */
        $store = [];
        //Store::with("purchases")->get();
        return StoreResource::collection($store);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRequest $request
     * @return JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $validated_store = $request->validated();

        try {
            $store = Store::create($validated_store);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
        return response()->json(new StoreResource($store), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Store  $store
     * @return StoreResource
     */
    public function show(Store $store): StoreResource
    {
        return new StoreResource($store->with("purchases"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Store  $store
     * @return JsonResponse
     */
    public function update(StoreRequest $request, Store $store): JsonResponse
    {
        try {
            $validated_store = $request->validated();
            $store = Store::create($validated_store);
            return response()->json(new StoreResource($store), 201);
        } catch (Exception $exception) {
            return response()->json(['message' => 'Произошла ошибка при создании записи.', 'error' => $exception->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Store  $store
     * @return JsonResponse
     */
    public function destroy(Store $store): JsonResponse
    {
        try {
            $store->delete();
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
        return response()->json(null, 204);
    }
}