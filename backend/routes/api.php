<?php

use App\Http\Controllers\Api\AnnouncementController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\MangaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// 2. Manga (Public MangaDex Proxy)
Route::prefix('manga')->group(function () {
    Route::get('/recommendations', [MangaController::class, 'recommendations']);
    Route::get('/updates', [MangaController::class, 'updates']);
    Route::get('/popular', [MangaController::class, 'popular']);
});

// 3. Homepage Curated Content
Route::get('/banners', [BannerController::class, 'index']);
Route::get('/announcements', [AnnouncementController::class, 'index']);

