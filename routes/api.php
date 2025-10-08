<?php

use App\Http\Controllers\Api\ImportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Import API Routes
Route::prefix('import')->group(function () {
    Route::post('/manga', [ImportController::class, 'importManga']);
    Route::post('/manga/{manga:slug}/chapters', [ImportController::class, 'importChapters']);
    Route::get('/genres', [ImportController::class, 'getGenres']);
    Route::get('/status', [ImportController::class, 'status']);
});
