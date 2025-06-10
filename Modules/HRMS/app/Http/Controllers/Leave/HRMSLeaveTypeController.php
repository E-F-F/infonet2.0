<?php

namespace Modules\HRMS\Http\Controllers\Leave;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\HRMS\Models\HRMSLeaveType;

class HRMSLeaveTypeController extends Controller
{
    public function index()
    {
        $leaveTypes = HRMSLeaveType::all();
        return view('hrms::leave_management.leave_types.index', compact('leaveTypes'));
    }

    public function show($id)
    {
        $leaveType = HRMSLeaveType::findOrFail($id);
        return response()->json($leaveType);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:hrms_leave_type,name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Name has been taken',
            ], 422);
        }

        $leaveType = HRMSLeaveType::create([
            'name' => $request->name,
            'is_active' => $request->has('is_active'), // Check if checkbox was sent
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Leave type added successfully!',
            'leaveType' => $leaveType
        ]);
    }

    public function update(Request $request, $id)
    {
        $leaveType = HRMSLeaveType::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:hrms_leave_type,name,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Name has been taken',
            ], 422);
        }

        $leaveType->update([
            'name' => $request->name,
        ]);

        return response()->json(['success' => true, 'leaveType' => $leaveType]);
    }

    public function destroy($id)
    {
        $leaveType = HRMSLeaveType::findOrFail($id);
        $leaveType->delete();

        return response()->json(['success' => true, 'message' => 'Leave type deleted successfully']);
    }
}
