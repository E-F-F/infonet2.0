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
use Modules\HRMS\Models\HRMSRosterDayAssignment;
use Modules\HRMS\Models\HRMSStaffRosterGroupAssignment;
use Modules\HRMS\Models\HRMSRosterShift; // Import the RosterShift model
use Illuminate\Validation\ValidationException; // Import ValidationException
use Illuminate\Support\Facades\Log;

class HRMSRosterShiftController extends Controller
{
    /**
     * Display a listing of the roster shifts.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = HRMSRosterShift::query();

            // Optional: Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->input('status'));
            }

            // Optional: Search by name
            if ($request->has('search')) {
                $searchTerm = $request->input('search');
                $query->where('name', 'like', '%' . $searchTerm . '%');
            }

            // Optional: Pagination
            $perPage = $request->input('per_page', 10);
            $shifts = $query->paginate($perPage);

            return response()->json([
                'message' => 'Roster shifts retrieved successfully.',
                'data' => $shifts
            ]);
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error retrieving roster shifts: ' . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred while retrieving roster shifts.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created roster shift in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255|unique:hrms_roster_shift,name',
                'time_in' => 'required|date_format:H:i:s',
                'time_out' => 'required|date_format:H:i:s|after:time_in',
                'break_time_in' => 'nullable|date_format:H:i:s',
                'break_time_out' => 'nullable|date_format:H:i:s|after:break_time_in',
                'has_lunch_break' => 'boolean',
                'break_minutes' => 'nullable|integer|min:0',
                'second_break_time_in' => 'nullable|date_format:H:i:s',
                'second_break_time_out' => 'nullable|date_format:H:i:s|after:second_break_time_in',
                'second_break_minutes' => 'nullable|integer|min:0',
                'is_lunch_break' => 'boolean',
                'ot_time_in' => 'nullable|date_format:H:i:s',
                'ot_time_out' => 'nullable|date_format:H:i:s|after:ot_time_in',
                'ot_work_minutes' => 'nullable|integer|min:0',
                'full_shift' => 'boolean',
                'flexi' => 'boolean',
                'late_offset_ot' => 'boolean',
                'alt_shift' => 'nullable|string|max:255',
                'ot1_component' => 'nullable|string|max:255',
                'ot2_component' => 'nullable|string|max:255',
                'ot2_component_hours' => 'nullable|integer|min:0',
                'late_in_rounding_minutes' => 'nullable|integer|min:0',
                'early_out_rounding_minutes' => 'nullable|integer|min:0',
                'break_late_in_rounding_minutes' => 'nullable|integer|min:0',
                'break_late_in_minimum_minutes' => 'nullable|integer|min:0',
                'ot_round_down_minutes' => 'nullable|integer|min:0',
                'ot_round_up_adj_minutes' => 'nullable|integer|min:0',
                'ot_minimum_minutes' => 'nullable|integer|min:0',
                'ot_days' => 'nullable|integer|min:0',
                'type_for_leave' => 'nullable|string|max:255',
                'status' => 'in:active,disabled',
                'branch_id' => 'nullable|exists:branch,id', // Assuming 'branch' is the table name for branches
                'allowed_thumbprint_once' => 'boolean',
                'background_color' => 'nullable|string|max:255',
                'remarks' => 'nullable|string',
            ]);

            $shift = HRMSRosterShift::create($validatedData);

            return response()->json([
                'message' => 'Roster shift created successfully.',
                'data' => $shift
            ], 201); // 201 Created
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 422); // 422 Unprocessable Entity
        } catch (\Exception $e) {
            Log::error('Error creating roster shift: ' . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred while creating the roster shift.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified roster shift.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $shift = HRMSRosterShift::find($id);

            if (!$shift) {
                return response()->json([
                    'message' => 'Roster shift not found.'
                ], 404); // 404 Not Found
            }

            return response()->json([
                'message' => 'Roster shift retrieved successfully.',
                'data' => $shift
            ]);
        } catch (\Exception $e) {
            Log::error('Error retrieving roster shift: ' . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred while retrieving the roster shift.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified roster shift in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $shift = HRMSRosterShift::find($id);

            if (!$shift) {
                return response()->json([
                    'message' => 'Roster shift not found.'
                ], 404);
            }

            $validatedData = $request->validate([
                'name' => 'required|string|max:255|unique:hrms_roster_shift,name,' . $id, // Unique except for itself
                'time_in' => 'required|date_format:H:i:s',
                'time_out' => 'required|date_format:H:i:s|after:time_in',
                'break_time_in' => 'nullable|date_format:H:i:s',
                'break_time_out' => 'nullable|date_format:H:i:s|after:break_time_in',
                'has_lunch_break' => 'boolean',
                'break_minutes' => 'nullable|integer|min:0',
                'second_break_time_in' => 'nullable|date_format:H:i:s',
                'second_break_time_out' => 'nullable|date_format:H:i:s|after:second_break_time_in',
                'second_break_minutes' => 'nullable|integer|min:0',
                'is_lunch_break' => 'boolean',
                'ot_time_in' => 'nullable|date_format:H:i:s',
                'ot_time_out' => 'nullable|date_format:H:i:s|after:ot_time_in',
                'ot_work_minutes' => 'nullable|integer|min:0',
                'full_shift' => 'boolean',
                'flexi' => 'boolean',
                'late_offset_ot' => 'boolean',
                'alt_shift' => 'nullable|string|max:255',
                'ot1_component' => 'nullable|string|max:255',
                'ot2_component' => 'nullable|string|max:255',
                'ot2_component_hours' => 'nullable|integer|min:0',
                'late_in_rounding_minutes' => 'nullable|integer|min:0',
                'early_out_rounding_minutes' => 'nullable|integer|min:0',
                'break_late_in_rounding_minutes' => 'nullable|integer|min:0',
                'break_late_in_minimum_minutes' => 'nullable|integer|min:0',
                'ot_round_down_minutes' => 'nullable|integer|min:0',
                'ot_round_up_adj_minutes' => 'nullable|integer|min:0',
                'ot_minimum_minutes' => 'nullable|integer|min:0',
                'ot_days' => 'nullable|integer|min:0',
                'type_for_leave' => 'nullable|string|max:255',
                'status' => 'in:active,disabled',
                'branch_id' => 'nullable|exists:branch,id',
                'allowed_thumbprint_once' => 'boolean',
                'background_color' => 'nullable|string|max:255',
                'remarks' => 'nullable|string',
            ]);

            $shift->update($validatedData);

            return response()->json([
                'message' => 'Roster shift updated successfully.',
                'data' => $shift
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating roster shift: ' . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred while updating the roster shift.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified roster shift from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $shift = HRMSRosterShift::find($id);

            if (!$shift) {
                return response()->json([
                    'message' => 'Roster shift not found.'
                ], 404);
            }

            $shift->delete();

            return response()->json([
                'message' => 'Roster shift deleted successfully.'
            ], 200); // 200 OK for successful deletion
        } catch (\Exception $e) {
            Log::error('Error deleting roster shift: ' . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred while deleting the roster shift.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
