<?php

namespace App\Filament\Widgets;

use App\Models\Announcement;
use App\Models\Banner;
use App\Models\ReleaseSchedule;
use App\Models\ScanlatorMapping;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Banners Aktif', Banner::where('is_active', true)->count())
                ->description('Banner carousel beranda')
                ->descriptionIcon('heroicon-o-photo')
                ->color('primary'),

            Stat::make('Announcements Aktif', Announcement::where('is_active', true)->count())
                ->description('Pengumuman beranda')
                ->descriptionIcon('heroicon-o-megaphone')
                ->color('primary'),

            Stat::make('Scanlator Mappings', ScanlatorMapping::count())
                ->description('Total pemetaan grup scanlator')
                ->descriptionIcon('heroicon-o-link')
                ->color('primary'),

            Stat::make('Release Schedules Aktif', ReleaseSchedule::where('is_active', true)->count())
                ->description('Jadwal rilis mingguan aktif')
                ->descriptionIcon('heroicon-o-calendar-days')
                ->color('primary'),
        ];
    }
}
