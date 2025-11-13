<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Attendance;


class Map extends Component
{

    public $markers = [];

    public function render()
    {
        $attendances = Attendance::with('user')
                        ->get(['id', 'user_id', 'start_latitude', 'start_longitude', 'start_time']);
        // dd($attendances->toArray());
        return view('livewire.map', [
            'attendances' => $attendances
        ]);
    }

}
