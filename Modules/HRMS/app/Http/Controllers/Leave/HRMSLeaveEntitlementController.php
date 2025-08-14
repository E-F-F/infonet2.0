<?php

namespace Modules\HRMS\Http\Controllers\Leave;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\HRMS\Models\HRMSLeaveEntitlement;
use Modules\HRMS\Models\HRMSStaff; // Import HRMSStaff for validation
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response; // For HTTP status codes
use Carbon\Carbon; // For date manipulation, especially current year

/**
 * HRMSLeaveEntitlementController
 *
 * This API controller manages CRUD operations for HRMSLeaveEntitlement records.
 * It handles validation, creation, retrieval, updating, and deletion of staff leave entitlements.
 */
class HRMSLeaveEntitlementController extends Controller
{
    /**
     * Display a listing of the leave entitlements.
     *
     * Eager loads the staff relationship for comprehensive data.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $leaveEntitlements = HRMSLeaveEntitlement::with('staff', 'leaveType')->paginate(10);

        return response()->json([
            'data' => $leaveEntitlements->items(),
            'pagination' => [
                'total' => $leaveEntitlements->total(),
                'per_page' => $leaveEntitlements->perPage(),
                'current_page' => $leaveEntitlements->currentPage(),
                'last_page' => $leaveEntitlements->lastPage(),
                'from' => $leaveEntitlements->firstItem(),
                'to' => $leaveEntitlements->lastItem()
            ]
        ]);
    }

    /**
     * Display the specified leave entitlement.
     *
     * @param  int  $id The ID of the leave entitlement.
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $leaveEntitlement = HRMSLeaveEntitlement::with('staff', 'leaveType')->findOrFail($id);
        return response()->json($leaveEntitlement);
    }

    /**
     * Store a newly created leave entitlement in storage.
     *
     * @param  \Illuminate\Http\Request  $request The incoming request.
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hrms_staff_id'        => 'required|integer|exists:hrms_staff,id',
            'hrms_leave_type_id'   => 'required|integer|exists:hrms_leave_type,id',
            'entitled_days'        => 'required|numeric|min:0',
            'year'                 => 'required|integer|min:2000|max:' . (Carbon::now()->addYears(5)->year),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors'  => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            DB::beginTransaction();

            $exists = HRMSLeaveEntitlement::where('hrms_staff_id', $request->hrms_staff_id)
                ->where('hrms_leave_type_id', $request->hrms_leave_type_id)
                ->where('year', $request->year)
                ->first();

            if ($exists) {
                DB::rollBack();
                return response()->json([
                    'message' => 'Validation Error',
                    'errors'  => ['unique' => ['Entitlement already exists for this staff, leave type, and year.']]
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $entitled = (float) $request->entitled_days;

            $entitlement = HRMSLeaveEntitlement::create([
                'hrms_staff_id'       => $request->hrms_staff_id,
                'hrms_leave_type_id'  => $request->hrms_leave_type_id,
                'entitled_days'       => $entitled,
                'consumed_days'       => 0.0,
                'remaining_days'      => $entitled,
                'year'                => $request->year,
            ]);

            DB::commit();
            $entitlement->load('staff');

            return response()->json([
                'success' => true,
                'message' => 'Leave entitlement created successfully!',
                'data'    => $entitlement
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create leave entitlement.',
                'error'   => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * Update the specified leave entitlement in storage.
     *
     * Note: Typically, 'consumed_days' and 'remaining_days' are updated
     * automatically by leave applications, not directly via this endpoint.
     * This update allows modifying 'entitled_days' and 'year' (if no leaves are linked).
     *
     * @param  \Illuminate\Http\Request  $request The incoming request.
     * @param  int  $id The ID of the leave entitlement to update.
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $entitlement = HRMSLeaveEntitlement::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'entitled_days'       => 'sometimes|required|numeric|min:0',
            'year'                => 'sometimes|required|integer|min:2000|max:' . (Carbon::now()->addYears(5)->year),
            'hrms_leave_type_id'  => 'sometimes|required|integer|exists:hrms_leave_type,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors'  => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            DB::beginTransaction();

            $input = $request->only(['entitled_days', 'year', 'hrms_leave_type_id']);

            // Handle entitled_days adjustment
            if (isset($input['entitled_days'])) {
                $diff = $input['entitled_days'] - $entitlement->entitled_days;
                $input['remaining_days'] = $entitlement->remaining_days + $diff;

                if ($input['remaining_days'] < 0) {
                    $input['remaining_days'] = 0.0;
                    $input['consumed_days'] = $input['entitled_days']; // all used
                }
            }

            // Composite uniqueness check
            $newYear = $input['year'] ?? $entitlement->year;
            $newLeaveType = $input['hrms_leave_type_id'] ?? $entitlement->hrms_leave_type_id;
            $newStaffId = $entitlement->hrms_staff_id;

            $conflict = HRMSLeaveEntitlement::where('hrms_staff_id', $newStaffId)
                ->where('hrms_leave_type_id', $newLeaveType)
                ->where('year', $newYear)
                ->where('id', '!=', $entitlement->id)
                ->exists();

            if ($conflict) {
                DB::rollBack();
                return response()->json([
                    'message' => 'Validation Error',
                    'errors'  => ['unique' => ['Another entitlement already exists for this staff, leave type, and year.']]
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $entitlement->update($input);
            $entitlement->load('staff');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Leave entitlement updated successfully!',
                'data'    => $entitlement
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update leave entitlement.',
                'error'   => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * Remove the specified leave entitlement from storage.
     *
     * Note: Consider implications if there are associated leave applications.
     * Deleting an entitlement might require re-evaluating past leaves.
     *
     * @param  int  $id The ID of the leave entitlement to delete.
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $leaveEntitlement = HRMSLeaveEntitlement::findOrFail($id);

        try {
            DB::beginTransaction();

            // Optional: Add logic here to prevent deletion if there are consumed days
            // or if there are active leave applications linked to this entitlement year.
            // Example:
            // if ($leaveEntitlement->consumed_days > 0) {
            //     DB::rollBack();
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Cannot delete entitlement with consumed leave days.',
            //     ], Response::HTTP_CONFLICT); // 409 Conflict
            // }

            $leaveEntitlement->delete(); // This is a hard delete as the model doesn't use SoftDeletes

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Leave entitlement deleted successfully.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete leave entitlement.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get all leave entitlements for a specific staff.
     *
     * @param int $staffId The ID of the staff.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getByStaff($staffId)
    {
        // Validate if staff exists
        $staffExists = \Modules\HRMS\Models\HRMSStaff::where('id', $staffId)->exists();

        if (!$staffExists) {
            return response()->json([
                'success' => false,
                'message' => 'Staff not found.'
            ], Response::HTTP_NOT_FOUND);
        }

        // Fetch leave entitlements for the staff
        $leaveEntitlements = HRMSLeaveEntitlement::with('leaveType')
            ->where('hrms_staff_id', $staffId)
            ->orderBy('year', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $leaveEntitlements->items(),
            'pagination' => [
                'total' => $leaveEntitlements->total(),
                'per_page' => $leaveEntitlements->perPage(),
                'current_page' => $leaveEntitlements->currentPage(),
                'last_page' => $leaveEntitlements->lastPage(),
                'from' => $leaveEntitlements->firstItem(),
                'to' => $leaveEntitlements->lastItem()
            ]
        ], Response::HTTP_OK);
    }
}
