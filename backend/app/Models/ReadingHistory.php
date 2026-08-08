<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReadingHistory extends Model
{
    use HasFactory;

    protected $table = 'reading_history';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'manga_id',
        'manga_title',
        'manga_cover_url',
        'chapter_id',
        'chapter_number',
        'last_page_read',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'last_page_read' => 'integer',
            'read_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
