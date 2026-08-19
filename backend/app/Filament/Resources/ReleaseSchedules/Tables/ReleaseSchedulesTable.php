<?php

namespace App\Filament\Resources\ReleaseSchedules\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ReleaseSchedulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable(),
                TextColumn::make('manga_title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('manga_id')
                    ->label('Manga UUID')
                    ->searchable()
                    ->copyable()
                    ->fontFamily('mono')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('release_day')
                    ->badge()
                    ->sortable(),
                TextColumn::make('release_time')
                    ->time('H:i')
                    ->sortable(),
                TextColumn::make('is_active')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Aktif' : 'Nonaktif')
                    ->color(fn (bool $state): string => $state ? 'success' : 'gray')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('release_day', 'asc')
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('Semua status')
                    ->trueLabel('Hanya Aktif')
                    ->falseLabel('Hanya Nonaktif'),
                SelectFilter::make('release_day')
                    ->label('Hari Rilis')
                    ->options([
                        'monday' => 'Monday',
                        'tuesday' => 'Tuesday',
                        'wednesday' => 'Wednesday',
                        'thursday' => 'Thursday',
                        'friday' => 'Friday',
                        'saturday' => 'Saturday',
                        'sunday' => 'Sunday',
                    ]),
            ])
            ->emptyStateHeading('Belum ada jadwal rilis')
            ->emptyStateDescription('Atur jadwal rilis mingguan untuk manga/manhwa/manhua yang sedang on-going.')
            ->emptyStateIcon('heroicon-o-calendar-days')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
