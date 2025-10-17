<?php

namespace App\Filament\Resources\Offices\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section as ComponentsSection;

class OfficeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
        ->schema([ // Atau ->components([ jika di Infolist
            ComponentsSection::make('Data Lokasi Utama') // Beri judul untuk Section
                ->description('Masukkan detail nama dan koordinat lokasi.') // Deskripsi opsional
                ->schema([
                    TextInput::make('name')
                        ->required(),
                    TextInput::make('latitude')
                        ->required()
                        ->numeric(),
                    TextInput::make('longitude')
                        ->required()
                        ->numeric(),
                    TextInput::make('radius')
                        ->required()
                        ->numeric(),
                ]),
        ]);
    }
}
