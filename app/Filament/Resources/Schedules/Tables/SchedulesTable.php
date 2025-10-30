<?php

namespace App\Filament\Resources\Schedules\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;


class SchedulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $user = Auth::user();

                // Cegah error kalau belum login
                //jika dijalankan tdk ada error, intelphensenya yang error
                if ($user instanceof User && ! $user->hasRole('super_admin')) {
                    $query->where('user_id', $user->id);
                }
            })
            ->columns([
                TextColumn::make('user.name')
                    ->label('Name')
                    ->sortable()
                    ->searchable(),
                //toggle akan hilang kecuali super admin
                ToggleColumn::make('is_banned')
                    ->hidden(function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();

                        return ! ($user && $user->hasRole('super_admin'));
                    }),
                IconColumn::make('is_wfa')
                    ->label('WFA')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                TextColumn::make('shift.name')
                    ->searchable()
                    ->description(fn ($record) => $record->shift?->start_time. ' - '.$record->shift?->end_time),
                    // shift? artinya null safety
                TextColumn::make('office.name')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
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
