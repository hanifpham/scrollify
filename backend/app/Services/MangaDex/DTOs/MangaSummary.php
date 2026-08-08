<?php

namespace App\Services\MangaDex\DTOs;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;
use Throwable;

class MangaSummary implements Arrayable, JsonSerializable
{
    public function __construct(
        public string $id,
        public string $title,
        public ?string $cover_url,
        public string $format,
        public string $status,
        public ?float $rating,
        public ?string $views_label,
        public ?array $latest_chapter,
        public bool $is_new,
        public array $tags
    ) {}

    /**
     * Build MangaSummary instance from raw MangaDex manga response and optional extras.
     *
     * @param  array  $rawManga Raw manga data from MangaDex API
     * @param  array  $extra Optional pre-computed data (statistics, views, cover_url, latest_chapter, etc.)
     * @return self
     */
    public static function fromMangaDexResponse(array $rawManga, array $extra = []): self
    {
        $id = $rawManga['id'] ?? $extra['id'] ?? '';
        $attributes = $rawManga['attributes'] ?? [];

        // 1. Title Resolution (en -> id -> ja-ro -> altTitles -> fallback)
        $title = self::resolveTitle($attributes, $extra);

        // 2. Format mapping from originalLanguage
        $originalLanguage = $attributes['originalLanguage'] ?? $extra['original_language'] ?? null;
        $format = self::mapFormat($originalLanguage);

        // 3. Status
        $status = strtolower($attributes['status'] ?? $extra['status'] ?? 'ongoing');

        // 4. Cover URL resolution
        $coverUrl = self::resolveCoverUrl($id, $rawManga, $extra);

        // 5. Rating resolution
        $rating = self::resolveRating($rawManga, $extra);

        // 6. Views Label resolution
        $viewsLabel = self::resolveViewsLabel($rawManga, $extra);

        // 7. Latest Chapter & Is New
        $latestChapter = self::resolveLatestChapter($rawManga, $extra);
        $isNew = self::resolveIsNew($latestChapter, $extra);

        // 8. Tags (genre & theme only, lowercase strings)
        $tags = self::resolveTags($attributes);

        return new self(
            id: $id,
            title: $title,
            cover_url: $coverUrl,
            format: $format,
            status: $status,
            rating: $rating,
            views_label: $viewsLabel,
            latest_chapter: $latestChapter,
            is_new: $isNew,
            tags: $tags
        );
    }

    /**
     * Map MangaDex originalLanguage to Scrollify format.
     */
    public static function mapFormat(?string $originalLanguage): string
    {
        $lang = strtolower(trim((string) $originalLanguage));

        return match ($lang) {
            'ja', 'ja-ro' => 'manga',
            'ko', 'ko-ro' => 'manhwa',
            'zh', 'zh-ro', 'zh-hk' => 'manhua',
            default => 'other',
        };
    }

    /**
     * Format a numeric view/follow count into short human-readable label (e.g. "2.5M views", "850K views").
     */
    public static function formatViewsLabel(int|float|null $views): ?string
    {
        if ($views === null) {
            return null;
        }

        $views = (int) $views;
        if ($views >= 1_000_000) {
            $val = round($views / 1_000_000, 1);
            $formatted = ($val == (int) $val) ? (string) ((int) $val) : (string) $val;

            return "{$formatted}M views";
        }

        if ($views >= 1_000) {
            $val = round($views / 1_000, 1);
            $formatted = ($val == (int) $val) ? (string) ((int) $val) : (string) $val;

            return "{$formatted}K views";
        }

        return "{$views} views";
    }

    /**
     * Resolve title with priority: en -> id -> altTitles -> first available string.
     */
    protected static function resolveTitle(array $attributes, array $extra): string
    {
        if (! empty($extra['title'])) {
            return (string) $extra['title'];
        }

        $titleMap = $attributes['title'] ?? [];
        if (is_array($titleMap)) {
            if (! empty($titleMap['en'])) {
                return (string) $titleMap['en'];
            }
            if (! empty($titleMap['id'])) {
                return (string) $titleMap['id'];
            }
            if (! empty($titleMap['ja-ro'])) {
                return (string) $titleMap['ja-ro'];
            }
            foreach ($titleMap as $val) {
                if (! empty($val) && is_string($val)) {
                    return $val;
                }
            }
        } elseif (is_string($titleMap) && trim($titleMap) !== '') {
            return $titleMap;
        }

        // Search altTitles
        $altTitles = $attributes['altTitles'] ?? [];
        if (is_array($altTitles)) {
            // Check preferred languages first
            foreach (['en', 'id', 'ja-ro', 'ko-ro', 'zh-ro', 'ja'] as $langKey) {
                foreach ($altTitles as $alt) {
                    if (is_array($alt) && ! empty($alt[$langKey]) && is_string($alt[$langKey])) {
                        return $alt[$langKey];
                    }
                }
            }

            // Fallback to any first string in altTitles
            foreach ($altTitles as $alt) {
                if (is_array($alt)) {
                    foreach ($alt as $val) {
                        if (! empty($val) && is_string($val)) {
                            return $val;
                        }
                    }
                } elseif (is_string($alt) && trim($alt) !== '') {
                    return $alt;
                }
            }
        }

        return 'Untitled Manga';
    }

    /**
     * Resolve cover image full URL from relationships cover_art fileName.
     */
    protected static function resolveCoverUrl(string $mangaId, array $rawManga, array $extra): ?string
    {
        if (! empty($extra['cover_url'])) {
            return $extra['cover_url'];
        }

        $fileName = null;
        foreach ($rawManga['relationships'] ?? [] as $rel) {
            if (($rel['type'] ?? '') === 'cover_art') {
                $fileName = $rel['attributes']['fileName'] ?? $extra['cover_file_name'] ?? null;
                break;
            }
        }

        if ($fileName && $mangaId) {
            return "https://uploads.mangadex.org/covers/{$mangaId}/{$fileName}";
        }

        return null;
    }

    /**
     * Resolve rating from statistics or extra.
     */
    protected static function resolveRating(array $rawManga, array $extra): ?float
    {
        if (isset($extra['rating']) && is_numeric($extra['rating'])) {
            return round((float) $extra['rating'], 1);
        }

        $stats = $extra['statistics'] ?? $rawManga['statistics'] ?? null;
        if (is_array($stats)) {
            $bayesian = $stats['rating']['bayesian'] ?? $stats['rating']['mean'] ?? null;
            if (is_numeric($bayesian)) {
                return round((float) $bayesian, 1);
            }
        }

        return null;
    }

    /**
     * Resolve formatted views label.
     */
    protected static function resolveViewsLabel(array $rawManga, array $extra): ?string
    {
        if (! empty($extra['views_label'])) {
            return $extra['views_label'];
        }

        if (isset($extra['views']) && is_numeric($extra['views'])) {
            return self::formatViewsLabel((int) $extra['views']);
        }

        $stats = $extra['statistics'] ?? $rawManga['statistics'] ?? null;
        if (is_array($stats)) {
            $viewsCount = $stats['views'] ?? $stats['follows'] ?? null;
            if (is_numeric($viewsCount)) {
                return self::formatViewsLabel((int) $viewsCount);
            }
        }

        return null;
    }

    /**
     * Resolve latest chapter array.
     */
    protected static function resolveLatestChapter(array $rawManga, array $extra): ?array
    {
        if (! empty($extra['latest_chapter']) && is_array($extra['latest_chapter'])) {
            return [
                'id' => (string) ($extra['latest_chapter']['id'] ?? ''),
                'number' => (string) ($extra['latest_chapter']['number'] ?? $extra['latest_chapter']['chapter'] ?? '0'),
                'readable_at' => $extra['latest_chapter']['readable_at'] ?? $extra['latest_chapter']['readableAt'] ?? null,
            ];
        }

        if (! empty($extra['latest_chapter_detail']) && is_array($extra['latest_chapter_detail'])) {
            $chap = $extra['latest_chapter_detail'];
            $attrs = $chap['attributes'] ?? [];

            return [
                'id' => (string) ($chap['id'] ?? ''),
                'number' => (string) ($attrs['chapter'] ?? '0'),
                'readable_at' => $attrs['readableAt'] ?? $attrs['publishAt'] ?? $attrs['createdAt'] ?? null,
            ];
        }

        return null;
    }

    /**
     * Resolve whether the manga has a new chapter within the last 7 days.
     */
    protected static function resolveIsNew(?array $latestChapter, array $extra): bool
    {
        if (isset($extra['is_new'])) {
            return (bool) $extra['is_new'];
        }

        if (! empty($latestChapter['readable_at'])) {
            try {
                $readableAt = Carbon::parse($latestChapter['readable_at']);

                return $readableAt->greaterThanOrEqualTo(Carbon::now()->subDays(7));
            } catch (Throwable) {
                return false;
            }
        }

        return false;
    }

    /**
     * Resolve tags filtering only 'genre' and 'theme' groups.
     *
     * @return array<string>
     */
    protected static function resolveTags(array $attributes): array
    {
        $tags = [];
        foreach ($attributes['tags'] ?? [] as $tag) {
            $group = $tag['attributes']['group'] ?? '';
            if (in_array($group, ['genre', 'theme'], true)) {
                $name = $tag['attributes']['name']['en'] ?? null;
                if ($name) {
                    $tags[] = strtolower($name);
                }
            }
        }

        return array_values(array_unique($tags));
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'cover_url' => $this->cover_url,
            'format' => $this->format,
            'status' => $this->status,
            'rating' => $this->rating,
            'views_label' => $this->views_label,
            'latest_chapter' => $this->latest_chapter,
            'is_new' => $this->is_new,
            'tags' => $this->tags,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
