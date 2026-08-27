<?php

use App\Http\Controllers\Api\AnnouncementController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\BookmarkController;
use App\Http\Controllers\Api\MangaController;
use App\Http\Controllers\Api\ReadingHistoryController;
use App\Http\Controllers\Api\ScheduleController;
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

// 4. Library
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/bookmarks', [BookmarkController::class, 'index']);
    Route::post('/bookmarks', [BookmarkController::class, 'store']);
    Route::delete('/bookmarks/{mangaId}', [BookmarkController::class, 'destroy']);

    Route::get('/reading-history', [ReadingHistoryController::class, 'index']);
    Route::put('/reading-history', [ReadingHistoryController::class, 'update']);
});

// 5. Schedule
Route::get('/schedules', [ScheduleController::class, 'index']);



