<?php

namespace Modules\HRMS\Http\Controllers\Leave;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\HRMS\Models\HRMSLeaveRank;
use Illuminate\Support\Facades\DB; // Added for transaction handling

/**
 * HRMSLeaveRankController
 *
 * This API controller manages CRUD operations for HRMSLeaveRank records.
 * It handles validation, creation, retrieval, updating, and deletion of leave ranks.
 */
class HRMSLeaveRankController extends Controller
{
    /**
     * Display a listing of the leave ranks.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $query= HRMSLeaveRank::query();
        $leaveRanks = $query->paginate($perPage);
        return response()->json($leaveRanks);
    }

    /**
     * Display the specified leave rank.
     *
     * @param  int  $id The ID of the leave rank.
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $leaveRank = HRMSLeaveRank::findOrFail($id); // Singular variable name
        return response()->json($leaveRank);
    }

    /**
     * Store a newly created leave rank in storage.
     *
     * @param  \Illuminate\Http\Request  $request The incoming request.
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Define validation rules for creating a leave rank
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:hrms_leave_rank,name|max:255',
            'is_active' => 'boolean', // Validate that is_active is a boolean if present
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }

        try {
            DB::beginTransaction();

            $leaveRank = HRMSLeaveRank::create([
                'name' => $request->name,
                'is_active' => $request->has('is_active') ? (bool)$request->is_active : false, // Explicitly set to false if not present
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Leave Rank added successfully!',
                'data' => $leaveRank
            ], 201); // 201 Created
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create leave rank.',
                'error' => $e->getMessage()
            ], 500); // 500 Internal Server Error
        }
    }

    /**
     * Update the specified leave rank in storage.
     *
     * @param  \Illuminate\Http\Request  $request The incoming request.
     * @param  int  $id The ID of the leave rank to update.
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $leaveRank = HRMSLeaveRank::findOrFail($id); // Singular variable name

        // Define validation rules for updating a leave rank
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|unique:hrms_leave_rank,name,' . $id . '|max:255',
            'is_active' => 'sometimes|boolean', // Validate that is_active is a boolean if present
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Prepare data for update, ensuring 'is_active' is handled if present
            $updateData = $request->only('name');
            if ($request->has('is_active')) {
                $updateData['is_active'] = (bool)$request->is_active;
            }

            $leaveRank->update($updateData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Leave Rank updated successfully!',
                'data' => $leaveRank
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update leave rank.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified leave rank from storage.
     *
     * @param  int  $id The ID of the leave rank to delete.
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $leaveRank = HRMSLeaveRank::findOrFail($id); // Singular variable name

        // Consider if there are any related 'HRMSStaffEmployment' records.
        // If there are, you might want to prevent deletion, or soft delete them too,
        // or reassign them. For now, we'll assume cascading delete or that it's okay
        // if the database handles foreign key constraints (e.g., ON DELETE CASCADE)
        // or if you're using soft deletes on HRMSStaffEmployment.

        try {
            DB::beginTransaction();
            $leaveRank->delete();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Leave rank deleted successfully (soft deleted).'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete leave rank.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
