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
use Illuminate\Support\Str;

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
        $searchTerm = $request->input('q');
        $currency = $request->input('currency');
        $sortBy = $request->input('sortBy');
        $sortOrder = $request->input('sortOrder', 'asc');

        // Получаем все покупки, если не указан магазин
        $query = Purshase::with('store');

        if ($searchTerm) {
            $query->where(function ($query) use ($searchTerm) {
                $query->where('purshase_date', 'like', "%$searchTerm%")
                // ->orWhere('sum', '=', (float) $searchTerm)
                    ->orWhere('currency', 'like', "%$searchTerm%")
                    ->orWhere('document_path', 'like', "%$searchTerm%")
                    ->orWhereHas('store', function ($query) use ($searchTerm) {
                        $query->where('name', 'like', "%$searchTerm%");
                    });
            });
        }

        // Если указан магазин, фильтруем покупки по нему
        if ($store_id) {
            $query->where('store_id', $store_id);
        }
        if ($currency) {
            $query->where('currency', $currency);
        }

        if ($sortBy) {
            if ($sortBy === 'store_name') {
                $query->orderBy(
                    Store::select('name')
                        ->whereColumn('stores.id', 'purshases.store_id'),
                    $sortOrder
                );
            } else {
                $query->orderBy($sortBy, $sortOrder);
            }
        }

        $purshases = $query->paginate(20);

        return PurshaseResourse::collection($purshases);
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
                $fileName = $this->uploadFile($file);
                $data['document_path'] = $fileName;
            }

            $purshase = Purshase::create($data);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return response(['message' => $exception->getMessage()], 500);
        }

        return (new PurshaseResourse($purshase))->response()->setStatusCode(201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Purshase  $purshase
     * @return \Illuminate\Http\Response
     */
    public function update(PurshaseRequest $request, Purshase $purshase)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();

            // Обработка загруженного файла
            if ($request->hasFile('document')) {
                // Удаляем старый файл
                if ($purshase->document_path) {
                    Storage::disk('public')->delete($purshase->document_path);
                    // Storage::disk('s3')->delete('documents/' . $purshase->document); // S3
                }
                $file = $request->file('document');
                $fileName = $this->uploadFile($file);
                $data["document_path"] = $fileName;
            }

            $purshase->update($data);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return response(['message' => $exception->getMessage()], 500);
        }
        return (new PurshaseResourse($purshase))->response()->setStatusCode(201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purshase  $purshase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purshase $purshase)
    {
        try {
            DB::beginTransaction();

            // Удаляем файл покупки
            if ($purshase->document_path) {
                Storage::disk('public')->delete($purshase->document_path);
                // Storage::disk('s3')->delete('documents/' . $purshase->document); // S3
            }
            $purshase->delete();

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return response(['message' => $exception->getMessage()], 500);
        }
        return response()->json(null, 204);
    }

    private function uploadFile($file)
    {
        $allowedMimeTypes = ['application/pdf', 'image/jpeg', 'image/jpg'];
        if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
            throw new \Exception('Недопустимый формат файла.');
        }
        $originalName = $file->getClientOriginalName();
        $fileName = Str::random(40) . '.' . $file->getClientOriginalExtension();
        $filePath = 'documents/' . $fileName;
        Storage::disk('public')->put($filePath, file_get_contents($file));
        // Storage::disk('s3')->put('documents/' . $fileName, file_get_contents($file)); // S3
        return $filePath;
    }
}