<?php

namespace App\Services\MangaDex\DTOs;

class MangaDetail extends MangaSummary
{
    /**
     * @param  array<ChapterSummary>  $chapters
     */
    public function __construct(
        string $id,
        string $title,
        ?string $cover_url,
        string $format,
        string $status,
        ?float $rating,
        ?string $views_label,
        ?array $latest_chapter,
        bool $is_new,
        array $tags,
        public ?string $description,
        public ?string $author,
        public ?string $artist,
        public array $chapters = []
    ) {
        parent::__construct(
            id: $id,
            title: $title,
            cover_url: $cover_url,
            format: $format,
            status: $status,
            rating: $rating,
            views_label: $views_label,
            latest_chapter: $latest_chapter,
            is_new: $is_new,
            tags: $tags
        );
    }

    /**
     * Build MangaDetail instance from raw MangaDex response and extra metadata.
     */
    public static function fromMangaDexResponse(array $rawManga, array $extra = []): self
    {
        // 1. Build base summary
        $summary = parent::fromMangaDexResponse($rawManga, $extra);

        $attributes = $rawManga['attributes'] ?? [];

        // 2. Resolve description
        $description = self::resolveDescription($attributes, $extra);

        // 3. Resolve author & artist from relationships
        $author = self::resolveRelationshipName($rawManga, 'author', $extra['author'] ?? null);
        $artist = self::resolveRelationshipName($rawManga, 'artist', $extra['artist'] ?? null);

        // 4. Resolve chapters
        $chapters = self::resolveChapters($summary->id, $extra);

        return new self(
            id: $summary->id,
            title: $summary->title,
            cover_url: $summary->cover_url,
            format: $summary->format,
            status: $summary->status,
            rating: $summary->rating,
            views_label: $summary->views_label,
            latest_chapter: $summary->latest_chapter,
            is_new: $summary->is_new,
            tags: $summary->tags,
            description: $description,
            author: $author,
            artist: $artist,
            chapters: $chapters
        );
    }

    /**
     * Resolve description from attributes (en -> id -> first available string).
     */
    protected static function resolveDescription(array $attributes, array $extra): ?string
    {
        if (! empty($extra['description'])) {
            return (string) $extra['description'];
        }

        $descMap = $attributes['description'] ?? [];
        if (is_array($descMap)) {
            if (! empty($descMap['en'])) {
                return (string) $descMap['en'];
            }
            if (! empty($descMap['id'])) {
                return (string) $descMap['id'];
            }
            foreach ($descMap as $val) {
                if (! empty($val) && is_string($val)) {
                    return $val;
                }
            }
        } elseif (is_string($descMap) && trim($descMap) !== '') {
            return $descMap;
        }

        return null;
    }

    /**
     * Resolve author or artist name from relationship data.
     */
    protected static function resolveRelationshipName(array $rawManga, string $type, ?string $fallback = null): ?string
    {
        if (! empty($fallback)) {
            return $fallback;
        }

        foreach ($rawManga['relationships'] ?? [] as $rel) {
            if (($rel['type'] ?? '') === $type) {
                $name = $rel['attributes']['name'] ?? null;
                if ($name) {
                    return (string) $name;
                }
            }
        }

        return null;
    }

    /**
     * Resolve chapters list into array of ChapterSummary instances.
     *
     * @return array<ChapterSummary>
     */
    protected static function resolveChapters(string $mangaId, array $extra): array
    {
        $rawChapters = $extra['chapters'] ?? [];
        if (! is_array($rawChapters)) {
            return [];
        }

        $result = [];
        foreach ($rawChapters as $item) {
            if ($item instanceof ChapterSummary) {
                $result[] = $item;
            } elseif (is_array($item)) {
                $result[] = ChapterSummary::fromMangaDexResponse($item, ['manga_id' => $mangaId]);
            }
        }

        return $result;
    }

    public function toArray(): array
    {
        $base = parent::toArray();

        $base['description'] = $this->description;
        $base['author'] = $this->author;
        $base['artist'] = $this->artist;
        $base['chapters'] = array_map(
            static fn (ChapterSummary|array $c): array => $c instanceof ChapterSummary ? $c->toArray() : $c,
            $this->chapters
        );

        return $base;
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
