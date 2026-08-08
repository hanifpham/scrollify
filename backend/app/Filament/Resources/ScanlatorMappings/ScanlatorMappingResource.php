<?php

namespace App\Filament\Resources\ScanlatorMappings;

use App\Filament\Resources\ScanlatorMappings\Pages\CreateScanlatorMapping;
use App\Filament\Resources\ScanlatorMappings\Pages\EditScanlatorMapping;
use App\Filament\Resources\ScanlatorMappings\Pages\ListScanlatorMappings;
use App\Filament\Resources\ScanlatorMappings\Schemas\ScanlatorMappingForm;
use App\Filament\Resources\ScanlatorMappings\Tables\ScanlatorMappingsTable;
use App\Models\ScanlatorMapping;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ScanlatorMappingResource extends Resource
{
    protected static ?string $model = ScanlatorMapping::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-link';

    protected static string|UnitEnum|null $navigationGroup = 'Konten Kurasi';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return ScanlatorMappingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ScanlatorMappingsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListScanlatorMappings::route('/'),
            'create' => CreateScanlatorMapping::route('/create'),
            'edit' => EditScanlatorMapping::route('/{record}/edit'),
        ];
    }
}
