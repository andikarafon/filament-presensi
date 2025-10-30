<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Filament\Schemas\Components\Section as ComponentsSection;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
        ->schema([
            // ===================================
            // SECTION 1: Informasi Dasar
            // ===================================
            ComponentsSection::make('Informasi Dasar')
                ->description('Nama dan alamat email User.')
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('email')
                        ->label('Email address')
                        ->email()
                        ->required()
                        ->maxLength(255),
                    DateTimePicker::make('email_verified_at')
                        ->label('Verified At'),
                ]),

            // -----------------------------------

            // ===================================
            // SECTION 2: Pengaturan Keamanan & Peran
            // ===================================
            ComponentsSection::make('Pengaturan Keamanan & Peran')
                ->description('Kata sandi dan hak akses (role) pengguna.')
                ->schema([
                    TextInput::make('password')
                        ->password()
                        ->maxLength(255)
                        ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                        ->dehydrated(fn ($state) => filled($state))
                        ->required(fn (string $context): bool => $context === 'create')
                        ->helperText('Kosongkan kolom ini jika Anda tidak ingin mengubah password.'),
                    Select::make('roles')
                        ->relationship('roles', 'name')
                        ->multiple() //wajib untuk relasi many-to-many
                        ->preload()
                        ->searchable()
                        ->required(), // Menambahkan required untuk memastikan role terpilih
                    FileUpload::make('image') 
                ]),
        ]);
    }
}
