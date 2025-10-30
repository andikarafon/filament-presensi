<?php

namespace App\Filament\Resources\Leaves\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LeaveForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
        ->schema([ 
                Section::make('Data Leave') // Beri judul untuk Section
                ->schema([
                        DatePicker::make('start_date')
                            ->required(),
                        DatePicker::make('end_date')
                            ->required(),
                        Textarea::make('reason')
                            ->columnSpanFull(),
                        ]),
                    Section::make('Approval') // Beri judul untuk Section
                    ->schema([
                            Select::make('status')
                                ->options(['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'])
                                ->default('pending'),
                            Textarea::make('note')
                                ->columnSpanFull(),
                    ])
                    ->visible(function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole('super_admin');
                    }),
                    //hanya muncul di super_admin saja
            ]);
        
        
    }
}
