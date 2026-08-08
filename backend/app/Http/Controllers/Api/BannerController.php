<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\JsonResponse;

class BannerController extends Controller
{
    /**
     * GET /api/banners
     * Response: { "data": Banner[] }
     */
    public function index(): JsonResponse
    {
        $banners = Banner::where('is_active', true)
            ->orderBy('display_order', 'asc')
            ->get()
            ->map(function (Banner $banner) {
                return [
                    'id' => $banner->id,
                    'title' => $banner->title,
                    'subtitle' => $banner->subtitle,
                    'description' => $banner->description,
                    'image_url' => $banner->image_url,
                    'badge_label' => $banner->badge_label,
                    'link_type' => $banner->link_type,
                    'link_value' => $banner->link_value,
                ];
            });

        return response()->json([
            'data' => $banners,
        ]);
    }
}
