<?php

namespace Modules\HRMS\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\HRMS\Models\HRMSAppraisalType;

class HRMSAppraisalTypeController extends Controller
{
    public function index()
    {
        $appraisalTypes = HRMSAppraisalType::all();

        return response()->json([
            'success' => true,
            'data' => $appraisalTypes
        ]);
    }

    public function show($id)
    {
        $appraisalType = HRMSAppraisalType::find($id);

        if (!$appraisalType) {
            return response()->json([
                'success' => false,
                'message' => 'Appraisal Type not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $appraisalType
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:hrms_appraisal_type,name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $appraisalType = HRMSAppraisalType::create([
            'name' => $request->name,
            'is_active' => $request->has('is_active'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Appraisal Type created successfully',
            'data' => $appraisalType
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $appraisalType = HRMSAppraisalType::find($id);

        if (!$appraisalType) {
            return response()->json([
                'success' => false,
                'message' => 'Appraisal Type not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:hrms_appraisal_type,name,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $appraisalType->update([
            'name' => $request->name,
            'is_active' => $request->has('is_active'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Appraisal Type updated successfully',
            'data' => $appraisalType
        ]);
    }

    public function destroy($id)
    {
        $appraisalType = HRMSAppraisalType::find($id);

        if (!$appraisalType) {
            return response()->json([
                'success' => false,
                'message' => 'Appraisal Type not found'
            ], 404);
        }

        $appraisalType->delete();

        return response()->json([
            'success' => true,
            'message' => 'Appraisal Type deleted successfully'
        ]);
    }
}
