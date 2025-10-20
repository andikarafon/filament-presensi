<?php

namespace App\Filament\Resources\Schedules\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section as ComponentsSection;

class ScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
        ->schema([ // Atau ->components([ jika di Infolist
            ComponentsSection::make('Data Schedule') // Beri judul untuk Section
                ->schema([
                    Select::make('user_id')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->required(),
                    Select::make('shift_id')
                        ->relationship('shift', 'name')
                        ->required(),
                    Select::make('office_id')
                        ->relationship('office', 'name')
                        ->required(),
                Toggle::make('is_wfa'),
                ]),
        ]);
    }
}
