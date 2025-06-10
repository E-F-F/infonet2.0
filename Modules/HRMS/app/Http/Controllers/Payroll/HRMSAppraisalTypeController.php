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
        return view('hrms::payroll_management.appraisal_types.index', compact('appraisalTypes'));
    }

    public function show($id)
    {
        $appraisalType = HRMSAppraisalType::findOrFail($id);
        return response()->json($appraisalType);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:hrms_appraisal_type,name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Name has been taken',
            ], 422);
        }

        $appraisalType = HRMSAppraisalType::create([
            'name' => $request->name,
            'is_active' => $request->has('is_active'), // Check if checkbox was sent
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Appraisal Type added successfully!',
            'leaveRank' => $appraisalType
        ]);
    }

    public function update(Request $request, $id)
    {
        $appraisalType = HRMSAppraisalType::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:hrms_appraisal_type,name,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Name has been taken',
            ], 422);
        }

        $appraisalType->update([
            'name' => $request->name,
        ]);

        return response()->json(['success' => true, 'appraisalType' => $appraisalType]);
    }

    public function destroy($id)
    {
        $appraisalType = HRMSAppraisalType::findOrFail($id);
        $appraisalType->delete();

        return response()->json(['success' => true, 'message' => 'Appraisal Type deleted successfully']);
    }
}
