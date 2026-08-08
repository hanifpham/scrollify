<?php

namespace App\Services\MangaDex\DTOs;

use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

class ChapterSummary implements Arrayable, JsonSerializable
{
    public function __construct(
        public string $id,
        public ?string $manga_id,
        public string $number,
        public ?string $title,
        public ?string $readable_at,
        public string $translated_language = 'id'
    ) {}

    /**
     * Create ChapterSummary instance from MangaDex chapter response array.
     */
    public static function fromMangaDexResponse(array $rawChapter, array $extra = []): self
    {
        $id = $rawChapter['id'] ?? '';
        $attributes = $rawChapter['attributes'] ?? [];

        // Extract manga_id from relationships or extra
        $mangaId = $extra['manga_id'] ?? null;
        if (! $mangaId && ! empty($rawChapter['relationships'])) {
            foreach ($rawChapter['relationships'] as $rel) {
                if (($rel['type'] ?? '') === 'manga') {
                    $mangaId = $rel['id'] ?? null;
                    break;
                }
            }
        }

        $number = (string) ($attributes['chapter'] ?? $extra['number'] ?? '0');
        $title = $attributes['title'] ?? $extra['title'] ?? null;
        $readableAt = $attributes['readableAt'] ?? $attributes['publishAt'] ?? $attributes['createdAt'] ?? $extra['readable_at'] ?? null;
        $translatedLanguage = $attributes['translatedLanguage'] ?? $extra['translated_language'] ?? 'id';

        return new self(
            id: $id,
            manga_id: $mangaId,
            number: $number,
            title: $title,
            readable_at: $readableAt,
            translated_language: $translatedLanguage
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'manga_id' => $this->manga_id,
            'number' => $this->number,
            'title' => $this->title,
            'readable_at' => $this->readable_at,
            'translated_language' => $this->translated_language,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
