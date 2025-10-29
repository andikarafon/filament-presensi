<?php

namespace App\Filament\Resources\Attendances\Schemas;

use App\Models\Attendance;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AttendanceInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user_id')
                    ->numeric(),
                TextEntry::make('schedule_latitude')
                    ->numeric(),
                TextEntry::make('schedule_longitude')
                    ->numeric(),
                TextEntry::make('schedule_start_time')
                    ->time(),
                TextEntry::make('schedule_end_time')
                    ->time(),
                TextEntry::make('start_latitude')
                    ->numeric(),
                TextEntry::make('start_longitude')
                    ->numeric(),
                TextEntry::make('start_time')
                    ->time(),
                TextEntry::make('end_time')
                    ->time(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Attendance $record): bool => $record->trashed()),
                TextEntry::make('end_latitude')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('end_longitude')
                    ->numeric()
                    ->placeholder('-'),
            ]);
    }
}
