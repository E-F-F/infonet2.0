<?php

namespace Modules\HRMS\Http\Controllers\Roster;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Modules\HRMS\Models\HRMSRoster;
use Modules\HRMS\Models\HRMSRosterDayAssignments;
use Modules\HRMS\Models\HRMSRosterShift;
use Modules\HRMS\Models\HRMSStaff;

class HRMSRosterDayAssignmentsController extends Controller
{
    /**
     * Display a listing of the roster day assignments.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = HRMSRosterDayAssignments::query();

            // Filter by roster_id if provided
            if ($request->has('roster_id')) {
                $query->where('roster_id', $request->input('roster_id'));
            }

            // Filter by hrms_staff_id if provided
            if ($request->has('hrms_staff_id')) {
                $query->where('hrms_staff_id', $request->input('hrms_staff_id'));
            }

            // Filter by roster_date range if provided
            if ($request->has('start_date') && $request->has('end_date')) {
                $query->whereBetween('roster_date', [$request->input('start_date'), $request->input('end_date')]);
            }

            // Filter by day_type if provided
            if ($request->has('day_type')) {
                $query->where('day_type', $request->input('day_type'));
            }

            // Pagination
            $perPage = $request->input('per_page', 10);
            $assignments = $query->with(['roster', 'shift', 'staff'])
                ->paginate($perPage);

            return response()->json([
                'status' => 'success',
                'message' => 'Roster day assignments retrieved successfully.',
                'data' => $assignments
            ]);
        } catch (\Exception $e) {
            Log::error('Error retrieving roster day assignments: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while retrieving roster day assignments.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created roster day assignment in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'roster_id' => 'required|exists:hrms_roster,id',
                'roster_date' => 'required|date',
                'day_type' => 'required|in:workday,public_holiday,offday,company_halfoffday',
                'shift_id' => 'required|exists:hrms_roster_shift,id',
                'is_override' => 'boolean',
                'hrms_staff_id' => 'nullable|exists:hrms_staff,id',
            ]);

            // Check for unique constraint on roster_id, roster_date, and hrms_staff_id
            $existingAssignment = HRMSRosterDayAssignments::where([
                'roster_id' => $request->roster_id,
                'roster_date' => $request->roster_date,
                'hrms_staff_id' => $request->hrms_staff_id
            ])->exists();

            if ($existingAssignment) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'An assignment already exists for this roster, date, and staff.'
                ], 422);
            }

            $assignment = HRMSRosterDayAssignments::create($validatedData);

            return response()->json([
                'status' => 'success',
                'message' => 'Roster day assignment created successfully.',
                'data' => $assignment
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating roster day assignment: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while creating the roster day assignment.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified roster day assignment.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $assignment = HRMSRosterDayAssignments::with(['roster', 'shift', 'staff'])->find($id);

            if (!$assignment) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Roster day assignment not found.'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Roster day assignment retrieved successfully.',
                'data' => $assignment
            ]);
        } catch (\Exception $e) {
            Log::error('Error retrieving roster day assignment: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while retrieving the roster day assignment.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
