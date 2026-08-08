<?php

use App\Services\MangaDex\MangaDexCacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/_debug/mangadex-test', function (MangaDexCacheService $mangaDex) {
    return response()->json($mangaDex->searchManga(['limit' => 3]));
});

