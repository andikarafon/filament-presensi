<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Facades\Validator;
use App\Models\Schedule;
use App\Models\Leave;
use Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function getAttendanceToday() {
        $userId = auth()->user()->id;
        $today = now()->toDateString();
        $currentMonth = now()->month;

        $attendanceToday = Attendance::select('start_time', 'end_time')
                            ->where('user_id', $userId)
                            ->whereDate('created_at', $today)
                            ->first();

        $attendanceThisMonth = Attendance::select('start_time', 'end_time', 'created_at')
                            ->where('user_id', $userId)
                            ->whereMonth('created_at', $currentMonth)
                            ->get()
                            ->map(function ($attendance) {
                                return [
                                    'start_time' => $attendance->start_time,
                                    'end_time' => $attendance->end_time,
                                    'date' => $attendance->created_at->toDateString()
                                ];
                            });

        return response()->json([
                                'success' => true,
                                'message' => 'Attendance retrieved successfully.',
                                'data' => [
                                    'today' => $attendanceToday,
                                    'this_month' => $attendanceThisMonth
                                ]
                            ]);
    } //end getAttendanceToday

    public function getSchedule()
    {
        $schedule = Schedule::with(['office', 'shift'])->where('user_id', auth()->user()->id)->first();

        if ($schedule == null) {
            return response()->json([
                'success' => false,
                'message' => 'User belum mendapatkan jadwal kerja, segera hubungi Admin.',
                'data' => null
            ]);
        }

        $today = Carbon::today()->format('Y-m-d');
        $approvedLeave = Leave::where('user_id', Auth::user()->id)
                              ->where('status', 'approved')
                              ->whereDate('start_date', '<=', $today)
                              ->whereDate('end_date', '>=', $today)
                              ->exists();

        if ($approvedLeave) {
            return response()->json([
                'success' => true,
                'message' => 'Anda tidak dapat melakukan presensi karena sedang cuti.',
                'data' => null
            ]);
        }

        if ($schedule->is_banned) {
            return response()->json([
                'success' => false,
                'message' => 'You are banned',
                'data' => null
            ]);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'Success get schedule',
                'data' => $schedule
            ]);
        }
    } //end of getSchedule

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]); //validasi latitude dan longitude nya

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error.',
                'errors' => $validator->errors()
            ], 422);
        }

        //cek jadwalnya dulu, ada atau tidak
        $schedule = Schedule::where('user_id', Auth::user()->id)->first();

        if ($schedule == null) {
            return response()->json([
                'success' => false,
                'message' => 'User belum mendapatkan jadwal kerja, segera hubungi Admin.',
                'data' => null
            ]);
        }

        $today = Carbon::today()->format('Y-m-d');
        $approvedLeave = Leave::where('user_id', Auth::user()->id)
                              ->where('status', 'approved')
                              ->whereDate('start_date', '<=', $today)
                              ->whereDate('end_date', '>=', $today)
                              ->exists();

        if ($approvedLeave) {
            return response()->json([
                'success' => true,
                'message' => 'Anda tidak dapat melakukan presensi karena sedang cuti.',
                'data' => null
            ]);
        }

        if ($schedule) {
            $attendance = Attendance::where('user_id', Auth::user()->id)
                            ->whereDate('created_at', now()->toDateString())->first();

            if (!$attendance) { //jika belum ada, maka dibuat presensinya
                $attendance = Attendance::create([
                    'user_id' => Auth::user()->id,
                    'schedule_latitude' => $schedule->office->latitude,
                    'schedule_longitude' => $schedule->office->longitude,
                    'schedule_start_time' => $schedule->shift->start_time,
                    'schedule_end_time' => $schedule->shift->end_time,
                    'start_latitude' => $request->latitude,
                    'start_longitude' => $request->longitude,
                    'start_time' => Carbon::now()->toTimeString(),
                    'end_time' => Carbon::now()->toTimeString(),
                ]);
            } else { //Jika sudah ada, maka cuma update end_time nya saja
                $attendance->update([
                    'end_latitude' => $request->latitude,
                    'end_longitude' => $request->longitude,
                    'end_time' => Carbon::now()->toTimeString(),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Attendance recorded successfully.',
                'data' => $attendance
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No schedule found for the user.'
        ], 404);
    }

}
