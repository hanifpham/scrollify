<?php

namespace App\Filament\Resources\Banners\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Facades\Storage;
use Filament\Schemas\Schema;

class BannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Judul Banner')
                    ->prefixIcon('heroicon-o-document-text')
                    ->required()
                    ->maxLength(255),
                
                Select::make('link_type')
                    ->label('Saat Diklik, Menuju Ke')
                    ->prefixIcon('heroicon-o-link')
                    ->options([
                        'manga' => 'Halaman Manga',
                        'external' => 'Link Luar',
                        'none' => 'Tidak Ada Aksi',
                    ])
                    ->default('manga')
                    ->required(),
                
                TextInput::make('display_order')
                    ->label('Urutan Tampil')
                    ->prefixIcon('heroicon-o-bars-arrow-up')
                    ->numeric()
                    ->default(0)
                    ->required()
                    ->helperText('Angka kecil tampil duluan'),
                
                Toggle::make('is_active')
                    ->label('Aktifkan Banner Ini')
                    ->default(true)
                    ->required(),

                Section::make('Gambar Banner')
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
                            ->directory('uploads/banners')
                            ->image()
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->hidden(fn (Get $get) => $get('image_source') !== 'upload')
                            ->dehydrated(false)
                            ->live()
                            ->afterStateUpdated(function (Set $set, $state) {
                                if ($state) {
                                    $set('image_url', asset('storage/' . $state));
                                }
                            }),

                        TextInput::make('image_url')
                            ->label('URL Gambar')
                            ->prefixIcon('heroicon-o-photo')
                            ->url()
                            ->required()
                            ->maxLength(500)
                            ->helperText('Jika memilih upload, URL akan terisi otomatis.')
                            ->readOnly(fn (Get $get) => $get('image_source') === 'upload')
                            ->hidden(fn (Get $get) => $get('image_source') !== 'url'),
                    ]),

                TextInput::make('subtitle')
                    ->label('Subjudul')
                    ->prefixIcon('heroicon-o-chat-bubble-bottom-center-text')
                    ->maxLength(255),
                
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->rows(3)
                    ->columnSpanFull(),
                
                TextInput::make('manga_id')
                    ->label('ID Manga (MangaDex)')
                    ->prefixIcon('heroicon-o-identification')
                    ->uuid()
                    ->maxLength(36)
                    ->helperText('UUID manga dari MangaDex, contoh: 32d76d19-8a05-4db0-9fc2-e0b0648fe9d0. Kosongkan kalau banner ini bukan promosi manga tertentu.'),
                
                TextInput::make('badge_label')
                    ->label('Label Kecil')
                    ->prefixIcon('heroicon-o-tag')
                    ->maxLength(50)
                    ->placeholder('Contoh: ROMANCE, BARU, HOT'),
                
                TextInput::make('link_value')
                    ->label('Tujuan (ID Manga atau URL)')
                    ->prefixIcon('heroicon-o-arrow-top-right-on-square')
                    ->maxLength(500)
                    ->helperText('Isi ID manga (kalau di atas pilih \'Halaman Manga\') atau URL lengkap (kalau pilih \'Link Luar\')'),
                
                DateTimePicker::make('starts_at')
                    ->label('Mulai Ditampilkan (opsional)')
                    ->prefixIcon('heroicon-o-calendar')
                    ->nullable(),
                
                DateTimePicker::make('ends_at')
                    ->label('Berhenti Ditampilkan (opsional)')
                    ->prefixIcon('heroicon-o-calendar')
                    ->nullable(),
            ]);
    }
}
