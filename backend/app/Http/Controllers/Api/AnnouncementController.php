<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\JsonResponse;

class AnnouncementController extends Controller
{
    /**
     * GET /api/announcements
     * Response: { "data": Announcement[] }
     */
    public function index(): JsonResponse
    {
        $announcements = Announcement::where('is_active', true)
            ->orderBy('display_order', 'asc')
            ->orderByDesc('published_at')
            ->get()
            ->map(function (Announcement $announcement) {
                return [
                    'id' => $announcement->id,
                    'title' => $announcement->title,
                    'thumbnail_url' => $announcement->thumbnail_url,
                    'published_at' => $announcement->published_at ? $announcement->published_at->format('Y-m-d') : null,
                ];
            });

        return response()->json([
            'data' => $announcements,
        ]);
    }
}
