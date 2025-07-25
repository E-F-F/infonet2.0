<?php

namespace Modules\HRMS\Classes;

use Modules\HRMS\Models\HRMSAttendance;
use Modules\HRMS\Models\HRMSRosterDayAssignments;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\HRMS\Models\HRMSRoster;
use Modules\HRMS\Models\HRMSStaff;

class AttendanceService
{
    private $staffId;
    private $rosterGroupId;

    public function __construct()
    {
        $this->staffId = Auth::user()->id;
        $this->rosterGroupId = HRMSStaff::find($this->staffId)->roster_group;
    }

    /**
     * Process user clock-in/out and validate against roster.
     *
     * @param Carbon $clockTime
     * @param string $action
     * @return array
     */
    public function processClockIn(Carbon $clockTime, string $action): array
    {
        if (!$this->rosterGroupId) {
            return ['message' => 'Not assigned to any roster'];
        }

        $roster = HRMSRoster::where('roster_group_id', $this->rosterGroupId)->first();
        if (!$roster) {
            return ['message' => 'No roster found'];
        }

        $assignment = HRMSRosterDayAssignments::where('roster_id', $roster->id)
            ->where('roster_date', Carbon::today()->toDateString())
            ->with('shift')
            ->first();

        if (!$assignment || !$assignment->shift) {
            return ['message' => 'No shift assigned for today'];
        }

        $attendance = HRMSAttendance::firstOrNew([
            'hrms_staff_id' => $this->staffId,
            'attendance_date' => Carbon::today()->toDateString(),
        ]);

        $shiftTimeIn = Carbon::parse($assignment->shift->time_in);
        $shiftTimeOut = Carbon::parse($assignment->shift->time_out);
        $shiftBreakTimeIn = Carbon::parse($assignment->shift->break_time_in); //time in to go back to work
        $shiftBreakTimeOut = Carbon::parse($assignment->shift->break_time_out); //time out to go to break
        $shift_total_minutes = $shiftTimeOut->diffInMinutes($shiftTimeIn);

        // Only subtract break if both times are set
        if (!is_null($assignment->shift->break_time_in) && !is_null($assignment->shift->break_time_out)) {
            $break_minutes = Carbon::parse($assignment->shift->break_time_in)
                ->diffInMinutes(Carbon::parse($assignment->shift->break_time_out));

            $shift_total_minutes -= $break_minutes;
        }

        $shift_total_hours = $shift_total_minutes / 60;


        $response = ['message' => 'Invalid action or day type'];

        if ($assignment->day_type === 'workday') {
            if ($action === 'clockin') {
                if ($clockTime->lte($shiftBreakTimeIn)) {
                    $attendance->morning_clockIn = $clockTime;
                    $attendance->morning_status = $clockTime->lte($shiftTimeIn) ? 'present' : 'late';
                    $response = [
                        'status' => $attendance->morning_status,
                        'message' => 'Clock-in recorded successfully',
                    ];
                } elseif ($clockTime->between($shiftBreakTimeIn, $shiftBreakTimeOut)) {
                    $attendance->afternoon_clockIn = $clockTime;
                    $attendance->afternoon_status = 'present'; // You may refine this
                    $response = [
                        'status' => 'present',
                        'message' => 'Break clock-in recorded successfully',
                    ];
                }
            }

            if ($action === 'clockout') {
                if ($clockTime->between($shiftBreakTimeIn, $shiftBreakTimeOut)) {
                    $attendance->morning_clockOut = $clockTime;
                    $response = [
                        'status' => 'on break',
                        'message' => 'Break clock-out recorded successfully',
                    ];
                } elseif ($clockTime->gte($shiftBreakTimeIn)) {
                    $attendance->afternoon_clockOut = $clockTime;
                    $response = [
                        'status' => $clockTime->gte($shiftTimeOut) ? 'present' : 'early',
                        'message' => 'Clock-out recorded successfully',
                    ];
                }
            }

            // Calculate working hours if both clock-ins/outs are present
            if ($attendance->morning_clockIn && $attendance->morning_clockOut) {
                $morning_working_hours = Carbon::parse($attendance->morning_clockOut)
                    ->diffInMinutes(Carbon::parse($attendance->morning_clockIn)) / 60;
            }

            if ($attendance->afternoon_clockIn && $attendance->afternoon_clockOut) {
                $afternoon_working_hours = Carbon::parse($attendance->afternoon_clockOut)
                    ->diffInMinutes(Carbon::parse($attendance->afternoon_clockIn)) / 60;
            }
            $attendance->total_working_hours = $shift_total_hours - ($afternoon_working_hours + $morning_working_hours);
        }

        if ($assignment->day_type === 'halfday') {
            if ($action === 'clockin') {
                $attendance->morning_clockIn = $clockTime;
                $attendance->morning_status = $clockTime->lte($shiftTimeIn) ? 'present' : 'late';
                $response = [
                    'status' => $attendance->morning_status,
                    'message' => 'Clock-in recorded successfully',
                ];
            }

            if ($action === 'clockout') {
                $attendance->afternoon_clockOut = $clockTime;
                $response = [
                    'status' => $clockTime->gte($shiftTimeOut) ? 'present' : 'early',
                    'message' => 'Clock-out recorded successfully',
                ];
            }

            if ($attendance->morning_clockIn && $attendance->afternoon_clockOut) {
                $halfday_working_hours = Carbon::parse($attendance->afternoon_clockOut)
                    ->diffInMinutes(Carbon::parse($attendance->morning_clockIn)) / 60;
            }
            $attendance->total_working_hours = $shift_total_hours - $halfday_working_hours;
        }



        $attendance->save();

        return $response;
    }
}
