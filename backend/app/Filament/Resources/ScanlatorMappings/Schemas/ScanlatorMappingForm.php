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
                    ->label('Manga UUID')
                    ->prefixIcon('heroicon-o-identification')
                    ->uuid()
                    ->required()
                    ->maxLength(36)
                    ->helperText('MangaDex Manga UUID'),
                TextInput::make('scanlation_group_id')
                    ->label('Scanlation Group UUID')
                    ->prefixIcon('heroicon-o-user-group')
                    ->uuid()
                    ->required()
                    ->maxLength(36)
                    ->helperText('MangaDex Scanlation Group UUID'),
                Select::make('group_type')
                    ->prefixIcon('heroicon-o-tag')
                    ->options([
                        'project' => 'Project',
                        'mirror' => 'Mirror',
                    ])
                    ->default('project')
                    ->required(),
                TextInput::make('priority')
                    ->prefixIcon('heroicon-o-bars-arrow-up')
                    ->numeric()
                    ->default(0)
                    ->required()
                    ->helperText('Higher value = higher priority'),
            ]);
    }
}
