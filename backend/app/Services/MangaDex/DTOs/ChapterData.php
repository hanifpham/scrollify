<?php

namespace App\Services\MangaDex\DTOs;

use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

class ChapterData implements Arrayable, JsonSerializable
{
    public function __construct(
        public string $id,
        public ?string $manga_id,
        public string $number,
        public ?string $title,
        public array $pages,
        public string $translated_language = 'id'
    ) {}

    /**
     * Create ChapterData instance from MangaDex chapter response and page URLs.
     *
     * @param  array  $rawChapter Raw chapter data from MangaDex
     * @param  array<string>  $pages Array of full page image URLs
     * @param  array  $extra
     * @return self
     */
    public static function fromMangaDexResponse(array $rawChapter, array $pages = [], array $extra = []): self
    {
        $id = $rawChapter['id'] ?? $extra['id'] ?? '';
        $attributes = $rawChapter['attributes'] ?? [];

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
        $translatedLanguage = $attributes['translatedLanguage'] ?? $extra['translated_language'] ?? 'id';

        return new self(
            id: $id,
            manga_id: $mangaId,
            number: $number,
            title: $title,
            pages: $pages,
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
            'pages' => $this->pages,
            'translated_language' => $this->translated_language,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
