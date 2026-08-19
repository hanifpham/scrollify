<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PopularRequest;
use App\Http\Requests\RecommendationsRequest;
use App\Http\Requests\UpdatesRequest;
use App\Models\MangaView;
use App\Models\ScanlatorMapping;
use App\Exceptions\MangaDexApiException;
use App\Services\MangaDex\DTOs\ChapterData;
use App\Services\MangaDex\DTOs\MangaDetail;
use App\Services\MangaDex\DTOs\MangaSummary;
use App\Services\MangaDex\MangaDexCacheService;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class MangaController extends Controller
{
    public function __construct(
        protected MangaDexCacheService $mangaDex
    ) {}

    /**
     * GET /api/manga/recommendations
     * Query: format (manga|manhwa|manhua, required), limit (default 5)
     * Response: { "data": MangaSummary[] }
     */
    public function recommendations(RecommendationsRequest $request): JsonResponse
    {
        $format = $request->validated('format');
        $limit = (int) $request->validated('limit', 5);

        $languages = match ($format) {
            'manga' => ['ja'],
            'manhwa' => ['ko'],
            'manhua' => ['zh', 'zh-hk'],
            default => ['ja'],
        };

        $response = $this->mangaDex->searchManga([
            'originalLanguage' => $languages,
            'limit' => $limit,
            'order' => ['rating' => 'desc', 'followedCount' => 'desc'],
            'includes' => ['cover_art', 'author', 'artist'],
            'contentRating' => ['safe', 'suggestive'],
            'hasAvailableChapters' => 'true',
        ]);

        $items = $response['data'] ?? [];
        $mangaIds = array_column($items, 'id');

        $stats = ! empty($mangaIds)
            ? ($this->mangaDex->getMangaStatistics($mangaIds)['statistics'] ?? [])
            : [];

        $summaries = array_map(function ($rawManga) use ($stats) {
            $id = $rawManga['id'] ?? '';
            $extra = [];
            if (isset($stats[$id])) {
                $extra['statistics'] = $stats[$id];
            }

            return MangaSummary::fromMangaDexResponse($rawManga, $extra)->toArray();
        }, $items);

        return response()->json([
            'data' => $summaries,
        ]);
    }

    /**
     * GET /api/manga/updates
     * Query: type (project|mirror, required), page (default 1), per_page (default 30)
     * Response: { "data": MangaSummary[], "meta": { "current_page": 1, "last_page": 3, "total": 90 } }
     */
    public function updates(UpdatesRequest $request): JsonResponse
    {
        $type = $request->validated('type');
        $page = (int) $request->validated('page', 1);
        $perPage = (int) $request->validated('per_page', 30);

        // Fetch manga_id from scanlator_mappings filtered by group_type
        $mangaIds = ScanlatorMapping::where('group_type', $type)
            ->orderBy('priority', 'desc')
            ->pluck('manga_id')
            ->unique()
            ->values()
            ->all();

        $total = count($mangaIds);
        $lastPage = (int) max(1, ceil($total / $perPage));

        if ($total === 0 || $page > $lastPage) {
            return response()->json([
                'data' => [],
                'meta' => [
                    'current_page' => $page,
                    'last_page' => $lastPage,
                    'total' => $total,
                ],
            ]);
        }

        $offset = ($page - 1) * $perPage;
        $pageMangaIds = array_slice($mangaIds, $offset, $perPage);

        $response = $this->mangaDex->searchManga([
            'ids' => $pageMangaIds,
            'limit' => count($pageMangaIds),
            'includes' => ['cover_art', 'author', 'artist'],
            'contentRating' => ['safe', 'suggestive'],
        ]);

        $items = $response['data'] ?? [];
        $fetchedIds = array_column($items, 'id');

        $stats = ! empty($fetchedIds)
            ? ($this->mangaDex->getMangaStatistics($fetchedIds)['statistics'] ?? [])
            : [];

        $summaries = array_map(function ($rawManga) use ($stats) {
            $id = $rawManga['id'] ?? '';
            $extra = [];
            if (isset($stats[$id])) {
                $extra['statistics'] = $stats[$id];
            }

            return MangaSummary::fromMangaDexResponse($rawManga, $extra)->toArray();
        }, $items);

        return response()->json([
            'data' => $summaries,
            'meta' => [
                'current_page' => $page,
                'last_page' => $lastPage,
                'total' => $total,
            ],
        ]);
    }

    /**
     * GET /api/manga/popular
     * Query: period (daily|weekly|all, required), limit (default 5)
     * Response: { "data": MangaSummary[] }
     */
    public function popular(PopularRequest $request): JsonResponse
    {
        $period = $request->validated('period');
        $limit = (int) $request->validated('limit', 5);

        $query = MangaView::query();
        if ($period === 'daily') {
            $query->where('viewed_at', '>=', Carbon::now()->subDay());
        } elseif ($period === 'weekly') {
            $query->where('viewed_at', '>=', Carbon::now()->subDays(7));
        }

        $popularViews = $query->select('manga_id', DB::raw('COUNT(*) as total_views'))
            ->groupBy('manga_id')
            ->orderByDesc('total_views')
            ->limit($limit)
            ->get();

        if ($popularViews->isEmpty()) {
            return response()->json([
                'data' => [],
            ]);
        }

        $mangaIds = $popularViews->pluck('manga_id')->all();
        $viewCounts = $popularViews->pluck('total_views', 'manga_id')->all();

        $response = $this->mangaDex->searchManga([
            'ids' => $mangaIds,
            'limit' => count($mangaIds),
            'includes' => ['cover_art', 'author', 'artist'],
            'contentRating' => ['safe', 'suggestive'],
        ]);

        $items = $response['data'] ?? [];
        $itemsKeyed = [];
        foreach ($items as $raw) {
            if (isset($raw['id'])) {
                $itemsKeyed[$raw['id']] = $raw;
            }
        }

        $stats = ! empty($mangaIds)
            ? ($this->mangaDex->getMangaStatistics($mangaIds)['statistics'] ?? [])
            : [];

        // Preserve ranking order from $popularViews
        $summaries = [];
        foreach ($mangaIds as $id) {
            if (isset($itemsKeyed[$id])) {
                $rawManga = $itemsKeyed[$id];
                $extra = [
                    'views' => $viewCounts[$id] ?? null,
                ];
                if (isset($stats[$id])) {
                    $extra['statistics'] = $stats[$id];
                }

                $summaries[] = MangaSummary::fromMangaDexResponse($rawManga, $extra)->toArray();
            }
        }

        return response()->json([
            'data' => $summaries,
        ]);
    }

    /**
     * GET /api/manga/{id}
     * Response: { "data": MangaDetail }
     */
    public function show(string $id): JsonResponse
    {
        if (!Str::isUuid($id)) {
            return response()->json([
                'message' => 'Invalid manga ID format.',
                'errors' => ['id' => ['The manga ID must be a valid UUID.']]
            ], 422);
        }

        try {
            $mangaResponse = $this->mangaDex->getMangaById($id, ['cover_art', 'author', 'artist']);
            $mangaData = $mangaResponse['data'] ?? null;

            if (!$mangaData) {
                return response()->json(['message' => 'Manga not found'], 404);
            }

            $feedResponse = $this->mangaDex->getMangaFeed($id, [
                'limit' => 500,
                'translatedLanguage' => ['id', 'en'],
                'order' => ['readableAt' => 'desc'],
            ]);
            $chapters = $feedResponse['data'] ?? [];

            $stats = $this->mangaDex->getMangaStatistics([$id])['statistics'] ?? [];
            $extra = [];
            if (isset($stats[$id])) {
                $extra['statistics'] = $stats[$id];
            }
            $extra['chapters'] = $chapters;

            $mangaDetail = MangaDetail::fromMangaDexResponse($mangaData, $extra);

            return response()->json([
                'data' => $mangaDetail->toArray(),
            ]);

        } catch (MangaDexApiException $e) {
            if ($e->getStatusCode() === 404) {
                return response()->json(['message' => 'Manga not found'], 404);
            }
            return response()->json(['message' => 'Failed to fetch manga data'], 500);
        }
    }

    /**
     * GET /api/manga/{id}/chapters/{chapterId}
     * Response: { "data": ChapterData }
     */
    public function chapterPages(string $id, string $chapterId): JsonResponse
    {
        if (!Str::isUuid($id) || !Str::isUuid($chapterId)) {
            $errors = [];
            if (!Str::isUuid($id)) {
                $errors['id'] = ['The manga ID must be a valid UUID.'];
            }
            if (!Str::isUuid($chapterId)) {
                $errors['chapterId'] = ['The chapter ID must be a valid UUID.'];
            }
            return response()->json([
                'message' => 'Invalid ID format.',
                'errors' => $errors
            ], 422);
        }

        try {
            // Validasi chapter milik manga lewat chapter detail API
            $chapterResponse = $this->mangaDex->getChapterById($chapterId, ['manga']);
            $chapterMetadata = $chapterResponse['data'] ?? null;

            if (!$chapterMetadata) {
                return response()->json(['message' => 'Chapter not found.'], 404);
            }

            // Cari relasi manga
            $relatedMangaId = null;
            foreach ($chapterMetadata['relationships'] ?? [] as $rel) {
                if (($rel['type'] ?? '') === 'manga') {
                    $relatedMangaId = $rel['id'] ?? null;
                    break;
                }
            }

            if (strtolower((string)$relatedMangaId) !== strtolower($id)) {
                return response()->json(['message' => 'Chapter not found for this manga.'], 404);
            }

            try {
                $pagesResponse = $this->mangaDex->getChapterPages($chapterId);
                $pages = $pagesResponse['pages'] ?? [];
            } catch (MangaDexApiException $e) {
                if ($e->getStatusCode() === 404) {
                    $pages = [];
                } else {
                    throw $e;
                }
            }

            $chapterData = ChapterData::fromMangaDexResponse($chapterMetadata, $pages, ['manga_id' => $id]);

            return response()->json([
                'data' => $chapterData->toArray(),
            ]);

        } catch (MangaDexApiException $e) {
            if ($e->getStatusCode() === 404) {
                return response()->json(['message' => 'Chapter pages not found'], 404);
            }
            return response()->json(['message' => 'Failed to fetch chapter pages'], 500);
        }
    }
}
