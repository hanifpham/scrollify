<?php

namespace App\Filament\Resources\ScanlatorMappings\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ScanlatorMappingForm
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
                    ->helperText('UUID manga dari MangaDex, contoh: 32d76d19-8a05-4db0-9fc2-e0b0648fe9d0.'),
                TextInput::make('scanlation_group_id')
                    ->label('ID Grup Penerjemah (MangaDex)')
                    ->prefixIcon('heroicon-o-user-group')
                    ->uuid()
                    ->required()
                    ->maxLength(36)
                    ->helperText('UUID grup scanlator dari MangaDex. Cara dapatnya: buka halaman chapter manga di mangadex.org, klik nama grup penerjemahnya, copy UUID dari URL.'),
                Select::make('group_type')
                    ->label('Jenis Grup')
                    ->prefixIcon('heroicon-o-tag')
                    ->options([
                        'project' => 'Utama (Project)',
                        'mirror' => 'Cadangan (Mirror)',
                    ])
                    ->default('project')
                    ->required(),
                TextInput::make('priority')
                    ->label('Prioritas')
                    ->prefixIcon('heroicon-o-bars-arrow-up')
                    ->numeric()
                    ->default(0)
                    ->required()
                    ->helperText('Semakin besar angka, semakin diutamakan kalau ada lebih dari satu grup mirror'),
            ]);
    }
}
