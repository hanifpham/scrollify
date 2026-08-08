<?php

namespace App\Filament\Resources\ReleaseSchedules;

use App\Filament\Resources\ReleaseSchedules\Pages\CreateReleaseSchedule;
use App\Filament\Resources\ReleaseSchedules\Pages\EditReleaseSchedule;
use App\Filament\Resources\ReleaseSchedules\Pages\ListReleaseSchedules;
use App\Filament\Resources\ReleaseSchedules\Schemas\ReleaseScheduleForm;
use App\Filament\Resources\ReleaseSchedules\Tables\ReleaseSchedulesTable;
use App\Models\ReleaseSchedule;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ReleaseScheduleResource extends Resource
{
    protected static ?string $model = ReleaseSchedule::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static string|UnitEnum|null $navigationGroup = 'Konten Kurasi';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return ReleaseScheduleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReleaseSchedulesTable::configure($table);
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
            'index' => ListReleaseSchedules::route('/'),
            'create' => CreateReleaseSchedule::route('/create'),
            'edit' => EditReleaseSchedule::route('/{record}/edit'),
        ];
    }
}
