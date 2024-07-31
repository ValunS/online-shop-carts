<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PurshaseRequest;
use App\Http\Resources\PurshaseResourse;
use App\Models\Purshase;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PurshaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $store_id = $request->input('store_id');

        // Получаем все покупки, если не указан магазин
        $query = Purshase::query();

        // Если указан магазин, фильтруем покупки по нему
        if ($store_id) {
            $query->where('store_id', $store_id);
        }

        $purchases = $query->get();

        return PurshaseResourse::collection($purchases);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PurshaseRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(PurshaseRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();

            // Обработка загруженного файла
            if ($request->hasFile('document')) {
                $file = $request->file('document');
                $fileName = time() . '_' . $file->getClientOriginalName();

                // Проверка типа файла (PDF или JPG)
                $allowedMimeTypes = ['application/pdf', 'image/jpeg', 'image/jpg'];
                if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
                    throw new \Exception('Недопустимый формат файла.');
                }

                // Сохраняем документ локально
                Storage::disk('public')->put('documents/' . $fileName, file_get_contents($file));
//                Storage::disk('s3')->put('documents/'.$fileName, file_get_contents($file)); - конфигурация под s3
                $data['document'] = $fileName;
            }
            $purchase = Purshase::create($data);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return response(['message' => $exception->getMessage()], 500);
        }

        return (new PurshaseResourse($purchase))->response()->setStatusCode(201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Purshase  $purshase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Purshase $purshase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purshase  $purshase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purshase $purshase)
    {
        //
    }
}