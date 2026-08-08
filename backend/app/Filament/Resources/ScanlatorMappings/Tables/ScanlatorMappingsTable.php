<?php

namespace App\Filament\Resources\ScanlatorMappings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ScanlatorMappingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable(),
                TextColumn::make('manga_id')
                    ->label('Manga UUID')
                    ->searchable()
                    ->copyable()
                    ->fontFamily('mono'),
                TextColumn::make('scanlation_group_id')
                    ->label('Scanlation Group UUID')
                    ->searchable()
                    ->copyable()
                    ->fontFamily('mono'),
                TextColumn::make('group_type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'project' => 'primary',
                        'mirror' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('priority')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('priority', 'desc')
            ->filters([
                //
            ])
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
