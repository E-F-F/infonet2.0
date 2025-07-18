<?php

namespace Modules\HRMS\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Modules\HRMS\Models\HRMSPayroll;
use Modules\HRMS\Models\HRMSPayGroup;
use Modules\HRMS\Models\HRMSPayBatchType;
use Modules\HRMS\Models\HRMSStaff;
use Modules\HRMS\Models\HRMSRosterDayAssignment;
use Modules\HRMS\Models\HRMSStaffRosterGroupAssignment;
use Carbon\Carbon;
use Modules\HRMS\Models\HRMSRosterDayAssignments;

class HRMSPayrollController extends Controller
{
    /**
     * Display a listing of the payroll records.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = HRMSPayroll::query();

            // Filter by pay_group_id if provided
            if ($request->has('pay_group_id')) {
                $query->where('hrms_pay_group_id', $request->input('pay_group_id'));
            }

            // Filter by pay_batch_type_id if provided
            if ($request->has('pay_batch_type_id')) {
                $query->where('hrms_pay_batch_type_id', $request->input('pay_batch_type_id'));
            }

            // Filter by status if provided
            if ($request->has('status')) {
                $query->where('status', $request->input('status'));
            }

            // Pagination
            $perPage = $request->input('per_page', 10);
            $payrolls = $query->with(['payGroup', 'payBatchType', 'createdBy', 'updatedBy', 'approvedBy', 'rejectedBy'])
                ->paginate($perPage);

            return response()->json([
                'status' => 'success',
                'message' => 'Payroll records retrieved successfully.',
                'data' => $payrolls
            ]);
        } catch (\Exception $e) {
            Log::error('Error retrieving payroll records: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while retrieving payroll records.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created payroll record in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'hrms_pay_group_id' => 'required|exists:hrms_pay_group,id',
                'hrms_pay_batch_type_id' => 'required|exists:hrms_pay_batch_type,id',
                'full_work_day' => 'required|integer|min:0',
                'remarks' => 'nullable|string',
                'status' => 'required|in:draft,submitted,pending,approved,rejected',
                'created_by' => 'nullable|exists:hrms_staff,id',
                'updated_by' => 'nullable|exists:hrms_staff,id',
                'approved_by' => 'nullable|exists:hrms_staff,id',
                'rejected_by' => 'nullable|exists:hrms_staff,id',
                'approved_at' => 'nullable|date',
                'rejected_at' => 'nullable|date'
            ]);

            $payroll = HRMSPayroll::create($validatedData);

            return response()->json([
                'status' => 'success',
                'message' => 'Payroll record created successfully.',
                'data' => $payroll
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating payroll record: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while creating the payroll record.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified payroll record.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $payroll = HRMSPayroll::with(['payGroup', 'payBatchType', 'createdBy', 'updatedBy', 'approvedBy', 'rejectedBy'])
                ->find($id);

            if (!$payroll) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Payroll record not found.'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Payroll record retrieved successfully.',
                'data' => $payroll
            ]);
        } catch (\Exception $e) {
            Log::error('Error retrieving payroll record: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while retrieving the payroll record.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified payroll record in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $payroll = HRMSPayroll::find($id);

            if (!$payroll) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Payroll record not found.'
                ], 404);
            }

            $validatedData = $request->validate([
                'hrms_pay_group_id' => 'sometimes|required|exists:hrms_pay_group,id',
                'hrms_pay_batch_type_id' => 'sometimes|required|exists:hrms_pay_batch_type,id',
                'full_work_day' => 'sometimes|required|integer|min:0',
                'remarks' => 'sometimes|nullable|string',
                'status' => 'sometimes|required|in:draft,submitted,pending,approved,rejected',
                'created_by' => 'sometimes|nullable|exists:hrms_staff,id',
                'updated_by' => 'sometimes|nullable|exists:hrms_staff,id',
                'approved_by' => 'sometimes|nullable|exists:hrms_staff,id',
                'rejected_by' => 'sometimes|nullable|exists:hrms_staff,id',
                'approved_at' => 'sometimes|nullable|date',
                'rejected_at' => 'sometimes|nullable|date'
            ]);

            $payroll->update($validatedData);

            return response()->json([
                'status' => 'success',
                'message' => 'Payroll record updated successfully.',
                'data' => $payroll
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating payroll record: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while updating the payroll record.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified payroll record from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $payroll = HRMSPayroll::find($id);

            if (!$payroll) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Payroll record not found.'
                ], 404);
            }

            $payroll->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Payroll record deleted successfully.'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error deleting payroll record: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while deleting the payroll record.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate payroll for a specific pay group and period.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function generatePayroll(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'hrms_pay_group_id' => 'required|exists:hrms_pay_group,id',
                'hrms_pay_batch_type_id' => 'required|exists:hrms_pay_batch_type,id',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'created_by' => 'required|exists:hrms_staff,id'
            ]);

            $startDate = Carbon::parse($validatedData['start_date']);
            $endDate = Carbon::parse($validatedData['end_date']);
            $payGroupId = $validatedData['hrms_pay_group_id'];
            $payBatchTypeId = $validatedData['hrms_pay_batch_type_id'];
            $createdBy = $validatedData['created_by'];

            // Get staff members in the pay group via roster group assignments
            $staffIds = HRMSStaffRosterGroupAssignment::whereHas('rosterGroup', function ($query) use ($payGroupId) {
                $query->where('hrms_pay_group_id', $payGroupId); // Assuming pay group is linked to roster group
            })->pluck('hrms_staff_id')->toArray();

            // Calculate full work days for each staff member
            $payrollRecords = [];
            foreach ($staffIds as $staffId) {
                $workDays = HRMSRosterDayAssignments::where('hrms_staff_id', $staffId)
                    ->whereBetween('roster_date', [$startDate, $endDate])
                    ->where('day_type', 'workday')
                    ->whereHas('shift', function ($query) {
                        $query->where('full_shift', true);
                    })
                    ->count();

                // Create payroll record
                $payrollRecords[] = [
                    'hrms_pay_group_id' => $payGroupId,
                    'hrms_pay_batch_type_id' => $payBatchTypeId,
                    'full_work_day' => $workDays,
                    'remarks' => "Payroll generated for {$startDate->toDateString()} to {$endDate->toDateString()}",
                    'status' => 'draft',
                    'created_by' => $createdBy,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            // Batch insert payroll records
            HRMSPayroll::insert($payrollRecords);

            return response()->json([
                'status' => 'success',
                'message' => 'Payroll generated successfully for the period.',
                'data' => [
                    'payroll_count' => count($payrollRecords),
                    'period' => [
                        'start_date' => $startDate->toDateString(),
                        'end_date' => $endDate->toDateString()
                    ]
                ]
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error generating payroll: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while generating the payroll.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
