<?php

namespace App\Filament\Resources\Announcements\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AnnouncementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->prefixIcon('heroicon-o-document-text')
                    ->required()
                    ->maxLength(255),
                TextInput::make('thumbnail_url')
                    ->label('Thumbnail URL')
                    ->prefixIcon('heroicon-o-photo')
                    ->url()
                    ->maxLength(500),
                DatePicker::make('published_at')
                    ->prefixIcon('heroicon-o-calendar')
                    ->required()
                    ->default(now()),
                Toggle::make('is_active')
                    ->default(true)
                    ->required(),
                TextInput::make('display_order')
                    ->prefixIcon('heroicon-o-bars-arrow-up')
                    ->numeric()
                    ->default(0)
                    ->required(),
            ]);
    }
}
