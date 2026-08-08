<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReleaseSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'manga_id',
        'manga_title',
        'manga_cover_url',
        'release_day',
        'release_time',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
