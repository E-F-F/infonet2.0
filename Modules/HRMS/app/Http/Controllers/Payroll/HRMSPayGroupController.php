<?php

namespace Modules\HRMS\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\HRMS\Models\HRMSPayGroup;

class HRMSPayGroupController extends Controller
{
    public function index()
    {
        $payGroups = HRMSPayGroup::all();
        
        return response()->json($payGroups);
    }

    public function show($id)
    {
        $payGroup = HRMSPayGroup::findOrFail($id);
        return response()->json($payGroup);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:hrms_pay_group,name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Name has been taken',
            ], 422);
        }

        $payGroup = HRMSPayGroup::create([
            'name' => $request->name,
            'is_active' => $request->has('is_active'), // Check if checkbox was sent
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pay Group added successfully!',
            'leaveRank' => $payGroup
        ]);
    }

    public function update(Request $request, $id)
    {
        $payGroup = HRMSPayGroup::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:hrms_pay_group,name,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Name has been taken',
            ], 422);
        }

        $payGroup->update([
            'name' => $request->name,
        ]);

        return response()->json(['success' => true, 'payGroup' => $payGroup]);
    }

    public function destroy($id)
    {
        $leaveRank = HRMSPayGroup::findOrFail($id);
        $leaveRank->delete();

        return response()->json(['success' => true, 'message' => 'Pay Group deleted successfully']);
    }
}
