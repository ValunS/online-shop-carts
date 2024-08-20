<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function show($filename): Response
    {
        $path = 'documents/' . $filename; // Путь к файлу в хранилище

        if (!Storage::disk('public')->exists($path)) {
            abort(404);
        }

        $file = Storage::disk('public')->get($path);
        $type = Storage::disk('public')->mimeType($path);
        return response($file, 200)->header('Content-Type', $type);
    }
}