<?php

namespace Modules\HRMS\Http\Controllers\Roster;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\HRMS\Models\HRMSRoster;
use Modules\HRMS\Models\HRMSRosterShift;
use Modules\HRMS\Models\HRMSStaffRosterGroupAssignment;
use Illuminate\Validation\Rule;

class HRMSRosterController extends Controller
{
    // SHIFT APIs
    public function listShifts(): JsonResponse
    {
        $data = HRMSRosterShift::with('branch')->orderBy('name')->get();
        return response()->json($data);
    }

    public function getShift($id): JsonResponse
    {
        $shift = HRMSRosterShift::findOrFail($id);
        return response()->json($shift);
    }

    public function createShift(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|unique:hrms_roster_shift,name',
            'time_in' => 'required|date_format:H:i:s',
            'time_out' => 'required|date_format:H:i:s',
            'break_time_in' => 'nullable|date_format:H:i:s',
            'break_time_out' => 'nullable|date_format:H:i:s',
            'has_lunch_break' => 'required|boolean',
            'break_minutes' => 'nullable|integer|min:0',
            'second_break_time_in' => 'nullable|date_format:H:i:s',
            'second_break_time_out' => 'nullable|date_format:H:i:s',
            'second_break_minutes' => 'nullable|integer|min:0',
            'is_lunch_break' => 'required|boolean',
            'ot_time_in' => 'nullable|date_format:H:i:s',
            'ot_time_out' => 'nullable|date_format:H:i:s',
            'ot_work_minutes' => 'nullable|integer|min:0',
            'full_shift' => 'required|boolean',
            'flexi' => 'required|boolean',
            'late_offset_ot' => 'required|boolean',
            'alt_shift' => 'nullable|string',
            'ot1_component' => 'nullable|string',
            'ot2_component' => 'nullable|string',
            'ot2_component_hours' => 'nullable|integer|min:0',
            'late_in_rounding_minutes' => 'nullable|integer|min:0',
            'early_out_rounding_minutes' => 'nullable|integer|min:0',
            'break_late_in_rounding_minutes' => 'nullable|integer|min:0',
            'break_late_in_minimum_minutes' => 'nullable|integer|min:0',
            'ot_round_down_minutes' => 'nullable|integer|min:0',
            'ot_round_up_adj_minutes' => 'nullable|integer|min:0',
            'ot_minimum_minutes' => 'nullable|integer|min:0',
            'ot_days' => 'nullable|integer|min:0',
            'type_for_leave' => 'nullable|string',
            'status' => ['required', Rule::in(['active', 'disabled'])],
            'branch_id' => 'nullable|exists:branch,id',
            'allowed_thumbprint_once' => 'required|boolean',
            'background_color' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        $shift = HRMSRosterShift::create($data);
        return response()->json($shift, 201);
    }

    public function updateShift(Request $request, $id): JsonResponse
    {
        $shift = HRMSRosterShift::findOrFail($id);

        $data = $request->validate([
            'name' => ['sometimes', 'string', Rule::unique('hrms_roster_shift')->ignore($shift->id)],
            'time_in' => 'sometimes|date_format:H:i:s',
            'time_out' => 'sometimes|date_format:H:i:s',
            'break_time_in' => 'nullable|date_format:H:i:s',
            'break_time_out' => 'nullable|date_format:H:i:s',
            'has_lunch_break' => 'sometimes|boolean',
            'break_minutes' => 'nullable|integer|min:0',
            'second_break_time_in' => 'nullable|date_format:H:i:s',
            'second_break_time_out' => 'nullable|date_format:H:i:s',
            'second_break_minutes' => 'nullable|integer|min:0',
            'is_lunch_break' => 'sometimes|boolean',
            'ot_time_in' => 'nullable|date_format:H:i:s',
            'ot_time_out' => 'nullable|date_format:H:i:s',
            'ot_work_minutes' => 'nullable|integer|min:0',
            'full_shift' => 'sometimes|boolean',
            'flexi' => 'sometimes|boolean',
            'late_offset_ot' => 'sometimes|boolean',
            'alt_shift' => 'nullable|string',
            'ot1_component' => 'nullable|string',
            'ot2_component' => 'nullable|string',
            'ot2_component_hours' => 'nullable|integer|min:0',
            'late_in_rounding_minutes' => 'nullable|integer|min:0',
            'early_out_rounding_minutes' => 'nullable|integer|min:0',
            'break_late_in_rounding_minutes' => 'nullable|integer|min:0',
            'break_late_in_minimum_minutes' => 'nullable|integer|min:0',
            'ot_round_down_minutes' => 'nullable|integer|min:0',
            'ot_round_up_adj_minutes' => 'nullable|integer|min:0',
            'ot_minimum_minutes' => 'nullable|integer|min:0',
            'ot_days' => 'nullable|integer|min:0',
            'type_for_leave' => 'nullable|string',
            'status' => ['sometimes', Rule::in(['active', 'disabled'])],
            'branch_id' => 'nullable|exists:branch,id',
            'allowed_thumbprint_once' => 'sometimes|boolean',
            'background_color' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);
        $shift->update($data);
        return response()->json($shift);
    }

    public function deleteShift($id): JsonResponse
    {
        HRMSRosterShift::findOrFail($id)->delete();
        return response()->json(['message' => 'Shift deleted.']);
    }


    // ROSTER APIs
    public function listRosters(): JsonResponse
    {
        $data = HRMSRoster::with('branch')->get();
        return response()->json($data);
    }

    public function getRoster($id): JsonResponse
    {
        $roster = HRMSRoster::findOrFail($id);
        return response()->json($roster);
    }

    public function createRoster(Request $request): JsonResponse
    {
        $data = $request->validate([
            'branch_id' => 'nullable|exists:branch,id',
            'year' => 'required|date',
            'effective_date' => 'required|date',
            // Default shifts
            'default_roster_shift_workday' => 'nullable|exists:hrms_roster_shift,id',
            'default_roster_shift_public_holiday' => 'nullable|exists:hrms_roster_shift,id',
            'default_roster_shift_offday' => 'nullable|exists:hrms_roster_shift,id',
            'default_roster_shift_company_halfoffday' => 'nullable|exists:hrms_roster_shift,id',

            // Sunday
            'sunday_shift_workday' => 'nullable|exists:hrms_roster_shift,id',
            'sunday_shift_public_holiday' => 'nullable|exists:hrms_roster_shift,id',
            'sunday_shift_offday' => 'nullable|exists:hrms_roster_shift,id',
            'sunday_shift_company_halfoffday' => 'nullable|exists:hrms_roster_shift,id',

            // Monday
            'monday_shift_workday' => 'nullable|exists:hrms_roster_shift,id',
            'monday_shift_public_holiday' => 'nullable|exists:hrms_roster_shift,id',
            'monday_shift_offday' => 'nullable|exists:hrms_roster_shift,id',
            'monday_shift_company_halfoffday' => 'nullable|exists:hrms_roster_shift,id',

            // Tuesday
            'tuesday_shift_workday' => 'nullable|exists:hrms_roster_shift,id',
            'tuesday_shift_public_holiday' => 'nullable|exists:hrms_roster_shift,id',
            'tuesday_shift_offday' => 'nullable|exists:hrms_roster_shift,id',
            'tuesday_shift_company_halfoffday' => 'nullable|exists:hrms_roster_shift,id',

            // Wednesday
            'wednesday_shift_workday' => 'nullable|exists:hrms_roster_shift,id',
            'wednesday_shift_public_holiday' => 'nullable|exists:hrms_roster_shift,id',
            'wednesday_shift_offday' => 'nullable|exists:hrms_roster_shift,id',
            'wednesday_shift_company_halfoffday' => 'nullable|exists:hrms_roster_shift,id',

            // Thursday
            'thursday_shift_workday' => 'nullable|exists:hrms_roster_shift,id',
            'thursday_shift_public_holiday' => 'nullable|exists:hrms_roster_shift,id',
            'thursday_shift_offday' => 'nullable|exists:hrms_roster_shift,id',
            'thursday_shift_company_halfoffday' => 'nullable|exists:hrms_roster_shift,id',

            // Friday
            'friday_shift_workday' => 'nullable|exists:hrms_roster_shift,id',
            'friday_shift_public_holiday' => 'nullable|exists:hrms_roster_shift,id',
            'friday_shift_offday' => 'nullable|exists:hrms_roster_shift,id',
            'friday_shift_company_halfoffday' => 'nullable|exists:hrms_roster_shift,id',

            // Saturday
            'saturday_shift_workday' => 'nullable|exists:hrms_roster_shift,id',
            'saturday_shift_public_holiday' => 'nullable|exists:hrms_roster_shift,id',
            'saturday_shift_offday' => 'nullable|exists:hrms_roster_shift,id',
            'saturday_shift_company_halfoffday' => 'nullable|exists:hrms_roster_shift,id',
        ]);

        $roster = HRMSRoster::create($data);
        return response()->json($roster, 201);
    }

    public function updateRoster(Request $request, $id): JsonResponse
    {
        $roster = HRMSRoster::findOrFail($id);

        $data = $request->validate([
            'branch_id' => 'nullable|exists:branch,id',
            'year' => 'sometimes|date',
            'effective_date' => 'sometimes|date',
            // Default shifts
            'default_roster_shift_workday' => 'sometimes|nullable|exists:hrms_roster_shift,id',
            'default_roster_shift_public_holiday' => 'sometimes|nullable|exists:hrms_roster_shift,id',
            'default_roster_shift_offday' => 'sometimes|nullable|exists:hrms_roster_shift,id',
            'default_roster_shift_company_halfoffday' => 'sometimes|nullable|exists:hrms_roster_shift,id',

            // Weekly shift overrides
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
            'saturday_shift_offday' => 'sometimes|nullable|exists:hrms_roster_shift,id',
            'saturday_shift_company_halfoffday' => 'sometimes|nullable|exists:hrms_roster_shift,id',
        ]);

        $roster->update($data);
        return response()->json($roster);
    }

    public function deleteRoster($id): JsonResponse
    {
        HRMSRoster::findOrFail($id)->delete();
        return response()->json(['message' => 'Roster deleted.']);
    }


    // ASSIGNMENT APIs
    public function listAssignments(): JsonResponse
    {
        $data = HRMSStaffRosterGroupAssignment::with(['staff', 'rosterGroup'])->get();
        return response()->json($data);
    }

    public function getAssignment($id): JsonResponse
    {
        $assignment = HRMSStaffRosterGroupAssignment::findOrFail($id);
        return response()->json($assignment);
    }

    public function createAssignment(Request $request): JsonResponse
    {
        $data = $request->validate([
            'hrms_staff_id' => 'required|exists:hrms_staff,id',
            'roster_group_id' => 'required|exists:hrms_roster_group,id',
            'effective_date' => 'required|date',
        ]);

        $assignment = HRMSStaffRosterGroupAssignment::create($data);
        return response()->json($assignment, 201);
    }

    public function updateAssignment(Request $request, $id): JsonResponse
    {
        $assignment = HRMSStaffRosterGroupAssignment::findOrFail($id);

        $data = $request->validate([
            'hrms_staff_id' => 'sometimes|exists:hrms_staff,id',
            'roster_group_id' => 'sometimes|exists:hrms_roster_group,id',
            'effective_date' => 'sometimes|date',
        ]);

        $assignment->update($data);
        return response()->json($assignment);
    }

    public function deleteAssignment($id): JsonResponse
    {
        HRMSStaffRosterGroupAssignment::findOrFail($id)->delete();
        return response()->json(['message' => 'Assignment deleted.']);
    }

    public function createMultipleAssignments(Request $request): JsonResponse
    {
        $data = $request->validate([
            'assignments' => 'required|array|min:1',
            'assignments.*.hrms_staff_id' => 'required|exists:hrms_staff,id',
            'assignments.*.roster_group_id' => 'required|exists:hrms_roster_group,id',
            'assignments.*.effective_date' => 'required|date',
        ]);

        $createdAssignments = [];

        foreach ($data['assignments'] as $assignmentData) {
            $createdAssignments[] = HRMSStaffRosterGroupAssignment::create($assignmentData);
        }

        return response()->json([
            'message' => 'Assignments created successfully.',
            'data' => $createdAssignments,
        ], 201);
    }
}
