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
        $leaveEntitlements = HRMSLeaveEntitlement::with('staff')->paginate(10);

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
        $leaveEntitlement = HRMSLeaveEntitlement::with('staff')->findOrFail($id);
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
            'hrms_staff_id' => 'required|integer|exists:hrms_staff,id',
            'entitled_days' => 'required|numeric|min:0',
            'year' => 'required|integer|min:2000|max:' . (Carbon::now()->addYears(5)->year), // Example year range
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            DB::beginTransaction();

            // Check for unique combination of staff_id and year
            $existingEntitlement = HRMSLeaveEntitlement::where('hrms_staff_id', $request->hrms_staff_id)
                ->where('year', $request->year)
                ->first();
            if ($existingEntitlement) {
                DB::rollBack();
                return response()->json([
                    'message' => 'Validation Error',
                    'errors' => ['hrms_staff_id' => ['An entitlement already exists for this staff member in the specified year.']]
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $entitledDays = (float)$request->entitled_days;

            $leaveEntitlement = HRMSLeaveEntitlement::create([
                'hrms_staff_id' => $request->hrms_staff_id,
                'entitled_days' => $entitledDays,
                'consumed_days' => 0.0, // Always start with 0 consumed days for a new entitlement
                'remaining_days' => $entitledDays, // Remaining days are initially equal to entitled days
                'year' => $request->year,
            ]);

            DB::commit();

            // Reload with staff relationship for response
            $leaveEntitlement->load('staff');

            return response()->json([
                'success' => true,
                'message' => 'Leave entitlement created successfully!',
                'data' => $leaveEntitlement
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create leave entitlement.',
                'error' => $e->getMessage()
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
        $leaveEntitlement = HRMSLeaveEntitlement::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'entitled_days' => 'sometimes|required|numeric|min:0',
            // 'consumed_days' and 'remaining_days' are usually managed by leave applications
            // If you allow direct updates, add validation here and ensure consistency.
            // For now, we'll assume they are not directly updated via this API.
            'year' => 'sometimes|required|integer|min:2000|max:' . (Carbon::now()->addYears(5)->year),
            // If staff_id is allowed to change, add validation and unique check
            // 'hrms_staff_id' => 'sometimes|required|integer|exists:hrms_staff,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            DB::beginTransaction();

            $updateData = $request->only(['entitled_days', 'year']);

            // Handle 'entitled_days' update: recalculate remaining_days
            if (isset($updateData['entitled_days'])) {
                $oldEntitledDays = $leaveEntitlement->entitled_days;
                $newEntitledDays = (float)$updateData['entitled_days'];

                // Calculate the difference in entitlement
                $entitlementDifference = $newEntitledDays - $oldEntitledDays;

                // Update remaining_days based on the change in entitled_days
                $updateData['remaining_days'] = $leaveEntitlement->remaining_days + $entitlementDifference;

                // Ensure remaining_days doesn't go below zero if consumed days exceed new entitlement
                if ($updateData['remaining_days'] < 0) {
                    // This scenario means the new entitlement is less than already consumed days.
                    // You might want to prevent this or adjust consumed_days/remaining_days accordingly.
                    // For simplicity, we'll just set remaining to 0 and adjust consumed if needed.
                    $updateData['remaining_days'] = 0.0;
                    $updateData['consumed_days'] = $newEntitledDays; // All new entitled days are consumed
                }
            }

            // If year or staff_id is updated, ensure uniqueness
            if ($request->has('year') || $request->has('hrms_staff_id')) {
                $newStaffId = $request->input('hrms_staff_id', $leaveEntitlement->hrms_staff_id);
                $newYear = $request->input('year', $leaveEntitlement->year);

                $existingEntitlement = HRMSLeaveEntitlement::where('hrms_staff_id', $newStaffId)
                    ->where('year', $newYear)
                    ->where('id', '!=', $id) // Exclude current record
                    ->first();
                if ($existingEntitlement) {
                    DB::rollBack();
                    return response()->json([
                        'message' => 'Validation Error',
                        'errors' => ['unique_combination' => ['An entitlement already exists for this staff member in the specified year.']]
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                }
            }

            $leaveEntitlement->update($updateData);

            DB::commit();

            // Reload with staff relationship for response
            $leaveEntitlement->load('staff');

            return response()->json([
                'success' => true,
                'message' => 'Leave entitlement updated successfully!',
                'data' => $leaveEntitlement
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update leave entitlement.',
                'error' => $e->getMessage()
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
}
