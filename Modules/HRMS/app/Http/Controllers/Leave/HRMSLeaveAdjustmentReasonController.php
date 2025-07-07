<?php

namespace Modules\HRMS\Http\Controllers\Leave;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\HRMS\Models\HRMSLeaveRank;
use Illuminate\Support\Facades\DB; // Added for transaction handling
use Modules\HRMS\Models\HRMSLeaveAdjustment;
use Modules\HRMS\Models\HRMSLeaveType; // Needed for custom validation
use Illuminate\Validation\Rule; // Needed for unique rule in update (though not used here directly)
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Modules\HRMS\Models\HRMSLeaveAdjustmentReason;

/**
 * HRMSLeaveRankController
 *
 * This API controller manages CRUD operations for HRMSLeaveRank records.
 * It handles validation, creation, retrieval, updating, and deletion of leave ranks.
 */
class HRMSLeaveAdjustmentReasonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $reasons = HRMSLeaveAdjustmentReason::all();

        return response()->json([
            'success' => true,
            'message' => 'Leave adjustment reasons retrieved successfully.',
            'data' => $reasons,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        // Define validation rules directly in the controller
        $validatedData = $request->validate([
            'reason_name' => ['required', 'string', 'max:255', 'unique:hrms_leave_adjustment_reason,reason_name'],
            'built_in' => ['required', 'string', Rule::in(['Normal', 'NONE'])], // Updated for ENUM
            'is_active' => ['boolean'],
        ]);

        $reason = HRMSLeaveAdjustmentReason::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Leave adjustment reason created successfully.',
            'data' => $reason,
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $reason = HRMSLeaveAdjustmentReason::withTrashed()->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Leave adjustment reason retrieved successfully.',
            'data' => $reason,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $reason = HRMSLeaveAdjustmentReason::withTrashed()->findOrFail($id);

        // Define validation rules directly in the controller for update
        $validatedData = $request->validate([
            'reason_name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('hrms_leave_adjustment_reason', 'reason_name')->ignore($id),
            ],
            'built_in' => ['sometimes', 'required', 'string', Rule::in(['Normal', 'NONE'])], // Updated for ENUM
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $reason->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Leave adjustment reason updated successfully.',
            'data' => $reason,
        ]);
    }

    /**
     * Remove the specified resource from storage (soft delete).
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $reason = HRMSLeaveAdjustmentReason::findOrFail($id);
        $reason->delete();

        return response()->json([
            'success' => true,
            'message' => 'Leave adjustment reason soft deleted successfully.',
            'data' => null,
        ], Response::HTTP_NO_CONTENT);
    }

    /**
     * Restore the specified soft-deleted resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function restore(int $id): JsonResponse
    {
        $reason = HRMSLeaveAdjustmentReason::onlyTrashed()->findOrFail($id);
        $reason->restore();

        return response()->json([
            'success' => true,
            'message' => 'Leave adjustment reason restored successfully.',
            'data' => $reason,
        ]);
    }

    /**
     * Permanently remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function forceDelete(int $id): JsonResponse
    {
        $reason = HRMSLeaveAdjustmentReason::withTrashed()->findOrFail($id);
        $reason->forceDelete();

        return response()->json([
            'success' => true,
            'message' => 'Leave adjustment reason permanently deleted successfully.',
            'data' => null,
        ], Response::HTTP_NO_CONTENT);
    }
}
