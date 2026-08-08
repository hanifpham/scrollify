<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScanlatorMapping extends Model
{
    use HasFactory;

    protected $fillable = [
        'manga_id',
        'scanlation_group_id',
        'group_type',
        'priority',
    ];

    protected function casts(): array
    {
        return [
            'priority' => 'integer',
        ];
    }
}
