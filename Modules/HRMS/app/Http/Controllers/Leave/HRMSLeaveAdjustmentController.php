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
use Modules\HRMS\Transformers\HRMSLeaveAdjustmentResource;

/**
 * HRMSLeaveRankController
 *
 * This API controller manages CRUD operations for HRMSLeaveRank records.
 * It handles validation, creation, retrieval, updating, and deletion of leave ranks.
 */
class HRMSLeaveAdjustmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $perPage = request()->input('per_page', 10);
        $adjustments = HRMSLeaveAdjustment::with([
            'staff', 
            'leaveType', 
            'adjustmentReason'
            ])
            ->paginate($perPage);

        return HRMSLeaveAdjustmentResource::collection($adjustments)->response()->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'hrms_staff_id' => ['required', 'integer', 'exists:hrms_staff,id'],
            'hrms_leave_type_id' => ['required', 'integer', 'exists:hrms_leave_type,id'],
            'adjustment_reason_id' => ['required', 'integer', 'exists:hrms_leave_adjustment_reason,id'],
            'days' => ['required', 'numeric', 'between:-9999.99,9999.99'],
            'effective_date' => ['required', 'date'],
            'remarks' => ['nullable', 'string', 'max:500'],
        ]);

        $leaveType = HRMSLeaveType::find($validatedData['hrms_leave_type_id']);

        if (!$leaveType) {
            return response()->json([
                'success' => false,
                'message' => 'Leave Type not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        if (!$leaveType->leave_model) {
            return response()->json([
                'success' => false,
                'message' => 'Leave Type cannot be used for leave model.',
            ], Response::HTTP_BAD_REQUEST);
        }

        $adjustment = HRMSLeaveAdjustment::create($validatedData);
        $adjustment->load(['staff', 'leaveType', 'adjustmentReason']);

        return response()->json([
            'success' => true,
            'message' => 'Leave adjustment created successfully.',
            'data' => $adjustment,
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
        $adjustment = HRMSLeaveAdjustment::with(['staff', 'leaveType', 'adjustmentReason'])->findOrFail($id);

        return HRMSLeaveAdjustmentResource::make($adjustment)->response()->setStatusCode(Response::HTTP_OK);
        // return response()->json([
        //     'success' => true,
        //     'message' => 'Leave adjustment retrieved successfully.',
        //     'data' => $adjustment,
        // ]);
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
        $adjustment = HRMSLeaveAdjustment::findOrFail($id);

        // Define validation rules directly in the controller for update
        $validatedData = $request->validate([
            'hrms_staff_id' => ['sometimes', 'required', 'integer', 'exists:hrms_staff,id'],
            'hrms_leave_type_id' => ['sometimes', 'required', 'integer', 'exists:hrms_leave_type,id'],
            'adjustment_reason_id' => ['sometimes', 'required', 'integer', 'exists:hrms_leave_adjustment_reason,id'],
            'days' => ['sometimes', 'required', 'numeric', 'between:-9999.99,9999.99'],
            'effective_date' => ['sometimes', 'required', 'date'],
            'remarks' => ['nullable', 'string', 'max:500'],
        ]);

        // Custom validation logic for leave_model, only if hrms_leave_type_id is present in the request
        if (isset($validatedData['hrms_leave_type_id'])) {
            $leaveType = HRMSLeaveType::find($validatedData['hrms_leave_type_id']);
            if ($leaveType && !$leaveType->leave_model) {
                return response()->json([
                    'success' => false,
                    'message' => 'Leave Type cannot be used for leave model.',
                ], Response::HTTP_BAD_REQUEST);
            }
        }

        $adjustment->update($validatedData);

        $adjustment->load(['staff', 'leaveType', 'adjustmentReason']);

        return response()->json([
            'success' => true,
            'message' => 'Leave adjustment updated successfully.',
            'data' => $adjustment,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $adjustment = HRMSLeaveAdjustment::findOrFail($id);
        $adjustment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Leave adjustment deleted successfully.',
            'data' => null,
        ], Response::HTTP_NO_CONTENT);
    }

    public function getLeaveAdjustmentsByStaff(int $staffId): JsonResponse
    {
        $adjustments = HRMSLeaveAdjustment::with(['leaveType', 'adjustmentReason'])
            ->where('hrms_staff_id', $staffId)
            ->get();

        return HRMSLeaveAdjustmentResource::collection($adjustments)->response()->setStatusCode(Response::HTTP_OK);
    }

}
