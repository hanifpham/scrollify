<?php

namespace App\Filament\Resources\ReleaseSchedules\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ReleaseScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('manga_id')
                    ->label('Manga UUID')
                    ->prefixIcon('heroicon-o-identification')
                    ->uuid()
                    ->required()
                    ->maxLength(36)
                    ->helperText('MangaDex Manga UUID'),
                TextInput::make('manga_title')
                    ->label('Manga Title')
                    ->prefixIcon('heroicon-o-book-open')
                    ->required()
                    ->maxLength(255),
                TextInput::make('manga_cover_url')
                    ->label('Manga Cover URL')
                    ->prefixIcon('heroicon-o-photo')
                    ->url()
                    ->required()
                    ->maxLength(500),
                Select::make('release_day')
                    ->prefixIcon('heroicon-o-calendar-days')
                    ->options([
                        'monday' => 'Monday',
                        'tuesday' => 'Tuesday',
                        'wednesday' => 'Wednesday',
                        'thursday' => 'Thursday',
                        'friday' => 'Friday',
                        'saturday' => 'Saturday',
                        'sunday' => 'Sunday',
                    ])
                    ->required(),
                TimePicker::make('release_time')
                    ->prefixIcon('heroicon-o-clock')
                    ->seconds(false)
                    ->nullable(),
                Toggle::make('is_active')
                    ->default(true)
                    ->required(),
            ]);
    }
}
