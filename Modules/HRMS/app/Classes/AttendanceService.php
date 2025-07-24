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
            ->where('roster_date', $clockTime->toDateString())
            ->with('shift')
            ->first();

        if (!$assignment || !$assignment->shift) {
            return ['message' => 'No shift assigned for today'];
        }

        $shiftTimeIn = Carbon::parse($assignment->shift->time_in);
        $shiftTimeOut = Carbon::parse($assignment->shift->time_out);
        $shiftBreakTimeIn = Carbon::parse($assignment->shift->break_time_in);
        $shiftBreakTimeOut = Carbon::parse($assignment->shift->break_time_out);

        if ($assignment->day_type === 'workday') {
            if ($action === 'clockin') {
                if ($clockTime->lte($shiftBreakTimeIn)) {
                    $status = $clockTime->lte($shiftTimeIn) ? 'on time' : 'late';
                    return [
                        'status' => $status,
                        'message' => 'Clock-in recorded successfully',
                    ];
                }
                if ($clockTime->between($shiftBreakTimeIn, $shiftBreakTimeOut)) {
                    $status = $clockTime->lte($shiftBreakTimeIn) ? 'on time' : 'late';
                    return [
                        'status' => $status,
                        'message' => 'Break clock-in recorded successfully',
                    ];
                }
            }

            if ($action === 'clockout') {
                if ($clockTime->between($shiftBreakTimeIn, $shiftBreakTimeOut)) {
                    $status = $clockTime->gte($shiftBreakTimeOut) ? 'on time' : 'early';
                    return [
                        'status' => $status,
                        'message' => 'Break clock-out recorded successfully',
                    ];
                }
                if ($clockTime->gte($shiftBreakTimeIn)) {
                    $status = $clockTime->gte($shiftTimeOut) ? 'on time' : 'early';
                    return [
                        'status' => $status,
                        'message' => 'Clock-out recorded successfully',
                    ];
                }
            }
        }

        if ($assignment->day_type === 'halfday') {
            if ($action === 'clockin') {
                $status = $clockTime->lte($shiftTimeIn) ? 'on time' : 'late';
                return [
                    'status' => $status,
                    'message' => 'Clock-in recorded successfully',
                ];
            }

            if ($action === 'clockout') {
                $status = $clockTime->gte($shiftTimeOut) ? 'on time' : 'early';
                return [
                    'status' => $status,
                    'message' => 'Clock-out recorded successfully',
                ];
            }
        }

        return ['message' => 'Invalid action or day type'];
    }
}
