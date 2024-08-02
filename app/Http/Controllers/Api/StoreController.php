<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRequest;
use App\Http\Resources\StoreResourse;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $store = Store::with("purshases")->get();
        return StoreResourse::collection($store);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $store = Store::create($request->validated());
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return response(['message' => $exception->getMessage()], 500);
        }

        return (new StoreResourse($store))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function show(Store $store)
    {
        return new StoreResourse($store);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function update(StoreRequest $request, Store $store)
    {
        try {
            DB::beginTransaction();
            $store->update($request->validated());
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return response(['message' => $exception->getMessage()], 500);
        }
        return (new StoreResourse($store))->response()->setStatusCode(201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function destroy(Store $store)
    {
        try {
            DB::beginTransaction();
            $store->delete();
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return response(['message' => $exception->getMessage()], 500);
        }
        return response()->json(null, 204);
    }
}