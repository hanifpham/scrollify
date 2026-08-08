<?php

namespace App\Filament\Resources\ScanlatorMappings\Pages;

use App\Filament\Resources\ScanlatorMappings\ScanlatorMappingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListScanlatorMappings extends ListRecords
{
    protected static string $resource = ScanlatorMappingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
