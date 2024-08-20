<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;
use App\Http\Resources\PurchaseResource;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $store_id = $request->input('store_id');
        $searchTerm = $request->input('q');
        $currency = $request->input('currency');
        $sortBy = $request->input('sortBy');
        $sortOrder = $request->input('sortOrder', 'asc');

        // Получаем все покупки, если не указан магазин
        $query = Purchase::with('store');

        if ($searchTerm) {
            $query->where(function ($query) use ($searchTerm) {
                $query->where('purchase_date', 'like', "%$searchTerm%")
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
                $query->leftJoin('stores', 'stores.id', '=', 'purchases.store_id')
                    ->orderBy('stores.name', $sortOrder);
            } else {
                $query->orderBy($sortBy, $sortOrder);
            }
        }

        $purchases = $query->paginate(20);

        return PurchaseResource::collection($purchases);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PurchaseRequest  $request
     * @return JsonResponse
     */
    public function store(PurchaseRequest $request): JsonResponse
    {
        try {
            $validated_request = $request->validated();

            // Обработка загруженного файла
            if ($request->hasFile('document')) {
                $file = $request->file('document');
                $fileName = $this->generateUniqueFilename($file);
                $validated_request['document_path'] = str_replace('storage', '', $fileName); //remove prefix 'storage'
            }

            $purchase = Purchase::create($validated_request);
            $this->uploadFile($file, $fileName);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }

        return (new PurchaseResource($purchase))->response()->setStatusCode(201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Purchase  $purchase
     * @return JsonResponse
     */
    public function update(UpdatePurchaseRequest $request, Purchase $purchase): JsonResponse
    {
        try {
            $validated_request = $request->validated();

            // Обработка загруженного файла
            if ($request->hasFile('document')) {
                $file = $request->file('document');
                $fileName = $this->generateUniqueFilename($file);
                $validated_request["document_path"] = str_replace('storage', '', $fileName); //remove prefix 'storage'

                if ($purchase->document_path) { // Если уже был файл 
                    DB::transaction(static function () use ($purchase, $validated_request) {
                        $purchase->update($validated_request);
                        Storage::disk('public')->delete($purchase->document_path); // Удаляем старый файл
                        // Storage::disk('s3')->delete('documents/' . $purchase->document); // S3
                    });
                }
            } else {
                $purchase->update($validated_request);
            }


        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
        return (new PurchaseResource($purchase))->response()->setStatusCode(201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Purchase  $purchase
     * @return JsonResponse
     */
    public function destroy(Purchase $purchase): JsonResponse
    {
        try {

            // Удаляем файл покупки
            if ($purchase->document_path) {
                DB::transaction(static function () use ($purchase) {
                    $purchase->delete();
                    Storage::disk('public')->delete($purchase->document_path);
                    // Storage::disk('s3')->delete('documents/' . $purchase->document); // S3
                }, 5);
            }
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 500);
        }
        return response()->json(null, 204);
    }

    /**
     * Upload file to storage.
     *
     * @param  UploadedFile  $file
     * @return string
     */
    private function uploadFile(UploadedFile $file, string $fileName): string
    {
        $allowedMimeTypes = ['application/pdf', 'image/jpeg', 'image/jpg'];
        if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
            throw new Exception('Недопустимый формат файла.');
        }
        $filePath = 'documents/' . $fileName;
        Storage::disk('public')->put($filePath, $file);
        // Storage::disk('s3')->put('documents/' . $fileName, file_get_contents($file)); // S3
        return $filePath;
    }

    /**
     * Generate unique filename.
     *
     * @param  UploadedFile  $file
     * @return string
     */
    private function generateUniqueFilename(UploadedFile $file): string
    {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $fileName = Str::slug($originalName) . '.' . $extension;
        $counter = 1;
        while (Storage::disk('public')->exists('documents/' . $fileName)) {
            $fileName = Str::slug($originalName) . '-' . $counter . '.' . $extension;
            $counter++;
        }
        return $fileName;
    }
}