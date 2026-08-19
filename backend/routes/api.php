<?php

use App\Http\Controllers\Api\AnnouncementController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\MangaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// 1. Auth
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');
});

// 2. Manga (Public MangaDex Proxy)
Route::prefix('manga')->group(function () {
    Route::get('/recommendations', [MangaController::class, 'recommendations']);
    Route::get('/updates', [MangaController::class, 'updates']);
    Route::get('/popular', [MangaController::class, 'popular']);
    Route::get('/{id}', [MangaController::class, 'show']);
    Route::get('/{id}/chapters/{chapterId}', [MangaController::class, 'chapterPages'])->middleware('throttle:30,1');
});

// 3. Homepage Curated Content
Route::get('/banners', [BannerController::class, 'index']);
Route::get('/announcements', [AnnouncementController::class, 'index']);

