<?php

namespace Modules\HRMS\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Modules\HRMS\Models\HRMSStaffRosterGroupAssignment;
use Modules\HRMS\Models\HRMSStaff;
use Modules\HRMS\Models\HRMSRosterGroup;

class HRMSStaffRosterGroupAssignmentController extends Controller
{
    /**
     * List all roster group assignments (paginated).
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $assignments = HRMSStaffRosterGroupAssignment::with(['staff', 'rosterGroup'])
            ->orderBy('effective_date', 'desc')
            ->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data'   => $assignments
        ], Response::HTTP_OK);
    }

    /**
     * Store a new roster group assignment.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hrms_staff_id'  => 'required|exists:hrms_staff,id',
            'roster_group_id' => 'required|exists:hrms_roster_group,id',
            'effective_date'  => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $assignment = DB::transaction(function () use ($request) {
            return HRMSStaffRosterGroupAssignment::create([
                'hrms_staff_id'  => $request->hrms_staff_id,
                'roster_group_id' => $request->roster_group_id,
                'effective_date'  => $request->effective_date
            ]);
        });

        return response()->json([
            'status'  => 'success',
            'message' => 'Roster group assignment created successfully',
            'data'    => $assignment
        ], Response::HTTP_CREATED);
    }

    /**
     * Show a specific roster group assignment.
     */
    public function show($id)
    {
        $assignment = HRMSStaffRosterGroupAssignment::with(['staff', 'rosterGroup'])->find($id);

        if (!$assignment) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Roster group assignment not found'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'status' => 'success',
            'data'   => $assignment
        ], Response::HTTP_OK);
    }

    /**
     * Update a roster group assignment.
     */
    public function update(Request $request, $id)
    {
        $assignment = HRMSStaffRosterGroupAssignment::find($id);

        if (!$assignment) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Roster group assignment not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'hrms_staff_id'  => 'required|exists:hrms_staff,id',
            'roster_group_id' => 'required|exists:hrms_roster_groups,id',
            'effective_date'  => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        DB::transaction(function () use ($assignment, $request) {
            $assignment->update([
                'hrms_staff_id'  => $request->hrms_staff_id,
                'roster_group_id' => $request->roster_group_id,
                'effective_date'  => $request->effective_date
            ]);
        });

        return response()->json([
            'status'  => 'success',
            'message' => 'Roster group assignment updated successfully',
            'data'    => $assignment
        ], Response::HTTP_OK);
    }

    /**
     * Delete a roster group assignment.
     */
    public function destroy($id)
    {
        $assignment = HRMSStaffRosterGroupAssignment::find($id);

        if (!$assignment) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Roster group assignment not found'
            ], Response::HTTP_NOT_FOUND);
        }

        DB::transaction(function () use ($assignment) {
            $assignment->delete();
        });

        return response()->json([
            'status'  => 'success',
            'message' => 'Roster group assignment deleted successfully'
        ], Response::HTTP_OK);
    }

    public function getByStaff($staffId)
    {
        // Check if staff exists
        $staff = HRMSStaff::find($staffId);
        if (!$staff) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Staff not found'
            ], Response::HTTP_NOT_FOUND);
        }

        // Get roster group assignments with details
        $assignments = HRMSStaffRosterGroupAssignment::with('rosterGroup')
            ->where('hrms_staff_id', $staffId)
            ->orderBy('effective_date', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'staff'  => $staff,
            'data'   => $assignments
        ], Response::HTTP_OK);
    }
}
