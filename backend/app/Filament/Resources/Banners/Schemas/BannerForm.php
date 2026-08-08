<?php

namespace App\Filament\Resources\Banners\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class BannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->prefixIcon('heroicon-o-document-text')
                    ->required()
                    ->maxLength(255),
                TextInput::make('subtitle')
                    ->prefixIcon('heroicon-o-chat-bubble-bottom-center-text')
                    ->maxLength(255),
                Textarea::make('description')
                    ->rows(3)
                    ->columnSpanFull(),
                TextInput::make('image_url')
                    ->label('Image URL')
                    ->prefixIcon('heroicon-o-photo')
                    ->url()
                    ->required()
                    ->maxLength(500),
                TextInput::make('manga_id')
                    ->label('Manga UUID')
                    ->prefixIcon('heroicon-o-identification')
                    ->uuid()
                    ->maxLength(36)
                    ->helperText('MangaDex UUID (optional if banner is general)'),
                TextInput::make('badge_label')
                    ->prefixIcon('heroicon-o-tag')
                    ->maxLength(50)
                    ->placeholder('e.g. ROMANCE, NEW, HOT'),
                Select::make('link_type')
                    ->prefixIcon('heroicon-o-link')
                    ->options([
                        'manga' => 'Manga',
                        'external' => 'External URL',
                        'none' => 'None',
                    ])
                    ->default('manga')
                    ->required(),
                TextInput::make('link_value')
                    ->prefixIcon('heroicon-o-arrow-top-right-on-square')
                    ->maxLength(500)
                    ->helperText('MangaDex UUID or external URL'),
                TextInput::make('display_order')
                    ->prefixIcon('heroicon-o-bars-arrow-up')
                    ->numeric()
                    ->default(0)
                    ->required(),
                Toggle::make('is_active')
                    ->default(true)
                    ->required(),
                DateTimePicker::make('starts_at')
                    ->prefixIcon('heroicon-o-calendar')
                    ->nullable(),
                DateTimePicker::make('ends_at')
                    ->prefixIcon('heroicon-o-calendar')
                    ->nullable(),
            ]);
    }
}
