<?php

namespace Modules\HRMS\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\HRMS\Models\HRMSStaffQualification;
use Modules\HRMS\Models\HRMSStaff;

class HRMSStaffQualificationController extends Controller
{
    /**
     * Get all qualifications for a specific staff.
     */
    public function getByStaff($staffId)
    {
        $staff = HRMSStaff::find($staffId);

        if (!$staff) {
            return response()->json([
                'status' => false,
                'message' => 'Staff not found'
            ], 404);
        }

        $qualifications = HRMSStaffQualification::where('hrms_staff_id', $staffId)->get();

        return response()->json([
            'status' => true,
            'staff_id' => $staffId,
            'data' => $qualifications
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
}
