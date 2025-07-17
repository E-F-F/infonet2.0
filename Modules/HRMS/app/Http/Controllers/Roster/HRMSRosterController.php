<?php

namespace Modules\HRMS\Http\Controllers\Roster;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Modules\HRMS\Models\HRMSRoster;
use Modules\HRMS\Models\HRMSRosterShift;
use Modules\HRMS\Models\HRMSRosterGroup;
use Modules\HRMS\Models\HRMSHoliday;
use Modules\HRMS\Models\HRMSOffday;
use Modules\HRMS\Models\HRMSRosterDayAssignments;
use Modules\HRMS\Models\HRMSStaffRosterGroupAssignment;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class HRMSRosterController extends Controller
{
    /**
     * Display a listing of the rosters.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = HRMSRoster::query();

            // Filter by branch_id if provided
            if ($request->has('branch_id')) {
                $query->where('branch_id', $request->input('branch_id'));
            }

            // Filter by year if provided
            if ($request->has('year')) {
                $query->where('year', $request->input('year'));
            }

            // Filter by status if provided
            if ($request->has('status')) {
                $query->where('status', $request->input('status'));
            }

            // Search by roster_group_id if provided
            if ($request->has('roster_group_id')) {
                $query->where('roster_group_id', $request->input('roster_group_id'));
            }

            // Pagination
            $perPage = $request->input('per_page', 10);
            $rosters = $query->with(['branch', 'rosterGroup', 'defaultRosterShiftWorkday', 'defaultRosterShiftPublicHoliday', 'defaultRosterShiftOffday', 'defaultRosterShiftCompanyHalfoffday'])
                ->paginate($perPage);

            return response()->json([
                'status' => 'success',
                'message' => 'Rosters retrieved successfully.',
                'data' => $rosters
            ]);
        } catch (\Exception $e) {
            Log::error('Error retrieving rosters: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while retrieving rosters.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created roster in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'branch_id' => 'nullable|exists:branch,id',
                'year' => 'required|integer|unique:hrms_roster,year',
                'roster_group_id' => 'nullable|exists:hrms_roster_group,id',
                'default_roster_shift_workday' => 'nullable|exists:hrms_roster_shift,id',
                'default_roster_shift_public_holiday' => 'nullable|exists:hrms_roster_shift,id',
                'default_roster_shift_offday' => 'nullable|exists:hrms_roster_shift,id',
                'default_roster_shift_company_halfoffday' => 'nullable|exists:hrms_roster_shift,id',
                'sunday_shift_workday' => 'nullable|exists:hrms_roster_shift,id',
                'sunday_shift_public_holiday' => 'nullable|exists:hrms_roster_shift,id',
                'sunday_shift_offday' => 'nullable|exists:hrms_roster_shift,id',
                'sunday_shift_company_halfoffday' => 'nullable|exists:hrms_roster_shift,id',
                'monday_shift_workday' => 'nullable|exists:hrms_roster_shift,id',
                'monday_shift_public_holiday' => 'nullable|exists:hrms_roster_shift,id',
                'monday_shift_offday' => 'nullable|exists:hrms_roster_shift,id',
                'monday_shift_company_halfoffday' => 'nullable|exists:hrms_roster_shift,id',
                'tuesday_shift_workday' => 'nullable|exists:hrms_roster_shift,id',
                'tuesday_shift_public_holiday' => 'nullable|exists:hrms_roster_shift,id',
                'tuesday_shift_offday' => 'nullable|exists:hrms_roster_shift,id',
                'tuesday_shift_company_halfoffday' => 'nullable|exists:hrms_roster_shift,id',
                'wednesday_shift_workday' => 'nullable|exists:hrms_roster_shift,id',
                'wednesday_shift_public_holiday' => 'nullable|exists:hrms_roster_shift,id',
                'wednesday_shift_offday' => 'nullable|exists:hrms_roster_shift,id',
                'wednesday_shift_company_halfoffday' => 'nullable|exists:hrms_roster_shift,id',
                'thursday_shift_workday' => 'nullable|exists:hrms_roster_shift,id',
                'thursday_shift_public_holiday' => 'nullable|exists:hrms_roster_shift,id',
                'thursday_shift_offday' => 'nullable|exists:hrms_roster_shift,id',
                'thursday_shift_company_halfoffday' => 'nullable|exists:hrms_roster_shift,id',
                'friday_shift_workday' => 'nullable|exists:hrms_roster_shift,id',
                'friday_shift_public_holiday' => 'nullable|exists:hrms_roster_shift,id',
                'friday_shift_offday' => 'nullable|exists:hrms_roster_shift,id',
                'friday_shift_company_halfoffday' => 'nullable|exists:hrms_roster_shift,id',
                'saturday_shift_workday' => 'nullable|exists:hrms_roster_shift,id',
                'saturday_shift_public_holiday' => 'nullable|exists:hrms_roster_shift,id',
                'saturday_shift_offday' => 'nullable|exists:hrms_roster_shift,id',
                'saturday_shift_company_halfoffday' => 'nullable|exists:hrms_roster_shift,id',
                'effective_date' => 'required|date',
                'status' => 'required|in:active,disabled'
            ]);

            $roster = HRMSRoster::create($validatedData);

            return response()->json([
                'status' => 'success',
                'message' => 'Roster created successfully.',
                'data' => $roster
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating roster: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while creating the roster.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified roster.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $roster = HRMSRoster::with(['branch', 'rosterGroup', 'defaultRosterShiftWorkday', 'defaultRosterShiftPublicHoliday', 'defaultRosterShiftOffday', 'defaultRosterShiftCompanyHalfoffday'])
                ->find($id);

            if (!$roster) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Roster not found.'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Roster retrieved successfully.',
                'data' => $roster
            ]);
        } catch (\Exception $e) {
            Log::error('Error retrieving roster: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while retrieving the roster.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified roster in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $roster = HRMSRoster::find($id);

            if (!$roster) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Roster not found.'
                ], 404);
            }

            $validatedData = $request->validate([
                'branch_id' => 'sometimes|nullable|exists:branch,id',
                'year' => 'sometimes|integer|unique:hrms_roster,year,' . $id,
                'roster_group_id' => 'sometimes|nullable|exists:hrms_roster_group,id',
                'default_roster_shift_workday' => 'sometimes|nullable|exists:hrms_roster_shift,id',
                'default_roster_shift_public_holiday' => 'sometimes|nullable|exists:hrms_roster_shift,id',
                'default_roster_shift_offday' => 'sometimes|nullable|exists:hrms_roster_shift,id',
                'default_roster_shift_company_halfoffday' => 'sometimes|nullable|exists:hrms_roster_shift,id',
                'sunday_shift_workday' => 'sometimes|nullable|exists:hrms_roster_shift,id',
                'sunday_shift_public_holiday' => 'sometimes|nullable|exists:hrms_roster_shift,id',
                'sunday_shift_offday' => 'sometimes|nullable|exists:hrms_roster_shift,id',
                'sunday_shift_company_halfoffday' => 'sometimes|nullable|exists:hrms_roster_shift,id',
                'monday_shift_workday' => 'sometimes|nullable|exists:hrms_roster_shift,id',
                'monday_shift_public_holiday' => 'sometimes|nullable|exists:hrms_roster_shift,id',
                'monday_shift_offday' => 'sometimes|nullable|exists:hrms_roster_shift,id',
                'monday_shift_company_halfoffday' => 'sometimes|nullable|exists:hrms_roster_shift,id',
                'tuesday_shift_workday' => 'sometimes|nullable|exists:hrms_roster_shift,id',
                'tuesday_shift_public_holiday' => 'sometimes|nullable|exists:hrms_roster_shift,id',
                'tuesday_shift_offday' => 'sometimes|nullable|exists:hrms_roster_shift,id',
                'tuesday_shift_company_halfoffday' => 'sometimes|nullable|exists:hrms_roster_shift,id',
                'wednesday_shift_workday' => 'sometimes|nullable|exists:hrms_roster_shift,id',
                'wednesday_shift_public_holiday' => 'sometimes|nullable|exists:hrms_roster_shift,id',
                'wednesday_shift_offday' => 'sometimes|nullable|exists:hrms_roster_shift,id',
                'wednesday_shift_company_halfoffday' => 'sometimes|nullable|exists:hrms_roster_shift,id',
                'thursday_shift_workday' => 'sometimes|nullable|exists:hrms_roster_shift,id',
                'thursday_shift_public_holiday' => 'sometimes|nullable|exists:hrms_roster_shift,id',
                'thursday_shift_offday' => 'sometimes|nullable|exists:hrms_roster_shift,id',
                'thursday_shift_company_halfoffday' => 'sometimes|nullable|exists:hrms_roster_shift,id',
                'friday_shift_workday' => 'sometimes|nullable|exists:hrms_roster_shift,id',
                'friday_shift_public_holiday' => 'sometimes|nullable|exists:hrms_roster_shift,id',
                'friday_shift_offday' => 'sometimes|nullable|exists:hrms_roster_shift,id',
                'friday_shift_company_halfoffday' => 'sometimes|nullable|exists:hrms_roster_shift,id',
                'saturday_shift_workday' => 'sometimes|nullable|exists:hrms_roster_shift,id',
                'saturday_shift_public_holiday' => 'sometimes|nullable|exists:hrms_roster_shift,id',
                'saturday_shift_offday' => 'nullable|exists:hrms_roster_shift,id',
                'saturday_shift_company_halfoffday' => 'sometimes|nullable|exists:hrms_roster_shift,id',
                'effective_date' => 'sometimes|required|date',
                'status' => 'sometimes|required|in:active,disabled'
            ]);

            $roster->update($validatedData);

            return response()->json([
                'status' => 'success',
                'message' => 'Roster updated successfully.',
                'data' => $roster
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating roster: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while updating the roster.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified roster from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $roster = HRMSRoster::find($id);

            if (!$roster) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Roster not found.'
                ], 404);
            }

            $roster->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Roster deleted successfully.'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error deleting roster: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while deleting the roster.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate roster and daily assignments for a specific year.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function generateForYear(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'year' => 'required|integer',
                'branch_id' => 'required|exists:branch,id',
                'roster_group_id' => 'required|exists:hrms_roster_group,id',
                'default_roster_shift_workday' => 'required|exists:hrms_roster_shift,id',
                'default_roster_shift_public_holiday' => 'required|exists:hrms_roster_shift,id',
                'default_roster_shift_offday' => 'required|exists:hrms_roster_shift,id',
                'default_roster_shift_company_halfoffday' => 'required|exists:hrms_roster_shift,id',
                'effective_date' => 'required|date',
                'status' => 'required|in:active,disabled'
            ]);

            $year = $request->year;
            $branchId = $request->branch_id;
            $rosterGroupId = $request->roster_group_id;

            // Check if roster already exists for the year
            $roster = HRMSRoster::where('year', $year)->where('branch_id', $branchId)->first();
            if ($roster) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Roster for this year and branch already exists.'
                ], 422);
            }

            // Create the roster
            $roster = HRMSRoster::create([
                'branch_id' => $branchId,
                'year' => $year,
                'roster_group_id' => $rosterGroupId,
                'default_roster_shift_workday' => $request->default_roster_shift_workday,
                'default_roster_shift_public_holiday' => $request->default_roster_shift_public_holiday,
                'default_roster_shift_offday' => $request->default_roster_shift_offday,
                'default_roster_shift_company_halfoffday' => $request->default_roster_shift_company_halfoffday,
                'sunday_shift_workday' => $request->default_roster_shift_offday, // Example: Sundays off
                'sunday_shift_offday' => $request->default_roster_shift_offday,
                'effective_date' => $request->effective_date,
                'status' => $request->status
            ]);

            // Get all holidays and off days for the year
            $holidays = HRMSHoliday::whereYear('holiday_date', $year)->where('status', 'active')->get()->pluck('holiday_date')->toArray();
            $offdays = HRMSOffday::where('status', 'active')
                ->whereYear('holiday_date', $year)
                ->orWhere(function ($query) use ($year) {
                    $query->where('recurring_interval', '!=', 'one time')
                        ->whereYear('effective_date', '<=', $year)
                        ->where(function ($q) use ($year) {
                            $q->whereNull('recurring_end_date')
                                ->orWhereYear('recurring_end_date', '>=', $year);
                        });
                })
                ->get();

            // Generate daily assignments
            $startDate = Carbon::create($year, 1, 1);
            $endDate = Carbon::create($year, 12, 31);
            $period = CarbonPeriod::create($startDate, $endDate);
            $assignments = [];

            foreach ($period as $date) {
                $dayOfWeek = strtolower($date->format('l'));
                $dayType = 'workday';
                $shiftId = $roster->default_roster_shift_workday;

                // Check if it's a holiday
                if (in_array($date->toDateString(), $holidays)) {
                    $dayType = 'public_holiday';
                    $shiftId = $roster->default_roster_shift_public_holiday;
                } else {
                    // Check if it's an off day
                    foreach ($offdays as $offday) {
                        if ($this->isOffdayMatch($offday, $date)) {
                            $dayType = 'offday';
                            $shiftId = $roster->default_roster_shift_offday;
                            break;
                        }
                    }

                    // Check day-specific shift (e.g., Sunday)
                    $daySpecificShift = $roster->{$dayOfWeek . '_shift_' . $dayType};
                    if ($daySpecificShift) {
                        $shiftId = $daySpecificShift;
                    }
                }

                // Create assignment for the roster group (not staff-specific)
                $assignments[] = [
                    'roster_id' => $roster->id,
                    'roster_date' => $date->toDateString(),
                    'day_type' => $dayType,
                    'shift_id' => $shiftId,
                    'is_override' => false,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            // Batch insert assignments
            HRMSRosterDayAssignments::insert($assignments);

            return response()->json([
                'status' => 'success',
                'message' => 'Roster and daily assignments generated successfully for ' . $year,
                'data' => [
                    'roster' => $roster,
                    'assignment_count' => count($assignments)
                ]
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error generating roster for year: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while generating the roster.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if an off day matches the given date based on its recurrence.
     *
     * @param HRMSOffday $offday
     * @param Carbon $date
     * @return bool
     */
    private function isOffdayMatch(HRMSOffday $offday, Carbon $date): bool
    {
        $effectiveDate = Carbon::parse($offday->effective_date);
        $endDate = $offday->recurring_end_date ? Carbon::parse($offday->recurring_end_date) : null;

        // Check if date is within effective range
        if ($date->lt($effectiveDate) || ($endDate && $date->gt($endDate))) {
            return false;
        }

        if ($offday->recurring_interval === 'one time') {
            return $date->toDateString() === $offday->holiday_date;
        }

        $intervalMap = [
            'weekly' => '1 week',
            'monthly' => '1 month',
            'quarterly' => '3 months',
            'annually' => '1 year'
        ];

        $interval = $intervalMap[$offday->recurring_interval] ?? null;
        if (!$interval) {
            return false;
        }

        $period = CarbonPeriod::create($effectiveDate, $interval, $endDate ?: $date);
        foreach ($period as $periodDate) {
            if ($periodDate->toDateString() === $date->toDateString()) {
                return true;
            }
        }

        return false;
    }
}
