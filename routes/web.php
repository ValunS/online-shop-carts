<?php

use App\Http\Controllers\DocumentController;
use Illuminate\Support\Facades\Route;

Route::get('{any}', function () {
    return view('app');
})->where('any', '^(?!api|documents).*$'); // Исключаем api и documents

Route::get('/documents/{filename}', [DocumentController::class, 'show']);