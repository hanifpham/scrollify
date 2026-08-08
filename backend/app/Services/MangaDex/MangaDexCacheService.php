<?php

namespace App\Services\MangaDex;

use Illuminate\Support\Facades\Cache;

class MangaDexCacheService
{
    public function __construct(
        protected MangaDexClient $client
    ) {}

    /**
     * Search manga with cache.
     *
     * @param  array  $params
     * @return array
     */
    public function searchManga(array $params = []): array
    {
        $cacheKey = 'mangadex:search:' . md5(json_encode($params));
        $ttl = (int) config('mangadex.cache_ttl', 600);

        return Cache::remember($cacheKey, $ttl, fn () => $this->client->searchManga($params));
    }

    /**
     * Get manga by ID with cache.
     *
     * @param  string  $id
     * @param  array  $includes
     * @return array
     */
    public function getMangaById(string $id, array $includes = ['cover_art', 'author', 'artist']): array
    {
        $cacheKey = 'mangadex:manga:' . $id . ':' . md5(json_encode($includes));
        $ttl = (int) config('mangadex.cache_ttl', 600);

        return Cache::remember($cacheKey, $ttl, fn () => $this->client->getMangaById($id, $includes));
    }

    /**
     * Get manga chapter feed with cache.
     *
     * @param  string  $id
     * @param  array  $params
     * @return array
     */
    public function getMangaFeed(string $id, array $params = []): array
    {
        $cacheKey = 'mangadex:feed:' . $id . ':' . md5(json_encode($params));
        $ttl = (int) config('mangadex.cache_ttl', 600);

        return Cache::remember($cacheKey, $ttl, fn () => $this->client->getMangaFeed($id, $params));
    }

    /**
     * Get chapter pages with cache.
     *
     * @param  string  $chapterId
     * @return array
     */
    public function getChapterPages(string $chapterId): array
    {
        $cacheKey = 'mangadex:chapter_pages:' . $chapterId;
        $ttl = (int) config('mangadex.cache_ttl', 600);

        return Cache::remember($cacheKey, $ttl, fn () => $this->client->getChapterPages($chapterId));
    }

    /**
     * Get format tags mapping (Manga, Manhwa, Manhua) with extended 24-hour cache.
     *
     * @return array<string, string>
     */
    public function getFormatTags(): array
    {
        $cacheKey = 'mangadex:format_tags';
        $ttl = (int) config('mangadex.format_tags_cache_ttl', 86400);

        return Cache::remember($cacheKey, $ttl, fn () => $this->client->getFormatTags());
    }

    /**
     * Get manga statistics with cache.
     *
     * @param  array<string>  $mangaIds
     * @return array
     */
    public function getMangaStatistics(array $mangaIds): array
    {
        $sortedIds = $mangaIds;
        sort($sortedIds);
        $cacheKey = 'mangadex:statistics:' . md5(json_encode($sortedIds));
        $ttl = (int) config('mangadex.cache_ttl', 600);

        return Cache::remember($cacheKey, $ttl, fn () => $this->client->getMangaStatistics($mangaIds));
    }

    /**
     * Access the underlying MangaDex client directly if needed.
     */
    public function getClient(): MangaDexClient
    {
        return $this->client;
    }
}
