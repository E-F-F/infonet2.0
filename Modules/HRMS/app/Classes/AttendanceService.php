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

        $shift = $assignment->shift;
        $shiftTimeIn = Carbon::parse($shift->time_in);
        $shiftTimeOut = Carbon::parse($shift->time_out);
        $shiftBreakOut = $shift->break_time_out ? Carbon::parse($shift->break_time_out) : null;
        $shiftBreakIn = $shift->break_time_in ? Carbon::parse($shift->break_time_in) : null;

        $shiftTotalMinutes = $shiftTimeOut->diffInMinutes($shiftTimeIn);
        if ($shiftBreakOut && $shiftBreakIn) {
            $breakMinutes = $shiftBreakIn->diffInMinutes($shiftBreakOut);
            $shiftTotalMinutes -= $breakMinutes;
        }

        $response = ['message' => 'Invalid action or day type'];

        if ($assignment->day_type === 'workday' || $assignment->day_type === 'halfday') {
            if ($action === 'clockin') {
                if (is_null($attendance->time_in)) {
                    $attendance->time_in = $clockTime;
                    $attendance->time_in_status = $clockTime->lte($shiftTimeIn) ? 'ontime' : 'late';

                    if ($attendance->time_in_status === 'late') {
                        $attendance->late_time_in = $clockTime->diffInMinutes($shiftTimeIn);
                    }

                    $response = [
                        'status' => $attendance->time_in_status,
                        'message' => 'Clock-in recorded successfully',
                    ];
                } elseif ($shiftBreakIn && $clockTime->between($shiftBreakOut, $shiftBreakIn)) {
                    $attendance->break_time_in = $clockTime;
                    $response = [
                        'status' => 'break_in',
                        'message' => 'Break return time recorded',
                    ];
                }
            }

            if ($action === 'clockout') {
                if ($shiftBreakOut && $clockTime->between($shiftTimeIn, $shiftBreakOut)) {
                    $attendance->break_time_out = $clockTime;
                    $response = [
                        'status' => 'break_out',
                        'message' => 'Break out time recorded',
                    ];
                } elseif (is_null($attendance->time_out)) {
                    $attendance->time_out = $clockTime;
                    $attendance->time_out_status = $clockTime->gte($shiftTimeOut) ? 'ontime' : 'early';

                    if ($attendance->time_out_status === 'early') {
                        $attendance->early_time_out = $shiftTimeOut->diffInMinutes($clockTime);
                    }

                    $response = [
                        'status' => $attendance->time_out_status,
                        'message' => 'Clock-out recorded successfully',
                    ];
                }
            }

            // Calculate working hours if both clock-in and clock-out exist
            if ($attendance->time_in && $attendance->time_out) {
                $totalMinutesWorked = $attendance->time_out->diffInMinutes($attendance->time_in);

                // Subtract break time if both are present
                if ($attendance->break_time_out && $attendance->break_time_in) {
                    $breakTime = $attendance->break_time_in->diffInMinutes($attendance->break_time_out);
                    $attendance->break_time_total = $breakTime;
                    $totalMinutesWorked -= $breakTime;
                }

                $attendance->total_working_hours = round($totalMinutesWorked / 60, 2);
            }
        }

        $attendance->save();

        return $response;
    }
}
