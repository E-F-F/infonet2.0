<?php

namespace Modules\HRMS\Http\Controllers\Leave;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\HRMS\Models\HRMSLeaveRank;

class HRMSLeaveRankController extends Controller
{
    public function index()
    {
        $leaveRanks = HRMSLeaveRank::all();
        return view('hrms::leave_management.leave_ranks.index', compact('leaveRanks'));
    }

    public function show($id)
    {
        $leaveRank = HRMSLeaveRank::findOrFail($id);
        return response()->json($leaveRank);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:hrms_leave_rank,name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Name has been taken',
            ], 422);
        }

        $leaveRank = HRMSLeaveRank::create([
            'name' => $request->name,
            'is_active' => $request->has('is_active'), // Check if checkbox was sent
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Leave Rank added successfully!',
            'leaveRank' => $leaveRank
        ]);
    }

    public function update(Request $request, $id)
    {
        $leaveRank = HRMSLeaveRank::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:hrms_leave_rank,name,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Name has been taken',
            ], 422);
        }

        $leaveRank->update([
            'name' => $request->name,
        ]);

        return response()->json(['success' => true, 'leaveRank' => $leaveRank]);
    }

    public function destroy($id)
    {
        $leaveRank = HRMSLeaveRank::findOrFail($id);
        $leaveRank->delete();

        return response()->json(['success' => true, 'message' => 'Leave rank deleted successfully']);
    }
}
