<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReleaseSchedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $day = $request->query('day');

        $query = ReleaseSchedule::where('is_active', true);

        if ($day) {
            $query->where('release_day', strtolower($day));
        }

        $schedules = $query->get();

        if ($day) {
            $data = [
                strtolower($day) => $schedules->map(function ($s) {
                    return [
                        'manga_id' => $s->manga_id,
                        'manga_title' => $s->manga_title,
                        'manga_cover_url' => $s->manga_cover_url,
                        'release_time' => $s->release_time,
                    ];
                })->toArray()
            ];
        } else {
            $data = $schedules->groupBy('release_day')->map(function ($daySchedules) {
                return $daySchedules->map(function ($s) {
                    return [
                        'manga_id' => $s->manga_id,
                        'manga_title' => $s->manga_title,
                        'manga_cover_url' => $s->manga_cover_url,
                        'release_time' => $s->release_time,
                    ];
                });
            })->toArray();
            
            // Ensure all days are present even if empty
            $allDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            foreach ($allDays as $d) {
                if (!isset($data[$d])) {
                    $data[$d] = [];
                }
            }
        }

        return response()->json(['data' => $data]);
    }
}
