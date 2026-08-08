<?php

return [
    /*
    |--------------------------------------------------------------------------
    | MangaDex API Configuration
    |--------------------------------------------------------------------------
    |
    | Base URL and Cache settings for MangaDex API client.
    | All HTTP requests to MangaDex must use these configuration values.
    |
    */

    'api_url' => env('MANGADEX_API_URL', 'https://api.mangadex.org'),

    'cache_ttl' => (int) env('MANGADEX_CACHE_TTL', 600),

    'format_tags_cache_ttl' => (int) env('MANGADEX_FORMAT_TAGS_CACHE_TTL', 86400),

    'timeout' => (int) env('MANGADEX_TIMEOUT', 15),

    'retry' => [
        'times' => (int) env('MANGADEX_RETRY_TIMES', 2),
        'sleep_ms' => (int) env('MANGADEX_RETRY_SLEEP_MS', 500),
    ],
];
