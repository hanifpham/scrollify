<?php

namespace App\Filament\Resources\Announcements\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Facades\Storage;
use Filament\Schemas\Schema;

class AnnouncementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Judul Pengumuman')
                    ->prefixIcon('heroicon-o-document-text')
                    ->required()
                    ->maxLength(255),
                
                DatePicker::make('published_at')
                    ->label('Tanggal')
                    ->prefixIcon('heroicon-o-calendar')
                    ->required()
                    ->default(now()),
                
                TextInput::make('display_order')
                    ->label('Urutan Tampil')
                    ->prefixIcon('heroicon-o-bars-arrow-up')
                    ->numeric()
                    ->default(0)
                    ->required(),
                
                Toggle::make('is_active')
                    ->label('Aktifkan Pengumuman Ini')
                    ->default(true)
                    ->required(),

                Section::make('Gambar Thumbnail (Opsional)')
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
                            ->directory('uploads/announcements')
                            ->image()
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->hidden(fn (Get $get) => $get('image_source') !== 'upload')
                            ->dehydrated(false)
                            ->live()
                            ->afterStateUpdated(function (Set $set, $state) {
                                if ($state) {
                                    $set('thumbnail_url', asset('storage/' . $state));
                                }
                            }),

                        TextInput::make('thumbnail_url')
                            ->label('URL Gambar Thumbnail')
                            ->prefixIcon('heroicon-o-photo')
                            ->url()
                            ->maxLength(500)
                            ->helperText('Jika memilih upload, URL akan terisi otomatis.')
                            ->readOnly(fn (Get $get) => $get('image_source') === 'upload')
                            ->hidden(fn (Get $get) => $get('image_source') !== 'url'),
                    ]),
            ]);
    }
}
