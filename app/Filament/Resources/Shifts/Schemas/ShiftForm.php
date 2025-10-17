<?php

namespace App\Filament\Resources\Shifts\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section as ComponentsSection;

class ShiftForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
                ->schema([ // Atau ->components([ jika di Infolist
                    ComponentsSection::make('Data Shift') // Beri judul untuk Section
                        ->description('Masukkan detail Shift') // Deskripsi opsional
                        ->schema([
                            TextInput::make('name')
                                ->required(),
                            TimePicker::make('start_time')
                                ->required(),
                            TimePicker::make('end_time')
                                ->required(),
                        ]),
                ]);
    }
}
