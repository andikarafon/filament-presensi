<?php

namespace App\Filament\Resources\Schedules\Pages;

use App\Filament\Resources\Schedules\ScheduleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;

class ListSchedules extends ListRecords
{
    protected static string $resource = ScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //jika ingin menambahkan action button, dan sebagainya
            Action::make('presensi')
                ->url(route('presensi'))
                ->color('warning'),
            CreateAction::make(),
        ];
    }
}
