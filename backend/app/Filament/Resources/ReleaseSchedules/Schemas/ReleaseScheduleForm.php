<?php

namespace App\Filament\Resources\ReleaseSchedules\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Facades\Storage;
use Filament\Schemas\Schema;

class ReleaseScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('manga_id')
                    ->label('ID Manga (MangaDex)')
                    ->prefixIcon('heroicon-o-identification')
                    ->uuid()
                    ->required()
                    ->maxLength(36)
                    ->helperText('UUID manga dari MangaDex'),
                
                TextInput::make('manga_title')
                    ->label('Judul Manga')
                    ->prefixIcon('heroicon-o-book-open')
                    ->required()
                    ->maxLength(255),
                
                Select::make('release_day')
                    ->label('Hari Rilis')
                    ->prefixIcon('heroicon-o-calendar-days')
                    ->options([
                        'monday' => 'Senin',
                        'tuesday' => 'Selasa',
                        'wednesday' => 'Rabu',
                        'thursday' => 'Kamis',
                        'friday' => 'Jumat',
                        'saturday' => 'Sabtu',
                        'sunday' => 'Minggu',
                    ])
                    ->required(),
                
                Toggle::make('is_active')
                    ->label('Aktifkan Jadwal Ini')
                    ->default(true)
                    ->required(),

                Section::make('Gambar Cover')
                    ->schema([
                        Radio::make('image_source')
                            ->label('Sumber Gambar')
                            ->options([
                                'upload' => 'Upload dari Galeri',
                                'url' => 'Pakai URL Langsung',
                            ])
                            ->default('url')
                            ->inline()
                            ->live()
                            ->dehydrated(false),
                            
                        FileUpload::make('image_upload')
                            ->label('Upload Gambar')
                            ->disk('public')
                            ->directory('uploads/schedules')
                            ->image()
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->hidden(fn (Get $get) => $get('image_source') !== 'upload')
                            ->dehydrated(false)
                            ->live()
                            ->afterStateUpdated(function (Set $set, $state) {
                                if ($state) {
                                    $set('manga_cover_url', asset('storage/' . $state));
                                }
                            }),

                        TextInput::make('manga_cover_url')
                            ->label('URL Gambar Cover')
                            ->prefixIcon('heroicon-o-photo')
                            ->url()
                            ->required()
                            ->maxLength(500)
                            ->helperText('Jika memilih upload, URL akan terisi otomatis.')
                            ->readOnly(fn (Get $get) => $get('image_source') === 'upload')
                            ->hidden(fn (Get $get) => $get('image_source') !== 'url'),
                    ]),

                TimePicker::make('release_time')
                    ->label('Jam Rilis (opsional)')
                    ->prefixIcon('heroicon-o-clock')
                    ->seconds(false)
                    ->nullable(),
            ]);
    }
}
