<?php

namespace App\Filament\Resources\Attendances\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;


class AttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user')
                    ->relationship('user', 'name')
                    ->disabled()
                    ->required(),
                TextInput::make('schedule_latitude')
                    ->required()
                    ->numeric(),
                TextInput::make('schedule_longitude')
                    ->required()
                    ->numeric(),
                TimePicker::make('schedule_start_time')
                    ->required(),
                TimePicker::make('schedule_end_time')
                    ->required(),
                TextInput::make('start_latitude')
                    ->required()
                    ->numeric(),
                TextInput::make('start_longitude')
                    ->required()
                    ->numeric(),
                TimePicker::make('start_time')
                    ->required(),
                TimePicker::make('end_time')
                    ->required(),
                TextInput::make('end_latitude')
                    ->numeric(),
                TextInput::make('end_longitude')
                    ->numeric(),
            ]);
    }
}
