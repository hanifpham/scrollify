<?php

namespace App\Services\MangaDex;

use App\Exceptions\MangaDexApiException;
use Illuminate\Support\Facades\Http;
use Throwable;

class MangaDexClient
{
    /**
     * Search manga with arbitrary query parameters.
     *
     * @param  array  $params (e.g. title, includedTags, order, limit, offset, contentRating, includes)
     * @return array Raw MangaDex API response
     *
     * @throws MangaDexApiException
     */
    public function searchManga(array $params = []): array
    {
        return $this->get('/manga', $params);
    }

    /**
     * Get single manga by ID with includes (cover_art, author, artist by default).
     *
     * @param  string  $id UUID string from MangaDex
     * @param  array  $includes
     * @return array Raw MangaDex API response
     *
     * @throws MangaDexApiException
     */
    public function getMangaById(string $id, array $includes = ['cover_art', 'author', 'artist']): array
    {
        return $this->get("/manga/{$id}", [
            'includes' => $includes,
        ]);
    }

    /**
     * Get chapter feed for a manga.
     *
     * @param  string  $id Manga UUID
     * @param  array  $params (e.g. limit, offset, translatedLanguage, order, includes)
     * @return array Raw MangaDex API response
     *
     * @throws MangaDexApiException
     */
    public function getMangaFeed(string $id, array $params = []): array
    {
        return $this->get("/manga/{$id}/feed", $params);
    }

    /**
     * Get single chapter by ID with includes (e.g. manga).
     *
     * @param  string  $id Chapter UUID
     * @param  array  $includes
     * @return array Raw MangaDex API response
     *
     * @throws MangaDexApiException
     */
    public function getChapterById(string $id, array $includes = ['manga']): array
    {
        return $this->get("/chapter/{$id}", [
            'includes' => $includes,
        ]);
    }

    /**
     * Get chapter image pages from MangaDex @Home server and build full image URLs.
     *
     * @param  string  $chapterId Chapter UUID
     * @return array Constructed page URLs and metadata
     *
     * @throws MangaDexApiException
     */
    public function getChapterPages(string $chapterId): array
    {
        // Check chapter status first to avoid silent 404s for unavailable chapters
        $chapterInfo = $this->getChapterById($chapterId);
        $isUnavailable = $chapterInfo['data']['attributes']['isUnavailable'] ?? false;
        
        if ($isUnavailable) {
            throw new \App\Exceptions\ChapterUnavailableException("Chapter ini tidak tersedia dari MangaDex (kemungkinan ditarik oleh penerbit resmi).");
        }

        $response = $this->get("/at-home/server/{$chapterId}");

        $baseUrl = $response['baseUrl'] ?? '';
        $chapter = $response['chapter'] ?? [];
        $hash = $chapter['hash'] ?? '';
        $dataFiles = $chapter['data'] ?? [];
        $dataSaverFiles = $chapter['dataSaver'] ?? [];

        $pages = array_map(
            static fn (string $filename): string => "{$baseUrl}/data/{$hash}/{$filename}",
            $dataFiles
        );

        $pagesDataSaver = array_map(
            static fn (string $filename): string => "{$baseUrl}/data-saver/{$hash}/{$filename}",
            $dataSaverFiles
        );

        return [
            'result' => $response['result'] ?? 'ok',
            'baseUrl' => $baseUrl,
            'hash' => $hash,
            'pages' => $pages,
            'pages_data_saver' => $pagesDataSaver,
        ];
    }

    /**
     * Get all format tags and return mapping of tag name to UUID.
     *
     * @return array<string, string> Array of [name => tag_id]
     *
     * @throws MangaDexApiException
     */
    public function getFormatTags(): array
    {
        $response = $this->get('/manga/tag');
        $tags = [];

        foreach ($response['data'] ?? [] as $tag) {
            $group = $tag['attributes']['group'] ?? null;
            if ($group === 'format') {
                $name = $tag['attributes']['name']['en'] ?? null;
                $tagId = $tag['id'] ?? null;

                if ($name && $tagId) {
                    $tags[$name] = $tagId;
                    $tags[strtolower($name)] = $tagId;
                }
            }
        }

        return $tags;
    }

    /**
     * Get statistics (ratings, follows, comments) for one or more manga.
     *
     * @param  array<string>  $mangaIds Array of manga UUIDs
     * @return array Raw statistics response
     *
     * @throws MangaDexApiException
     */
    public function getMangaStatistics(array $mangaIds): array
    {
        return $this->get('/statistics/manga', [
            'manga' => $mangaIds,
        ]);
    }

    /**
     * Execute GET request against MangaDex API with error handling.
     *
     * @param  string  $endpoint
     * @param  array  $query
     * @return array
     *
     * @throws MangaDexApiException
     */
    protected function get(string $endpoint, array $query = []): array
    {
        $baseUrl = config('mangadex.api_url');
        $timeout = (int) config('mangadex.timeout', 15);

        try {
            $response = Http::baseUrl($baseUrl)
                ->timeout($timeout)
                ->withoutVerifying()
                ->withHeaders([
                    'User-Agent' => 'Scrollify/1.0 (https://github.com/hanifpham/scrollify)',
                    'Accept' => 'application/json',
                ])
                ->get($endpoint, $query);

            if ($response->failed()) {
                $statusCode = $response->status();
                $body = $response->json() ?? [];
                $errorMessage = $this->extractErrorMessage($body, $statusCode, $endpoint);

                throw new MangaDexApiException($errorMessage, $statusCode, $body);
            }

            return $response->json() ?? [];
        } catch (MangaDexApiException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw new MangaDexApiException(
                "Failed to communicate with MangaDex API ({$endpoint}): " . $e->getMessage(),
                0,
                null,
                $e
            );
        }
    }

    /**
     * Extract user-friendly error message from MangaDex error response body.
     */
    protected function extractErrorMessage(array $body, int $statusCode, string $endpoint): string
    {
        if (! empty($body['errors']) && is_array($body['errors'])) {
            $first = $body['errors'][0];
            $detail = $first['detail'] ?? $first['title'] ?? null;
            if ($detail) {
                return "MangaDex API Error ({$statusCode}): {$detail}";
            }
        }

        return "MangaDex API request failed for {$endpoint} with status {$statusCode}";
    }
}
