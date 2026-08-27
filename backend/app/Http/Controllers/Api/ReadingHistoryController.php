<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateReadingHistoryRequest;
use App\Services\MangaDex\MangaDexCacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReadingHistoryController extends Controller
{
    public function __construct(
        protected MangaDexCacheService $mangaDex
    ) {}

    public function index(Request $request)
    {
        $history = $request->user()->readingHistory()->orderBy('read_at', 'desc')->get();

        $data = $history->map(function ($h) {
            return [
                'manga_id' => $h->manga_id,
                'manga_title' => $h->manga_title,
                'manga_cover_url' => $h->manga_cover_url,
                'chapter_id' => $h->chapter_id,
                'chapter_number' => $h->chapter_number,
                'last_page_read' => $h->last_page_read,
                'read_at' => $h->read_at ? $h->read_at->toIso8601String() : null,
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function update(UpdateReadingHistoryRequest $request)
    {
        $validated = $request->validated();
        $user = $request->user();

        // Get manga details to cache title and cover if not exists
        // (Optimally we could check if it exists in DB first, but for simplicity we fetch it or handle error if it fails)
        try {
            $manga = $this->mangaDex->getMangaById($validated['manga_id']);
            $title = $manga['data']['attributes']['title']['en'] ?? current($manga['data']['attributes']['title']) ?? 'Unknown';
            
            $coverArt = collect($manga['data']['relationships'])->firstWhere('type', 'cover_art');
            $coverFile = $coverArt['attributes']['fileName'] ?? null;
            $coverUrl = $coverFile ? "https://uploads.mangadex.org/covers/{$validated['manga_id']}/{$coverFile}.256.jpg" : null;
        } catch (\Exception $e) {
            // If it fails, we can just leave title/cover as empty or what's already there
            $title = 'Unknown';
            $coverUrl = '';
        }

        $user->readingHistory()->updateOrCreate(
            [
                'manga_id' => $validated['manga_id'],
                'chapter_id' => $validated['chapter_id'],
            ],
            [
                'manga_title' => $title,
                'manga_cover_url' => $coverUrl ?? '',
                'chapter_number' => $validated['chapter_number'],
                'last_page_read' => $validated['last_page_read'] ?? null,
                'read_at' => now(),
            ]
        );

        return response()->json(['message' => 'Reading history updated']);
    }
}
