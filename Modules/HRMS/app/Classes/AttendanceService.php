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

    public function test()
    {
        $clockInTime = Carbon::now();
        $roster = HRMSRoster::where('roster_group_id', $this->rosterGroupId)->first();
        $assignment = HRMSRosterDayAssignments::where('roster_id', $roster->id)
            ->where('roster_date', $clockInTime->toDateString())
            ->with('shift')
            ->first();

        if ($assignment->day_type === 'workday') {
            // Parses the scheduled shift start time into a Carbon object (e.g. "08:00:00").
            $shiftStartTime = Carbon::parse($assignment->shift->time_in); // Assumes HRMSRosterShift has start_time
            // Compares the current clock-in time to the scheduled shift time.
            $status = $clockInTime->lte($shiftStartTime) ? 'on time' : 'late';
            return [
                'status' => $status,
                'message' => 'Today is workday',
                'assignment' => $assignment

            ];
        }
        if ($assignment->day_type === 'offday') {
            return [
                'message' => 'Today is offday'
            ];
        }
        // return [
        //     'staffId' => $this->staffId,
        //     'rosterGroupId'  => $this->rosterGroupId,
        //     'roster' => $assignment
        // ];
    }

    /**
     * Process user clock-in and validate against roster.
     *
     * @param int $staffId
     * @param Carbon $clockInTime
     * @return array
     */
    public function processClockIn(int $staffId, Carbon $clockInTime): array
    {
        // Get roster day assignment for staff and date
        $assignment = HRMSRosterDayAssignments::where('hrms_staff_id', $staffId)
            ->where('roster_date', $clockInTime->toDateString())
            ->with('shift', 'roster')
            ->first();

        // Reject if no assignment exists
        if (!$assignment) {
            $shiftStartTime = Carbon::parse($assignment->shift->start_time); // Assumes HRMSRosterShift has start_time
            $status = $clockInTime->lte($shiftStartTime) ? 'on time' : 'late';
            return [
                'status' => $status,
                'message' => 'No roster assignment for staff on this date.'

            ];
        }

        // Check if it's an offday
        if ($assignment->day_type === 'offday') {
            // Reject if no specific work assignment for staff
            if (!$assignment->is_override || $assignment->hrms_staff_id !== $staffId) {
                return [
                    'status' => 'rejected',
                    'message' => 'Offday with no specific work assignment for staff.'
                ];
            }
        }

        // For workday or override, check shift start time
        $shiftStartTime = Carbon::parse($assignment->shift->start_time); // Assumes HRMSRosterShift has start_time
        $status = $clockInTime->lte($shiftStartTime) ? 'on time' : 'late';

        // Record attendance
        $attendance = HRMSAttendance::create([
            'hrms_staff_id' => $staffId,
            'attendance_date' => $clockInTime->toDateString(),
            'morning_clockIn' => $clockInTime,
            'morning_status' => $status,
            'remark' => $status === 'on time' ? 'On time' : 'Late clock-in'
        ]);

        return [
            'status' => 'success',
            'message' => 'Attendance recorded.',
            'attendance_id' => $attendance->id
        ];
    }
}
