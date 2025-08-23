<?php

namespace Modules\HRMS\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\HRMS\Models\HRMSStaffQualification;
use Modules\HRMS\Models\HRMSStaff;
use Modules\HRMS\Models\HRMSStaffEmploymentHistory;


class HRMSStaffQualificationController extends Controller
{
    /**
     * Get all qualifications for a specific staff.
     */
    public function getByStaff($staffId)
    {
        $staff = HRMSStaff::with([
            'qualifications', 
            'employmentHistory', 
            'trainingParticipants' => function ($query) {
                $query->with('training', 'training.trainingType');
            }
            ])->find($staffId);

        if (!$staff) {
            return response()->json([
                'status' => false,
                'message' => 'Staff not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $staff
        ], 200);
    }

    /**
     * Add a qualification for a specific staff.
     */
    public function storeForStaff(Request $request, $staffId)
    {
        $staff = HRMSStaff::find($staffId);

        if (!$staff) {
            return response()->json([
                'status' => false,
                'message' => 'Staff not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'qualification' => 'required|string|max:255',
            'institution'   => 'nullable|string|max:255',
            'start_date'    => 'nullable|date',
            'end_date'      => 'nullable|date|after_or_equal:start_date',
            'marks_grade'   => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $qualification = HRMSStaffQualification::create([
            'hrms_staff_id' => $staffId,
            'qualification' => $request->qualification,
            'institution'   => $request->institution,
            'start_date'    => $request->start_date,
            'end_date'      => $request->end_date,
            'marks_grade'   => $request->marks_grade,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Qualification added successfully',
            'data' => $qualification
        ], 201);
    }

    /**
     * Get a specific qualification for a staff.
     */
    public function show($qualificationId)
    {
        $qualification = HRMSStaffQualification::find($qualificationId);

        if (!$qualification) {
            return response()->json([
                'status' => false,
                'message' => 'Qualification not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $qualification
        ], 200);
    }

    /**
     * Update a qualification .
     */
    public function update(Request $request, $qualificationId)
    {
        $qualification = HRMSStaffQualification::find($qualificationId);

        if (!$qualification) {
            return response()->json([
                'status' => false,
                'message' => 'Qualification not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'qualification' => 'required|string|max:255',
            'institution'   => 'nullable|string|max:255',
            'start_date'    => 'nullable|date',
            'end_date'      => 'nullable|date|after_or_equal:start_date',
            'marks_grade'   => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $qualification->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Qualification updated successfully',
            'data' => $qualification
        ], 200);
    }

     /**
     * Store new employment history
     */
    public function store(Request $request, $staffId)
    {
        $validator = Validator::make($request->all(), [
            'organization' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'comment' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $staff = HRMSStaff::find($staffId);

        if (!$staff) {
            return response()->json([
                'status' => false,
                'message' => 'Staff not found'
            ], 404);
        }

        $history = HRMSStaffEmploymentHistory::create([
            'hrms_staff_id' => $staffId,
            'organization' => $request->organization,
            'position' => $request->position,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'comment' => $request->comment,
        ]);

        return response()->json([
            'message' => 'Employment history added successfully',
            'data' => $history
        ], 201);
    }

    /**
     * Update an employment history record
     */
    public function updateEH(Request $request, $id)
    {
        $history = HRMSStaffEmploymentHistory::find($id);

        if (!$history) {
            return response()->json([
                'status' => false,
                'message' => 'Employment history not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'organization' => 'sometimes|required|string|max:255',
            'position' => 'sometimes|required|string|max:255',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'comment' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $history->update($request->only([
            'organization',
            'position',
            'start_date',
            'end_date',
            'comment'
        ]));

        return response()->json([
            'status' => true,
            'message' => 'Employment history updated successfully',
            'data' => $history
        ], 200);
    }

    /**
     * Show a single employment history record
     */
    public function showEH($id)
    {
        $history = HRMSStaffEmploymentHistory::find($id);

        if (!$history) {
            return response()->json([
                'status' => false,
                'message' => 'Employment history not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $history
        ], 200);
    }
}
