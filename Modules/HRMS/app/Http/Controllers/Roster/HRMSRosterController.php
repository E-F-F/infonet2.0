<?php

namespace Modules\HRMS\Http\Controllers\Roster;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Modules\HRMS\Models\HRMSRoster;
use Modules\HRMS\Models\HRMSHoliday;
use Modules\HRMS\Models\HRMSOffday;
use Modules\HRMS\Models\HRMSRosterDayAssignments;
use Modules\HRMS\Models\HRMSStaffRosterGroupAssignment;

class HRMSRosterController extends Controller
{
    public function getStaffShift(Request $request, int $staffId): JsonResponse
    {
        $date = Carbon::parse($request->query('date', now()));

        $groupId = HRMSStaffRosterGroupAssignment::where('hrms_staff_id', $staffId)
            ->where('effective_date', '<=', $date)
            ->where(function ($q) use ($date) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $date);
            })
            ->value('roster_group_id');

        if (!$groupId) {
            return response()->json(['message' => 'No roster group assigned.'], 404);
        }

        $roster = HRMSRoster::where('roster_group_id', $groupId)
            ->where('year', $date->year)
            ->first();

        if (!$roster) {
            return response()->json(['message' => 'No roster found.'], 404);
        }

        $assignment = HRMSRosterDayAssignments::where('roster_id', $roster->id)
            ->where('roster_date', $date->toDateString())
            ->where(function ($q) use ($staffId) {
                $q->whereNull('hrms_staff_id')->orWhere('hrms_staff_id', $staffId);
            })
            ->orderByDesc('hrms_staff_id')
            ->first();

        if (!$assignment) {
            return response()->json(['message' => 'No shift assigned for this date.'], 404);
        }

        return response()->json([
            'date' => $date->toDateString(),
            'day_type' => $assignment->day_type,
            'shift' => $assignment->shift->only(['id', 'name', 'time_in', 'time_out']),
            'is_override' => $assignment->is_override,
        ]);
    }

    public function generate(Request $request): JsonResponse
    {
        $year = $request->input('year', now()->year);

        $rosters = HRMSRoster::with('rosterGroup')->where('year', $year)->get();
        $holidays = HRMSHoliday::pluck('holiday_date')->toArray();
        $offdays = HRMSOffday::where('status', 'active')->get();

        foreach ($rosters as $roster) {
            $period = CarbonPeriod::create(Carbon::create($year)->startOfYear(), Carbon::create($year)->endOfYear());

            foreach ($period as $date) {
                $dayType = 'workday';
                $dateString = $date->toDateString();
                $dow = strtolower($date->format('l'));

                if (in_array($dateString, $holidays)) {
                    $dayType = 'public_holiday';
                } elseif ($this->isMatchingOffday($date, $offdays)) {
                    $dayType = 'offday';
                }

                $field = $dow . '_shift_' . $dayType;
                $shiftId = $roster->{$field} ?? $roster->{'default_roster_shift_' . $dayType};

                if ($shiftId) {
                    HRMSRosterDayAssignments::updateOrCreate([
                        'roster_id' => $roster->id,
                        'roster_date' => $dateString,
                        'hrms_staff_id' => null,
                    ], [
                        'day_type' => $dayType,
                        'shift_id' => $shiftId,
                        'is_override' => false,
                    ]);
                }
            }
        }

        return response()->json(['message' => 'Roster generated for year ' . $year]);
    }

    public function override(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'roster_id' => 'required|exists:hrms_roster,id',
            'roster_date' => 'required|date',
            'hrms_staff_id' => 'nullable|exists:hrms_staff,id',
            'shift_id' => 'required|exists:hrms_roster_shift,id',
            'day_type' => 'required|in:workday,public_holiday,offday,company_halfoffday',
        ]);

        HRMSRosterDayAssignments::updateOrCreate([
            'roster_id' => $validated['roster_id'],
            'roster_date' => $validated['roster_date'],
            'hrms_staff_id' => $validated['hrms_staff_id'] ?? null,
        ], [
            'shift_id' => $validated['shift_id'],
            'day_type' => $validated['day_type'],
            'is_override' => true,
        ]);

        return response()->json(['message' => 'Override applied.']);
    }

    private function isMatchingOffday(Carbon $date, $offdays): bool
    {
        foreach ($offdays as $off) {
            $start = Carbon::parse($off->effective_date);
            $end = Carbon::parse($off->recurring_end_date);

            if (!$date->between($start, $end)) continue;

            $ref = Carbon::parse($off->holiday_date);

            switch ($off->recurring_interval) {
                case 'weekly':
                    if ($date->dayOfWeek === $ref->dayOfWeek) return true;
                    break;
                case 'monthly':
                    if ($date->day === $ref->day) return true;
                    break;
                case 'quarterly':
                    if ($date->day === $ref->day && in_array($date->month, [1, 4, 7, 10])) return true;
                    break;
                case 'annually':
                    if ($date->day === $ref->day && $date->month === $ref->month) return true;
                    break;
                case 'one time':
                    if ($date->equalTo($ref)) return true;
                    break;
            }
        }
        return false;
    }
}
