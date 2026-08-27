<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookmarkRequest;
use App\Models\Bookmark;
use App\Services\MangaDex\MangaDexCacheService;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    public function __construct(
        protected MangaDexCacheService $mangaDex
    ) {}

    public function index(Request $request)
    {
        $bookmarks = $request->user()->bookmarks()->orderBy('created_at', 'desc')->get();

        // Try to update latest info from MangaDex (best effort)
        if ($bookmarks->isNotEmpty()) {
            try {
                $mangaIds = $bookmarks->pluck('manga_id')->toArray();
                // We can't fetch all at once easily with getMangaById without a loop or search,
                // But the contract says "digabung info terbaru dari MangaDexCacheService". 
                // Let's use searchManga with ids parameter if we want batch, or just fetch one by one?
                // Actually, MangaDex allows fetching multiple IDs via `ids[]` in search.
                $mangaData = $this->mangaDex->searchManga([
                    'ids' => $mangaIds,
                    'limit' => count($mangaIds),
                    'includes' => ['cover_art', 'author', 'artist']
                ]);

                $mangaDict = [];
                foreach ($mangaData['data'] ?? [] as $manga) {
                    $mangaDict[$manga['id']] = $manga;
                }

                $bookmarks->transform(function ($bookmark) use ($mangaDict) {
                    if (isset($mangaDict[$bookmark->manga_id])) {
                        $manga = $mangaDict[$bookmark->manga_id];
                        $title = $manga['attributes']['title']['en'] ?? current($manga['attributes']['title']) ?? 'Unknown';
                        
                        $coverArt = collect($manga['relationships'])->firstWhere('type', 'cover_art');
                        $coverFile = $coverArt['attributes']['fileName'] ?? null;
                        $coverUrl = $coverFile ? "https://uploads.mangadex.org/covers/{$manga['id']}/{$coverFile}.256.jpg" : null;

                        $bookmark->manga_title = $title;
                        if ($coverUrl) {
                            $bookmark->manga_cover_url = $coverUrl;
                        }
                    }
                    return $bookmark;
                });
            } catch (\Exception $e) {
                // Ignore MangaDex errors, just use the cached data in db
            }
        }

        // Return mapped to MangaSummary as per API_CONTRACT
        $data = $bookmarks->map(function ($b) {
            return [
                'id' => $b->manga_id,
                'title' => $b->manga_title,
                'cover_url' => $b->manga_cover_url,
                'description' => '', // Bookmark list might not need full description
                'status' => 'ongoing', // Dummy or we can omit what we don't have
                'tags' => [],
                'is_new' => false,
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function store(StoreBookmarkRequest $request)
    {
        $mangaId = $request->validated('manga_id');
        $user = $request->user();

        // Check if already bookmarked
        if ($user->bookmarks()->where('manga_id', $mangaId)->exists()) {
            return response()->json(['message' => 'Conflict'], 409);
        }

        try {
            $manga = $this->mangaDex->getMangaById($mangaId);
            $title = $manga['data']['attributes']['title']['en'] ?? current($manga['data']['attributes']['title']) ?? 'Unknown';
            
            $coverArt = collect($manga['data']['relationships'])->firstWhere('type', 'cover_art');
            $coverFile = $coverArt['attributes']['fileName'] ?? null;
            $coverUrl = $coverFile ? "https://uploads.mangadex.org/covers/{$mangaId}/{$coverFile}.256.jpg" : null;
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid manga_id or MangaDex is down', 'errors' => ['manga_id' => ['Manga not found.']]], 422);
        }

        $bookmark = $user->bookmarks()->create([
            'manga_id' => $mangaId,
            'manga_title' => $title,
            'manga_cover_url' => $coverUrl ?? '',
        ]);

        return response()->json([
            'data' => [
                'id' => $bookmark->id,
                'manga_id' => $bookmark->manga_id,
                'created_at' => $bookmark->created_at->toIso8601String(),
            ]
        ], 201);
    }

    public function destroy(Request $request, string $mangaId)
    {
        $bookmark = $request->user()->bookmarks()->where('manga_id', $mangaId)->first();

        if (! $bookmark) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $bookmark->delete();

        return response()->noContent();
    }
}
