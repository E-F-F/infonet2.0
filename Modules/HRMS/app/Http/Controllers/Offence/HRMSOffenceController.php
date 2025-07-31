<?php

namespace Modules\HRMS\Http\Controllers\Offence;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Modules\HRMS\Models\HRMSOffence;
use Modules\HRMS\Models\HRMSOffenceType;
use Modules\HRMS\Models\HRMSOffenceActionTaken;
use Modules\HRMS\Models\HRMSStaff;
use App\Models\Branch;
use Modules\HRMS\Transformers\HRMSOffenceResource;

/**
 * HRMSOffenceController
 *
 * This API controller manages CRUD operations for HRMSOffence records.
 * It handles validation, creation, retrieval, updating, and deletion of staff offence records.
 */
class HRMSOffenceController extends Controller
{
    /**
     * Display a listing of the offence records.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = HRMSOffence::with([
            'branch',
            'staff',
            'offenceType',
            'actionTaken',
            'creator',
            'updater'
        ]);

        if ($request->filled('offence_type_id')) {
            $query->where('hrms_offence_type_id', $request->input('offence_type_id'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('issue_date', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('issue_date', '<=', $request->input('date_to'));
        }

        if ($request->filled('employee')) {
            $query->whereHas('staff', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('employee') . '%');
            });
        }

        if ($request->input('show_deleted')) {
            $query->withTrashed(); // assuming soft deletes
        }

        $offences = $query->get();

        return HRMSOffenceResource::collection($offences)->response()->setStatusCode(200);
    }

    /**
     * Display the specified offence record.
     *
     * @param  int  $id The ID of the offence record.
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $offence = HRMSOffence::with([
            'branch',
            'staff',
            'offenceType',
            'actionTaken',
            'creator',
            'updater'
        ])->findOrFail($id);

        return response()->json($offence);
    }

    /**
     * Store a newly created offence record in storage.
     *
     * @param  \Illuminate\Http\Request  $request The incoming request.
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'branch_id' => 'required|integer|exists:branch,id',
            'hrms_staff_id' => 'required|integer|exists:hrms_staff,id',
            'issue_date' => 'required|date',
            'hrms_offence_type_id' => 'required|integer|exists:hrms_offence_type,id',
            'description' => 'required|string',
            'hrms_offence_action_taken_id' => 'nullable|integer|exists:hrms_offence_action_taken,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $authenticatedStaffId = 1; // TODO: Replace with actual authenticated staff ID

            $offence = HRMSOffence::create([
                'branch_id' => $request->branch_id,
                'hrms_staff_id' => $request->hrms_staff_id,
                'issue_date' => $request->issue_date,
                'hrms_offence_type_id' => $request->hrms_offence_type_id,
                'description' => $request->description,
                'hrms_offence_action_taken_id' => $request->hrms_offence_action_taken_id,
                'created_by' => $authenticatedStaffId,
                'updated_by' => $authenticatedStaffId,
            ]);

            DB::commit();

            $offence->load(['branch', 'staff', 'offenceType', 'actionTaken', 'creator', 'updater']);

            return response()->json([
                'success' => true,
                'message' => 'Offence record created successfully!',
                'data' => $offence
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create offence record.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified offence record in storage.
     *
     * @param  \Illuminate\Http\Request  $request The incoming request.
     * @param  int  $id The ID of the offence record to update.
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $offence = HRMSOffence::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'branch_id' => 'sometimes|required|integer|exists:branch,id',
            'hrms_staff_id' => 'sometimes|required|integer|exists:hrms_staff,id',
            'issue_date' => 'sometimes|required|date',
            'hrms_offence_type_id' => 'sometimes|required|integer|exists:hrms_offence_type,id',
            'description' => 'sometimes|required|string',
            'hrms_offence_action_taken_id' => 'sometimes|nullable|integer|exists:hrms_offence_action_taken,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $authenticatedStaffId = 1; // TODO: Replace with actual authenticated staff ID

            $updateData = $request->only([
                'branch_id',
                'hrms_staff_id',
                'issue_date',
                'hrms_offence_type_id',
                'description',
                'hrms_offence_action_taken_id',
            ]);

            $updateData['updated_by'] = $authenticatedStaffId;

            $offence->update($updateData);

            DB::commit();

            $offence->load(['branch', 'staff', 'offenceType', 'actionTaken', 'creator', 'updater']);

            return response()->json([
                'success' => true,
                'message' => 'Offence record updated successfully!',
                'data' => $offence
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update offence record.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified offence record from storage.
     *
     * @param  int  $id The ID of the offence record to delete.
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $offence = HRMSOffence::findOrFail($id);

        try {
            $offence->delete();

            return response()->json([
                'success' => true,
                'message' => 'Offence record deleted successfully (soft deleted).'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete offence record.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
