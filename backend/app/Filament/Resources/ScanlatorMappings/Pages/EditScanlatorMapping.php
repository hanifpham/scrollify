<?php

namespace App\Filament\Resources\ScanlatorMappings\Pages;

use App\Filament\Resources\ScanlatorMappings\ScanlatorMappingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditScanlatorMapping extends EditRecord
{
    protected static string $resource = ScanlatorMappingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
