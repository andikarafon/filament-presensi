<?php

namespace App\Filament\Resources\Attendances\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class AttendancesTable
{
    public static function configure(Table $table): Table
    {
             return $table
             ->modifyQueryUsing(function (Builder $query) {
                        $user = Auth::user();
                        // Cegah error kalau belum login
                        //jika dijalankan tdk ada error, intelphensenya yang error
                        if ($user && ! $user->hasRole('super_admin')) {
                            $query->where('user_id', $user->id);
                        }
                    })
            ->columns([
                TextColumn::make('user.name')
                    ->sortable(),
                TextColumn::make('start_time')
                    ->label('Waktu Datang')
                    ->time()
                    ->sortable(),
                TextColumn::make('end_time')
                    ->label('Waktu Pulang')
                    ->time()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->date()
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
