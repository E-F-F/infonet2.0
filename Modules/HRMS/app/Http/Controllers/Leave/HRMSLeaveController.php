<?php

namespace Modules\HRMS\Http\Controllers\Leave;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\HRMS\Models\HRMSLeave;
use Modules\HRMS\Models\HRMSLeaveType;
use Modules\HRMS\Models\HRMSStaff;
use App\Models\Branch;
use Modules\HRMS\Models\HRMSAttendance;
use Modules\HRMS\Models\HRMSLeaveEntitlement;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
use Exception;
use Modules\HRMS\Transformers\HRMSLeaveResource;

/**
 * HRMSLeaveController
 *
 * This API controller manages CRUD operations for HRMSLeave records.
 * It handles validation, creation, retrieval, updating, and deletion of leave applications.
 * It now integrates with HRMSAttendance records upon leave approval and correctly manages
 * per-leave-type entitlements for staff, using the 'leave_model' flag to distinguish
 * entitlement-based leave types.
 */
class HRMSLeaveController extends Controller
{
    /**
     * Display a listing of the leave.
     *
     * Eager loads related models for comprehensive data retrieval.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $leaves = HRMSLeave::with([
            'branch',
            'staff',
            'leaveType',
            'creator',
            'updater',
            'approver',
            'rejecter'
        ])->paginate(10);

        // return response()->json([
        //     'data' => $leaves->items(),
        //     'pagination' => [
        //         'total' => $leaves->total(),
        //         'per_page' => $leaves->perPage(),
        //         'current_page' => $leaves->currentPage(),
        //         'last_page' => $leaves->lastPage(),
        //         'from' => $leaves->firstItem(),
        //         'to' => $leaves->lastItem()
        //     ]
        // ]);

        return HRMSLeaveResource::collection($leaves);
    }

    public function getCalendarEvents(Request $request)
    {
        $start = $request->query('start');
        $end   = $request->query('end');

        // Validate
        if (!$start || !$end) {
            return response()->json([
                'success' => false,
                'message' => 'Missing start or end parameter',
            ], 400);
        }

        try {
            // Safely parse dates
            $startDate = $start;
            $endDate   = $end;

            // Fetch leaves
            $leaves = HRMSLeave::with(['staff.personal', 'leaveType'])
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('date_from', [$startDate, $endDate])
                        ->orWhereBetween('date_to', [$startDate, $endDate])
                        ->orWhere(function ($q) use ($startDate, $endDate) {
                            $q->where('date_from', '<=', $startDate)
                                ->where('date_to', '>=', $endDate);
                        });
                })
                ->get();

            // Map to events
            $events = $leaves->map(function ($leave) {
                $dateFrom = $leave->date_from ? \Carbon\Carbon::parse($leave->date_from) : null;
                $dateTo   = $leave->date_to ? \Carbon\Carbon::parse($leave->date_to) : null;

                return [
                    'id'        => $leave->id,
                    'title'     => $leave->leaveType->name ?? 'Leave',
                    'start'     => $dateFrom?->toDateString(),
                    'end'       => $dateTo
                        ? $dateTo->copy()->addDay()->toDateString() // FullCalendar expects exclusive end
                        : $dateFrom?->toDateString(),
                    'employee'  => $leave->staff?->personal?->fullName ?? 'Unknown',
                    'status'    => strtolower($leave->status ?? 'pending'),
                    'reason'    => $leave->leave_purpose ?? '',
                ];
            });

            return response()->json($events);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified leave.
     *
     * @param  int  $id The ID of the leave.
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $leave = HRMSLeave::with([
            'branch',
            'staff',
            'leaveType',
            'creator',
            'updater',
            'approver',
            'rejecter'
        ])->findOrFail($id);

        return HRMSLeaveResource::make($leave);
    }

    /**
     * Store a newly created leave in storage.
     *
     * Handles creation of the leave record within a database transaction.
     * Sets 'created_by' and 'updated_by' based on the authenticated user.
     * Includes dynamic validation for leave dates based on leave type.
     * Checks against specific leave type entitlements, or skips if not entitlement-based.
     *
     * @param  \Illuminate\Http\Request  $request The incoming request.
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'branch_id' => 'required|integer|exists:branch,id',
            'hrms_staff_id' => 'required|integer|exists:hrms_staff,id',
            'hrms_leave_type_id' => 'required|integer|exists:hrms_leave_type,id',
            'date_from' => 'required|date',
            'session_from' => 'required|string|in:AM,PM',
            'date_to' => 'required|date|after_or_equal:date_from',
            'session_to' => 'required|string|in:AM,PM',
            'leave_purpose' => 'required|string',
            'attachment_url' => 'nullable|url|max:255',
            'status' => 'required|string|in:DRAFT,CANCELLED,SUBMITTED,PENDING,APPROVED,REJECTED',
            'remarks' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            DB::beginTransaction();

            $authenticatedStaffId = Auth::id();
            if (!$authenticatedStaffId) {
                // WARNING: Fallback for testing, handle properly in production (e.g., throw exception or specific error)
                $authenticatedStaffId = 1;
            }

            $leaveType = HRMSLeaveType::findOrFail($request->hrms_leave_type_id);
            $startDate = Carbon::parse($request->date_from);
            $endDate = Carbon::parse($request->date_to);
            // Ensure Carbon::now() uses the application's timezone for consistency
            $today = Carbon::now(config('app.timezone', 'Asia/Kuala_Lumpur'))->startOfDay();

            // Dynamic date validation based on leave type, using apply_within_days
            if ($leaveType->name === 'Sick Leave' || $leaveType->name === 'Emergency Leave') {
                // Use apply_within_days for grace period, fallback to 7 if null/not set
                $gracePeriodDays = $leaveType->apply_within_days ?? 7;
                $allowedStartDatePast = Carbon::now(config('app.timezone', 'Asia/Kuala_Lumpur'))->subDays($gracePeriodDays)->startOfDay();

                if ($startDate->lt($allowedStartDatePast) || $startDate->gt($today)) {
                    DB::rollBack();
                    return response()->json([
                        'message' => 'Validation Error',
                        'errors' => ['date_from' => ['For ' . $leaveType->name . ', leave start date must be within ' . $gracePeriodDays . ' days in the past (inclusive) or today.']]
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                }
            } else {
                // For other leave types, require advance application
                // Use apply_within_days for minimum advance days, fallback to 2 if null/not set
                $minimumAdvanceDays = $leaveType->apply_within_days ?? 2;
                // Calculate the earliest allowed start date: today + minimumAdvanceDays
                // If minimumAdvanceDays is 2, and today is July 4, 2025,
                // then $minAllowedStartDate will be July 6, 2025 (start of day).
                $minAllowedStartDate = Carbon::now(config('app.timezone', 'Asia/Kuala_Lumpur'))->addDays($minimumAdvanceDays)->startOfDay();

                if ($startDate->lt($minAllowedStartDate)) {
                    DB::rollBack();
                    return response()->json([
                        'message' => 'Validation Error',
                        'errors' => ['date_from' => ['For ' . $leaveType->name . ', leave start date must be at least ' . $minimumAdvanceDays . ' days from today (i.e., not before ' . $minAllowedStartDate->format('Y-m-d') . ').']]
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                }
            }

            $requestedDays = $startDate->diffInDays($endDate) + 1;
            $entitlement = null;

            // Conditionally check entitlement only if the leave type uses a leave model (is entitlement-based)
            if ($leaveType->leave_model) { // Using the existing 'leave_model' boolean
                $entitlement = HRMSLeaveEntitlement::where('hrms_staff_id', $request->hrms_staff_id)
                    ->where('hrms_leave_type_id', $request->hrms_leave_type_id)
                    ->where('year', $startDate->year)
                    ->first();

                if (!$entitlement) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Leave entitlement record not found for this staff member and leave type for the year ' . $startDate->year . '.',
                        'errors' => ['entitlement' => ['Please ensure leave entitlements are set up for this leave type.']]
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                }

                // Only check entitlement if the initial status is 'APPROVED' or 'PENDING'
                if (in_array($request->status, ['APPROVED', 'PENDING'])) {
                    if ($entitlement->remaining_days < $requestedDays) {
                        DB::rollBack();
                        return response()->json([
                            'message' => 'Validation Error',
                            'errors' => ['leave_days' => ['Insufficient leave entitlement. Remaining: ' . $entitlement->remaining_days . ' days. Requested: ' . $requestedDays . ' days.']]
                        ], Response::HTTP_UNPROCESSABLE_ENTITY);
                    }
                }
            }

            $leaveData = $request->only([
                'branch_id',
                'hrms_staff_id',
                'hrms_leave_type_id',
                'date_from',
                'session_from',
                'date_to',
                'session_to',
                'leave_purpose',
                'attachment_url',
                'status',
                'remarks',
            ]);

            $leaveData['created_by'] = $authenticatedStaffId;
            $leaveData['updated_by'] = $authenticatedStaffId;
            $leaveData['created_at'] = now();

            if ($leaveData['status'] === 'APPROVED') {
                $leaveData['approved_by'] = $authenticatedStaffId;
                $leaveData['approved_at'] = now();
                // Deduct from entitlement if approved on creation AND it's an entitlement-based leave
                if ($leaveType->leave_model && $entitlement) { // Using the existing 'leave_model' boolean
                    $entitlement->consumed_days += $requestedDays;
                    $entitlement->remaining_days -= $requestedDays;
                    $entitlement->save();
                }
            } elseif ($leaveData['status'] === 'REJECTED') {
                $leaveData['rejected_by'] = $authenticatedStaffId;
                $leaveData['rejected_at'] = now();
            } elseif ($leaveData['status'] === 'PENDING') {
                $leaveData['updated_at'] = now();
            }


            $leave = HRMSLeave::create($leaveData);

            if ($leave->status === 'APPROVED') {
                $this->updateAttendanceForLeave($leave);
            }

            DB::commit();

            $leave->load(['branch', 'staff', 'leaveType', 'creator', 'updater', 'approver', 'rejecter']);

            return response()->json([
                'success' => true,
                'message' => 'Leave application created successfully!',
                'data' => $leave
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create leave application.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified leave in storage.
     *
     * @param  \Illuminate\Http\Request  $request The incoming request.
     * @param  int  $id The ID of the leave to update.
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $leave = HRMSLeave::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'branch_id' => 'sometimes|required|integer|exists:branch,id',
            'hrms_staff_id' => 'sometimes|required|integer|exists:hrms_staff,id',
            'hrms_leave_type_id' => 'sometimes|required|integer|exists:hrms_leave_type,id',
            'date_from' => 'sometimes|required|date',
            'session_from' => 'sometimes|required|string|in:AM,PM',
            'date_to' => 'sometimes|required|date|after_or_equal:date_from',
            'session_to' => 'sometimes|required|string|in:AM,PM',
            'leave_purpose' => 'sometimes|required|string',
            'attachment_url' => 'nullable|url|max:255',
            'status' => 'sometimes|required|string|in:DRAFT,CANCELLED,SUBMITTED,PENDING,APPROVED,REJECTED',
            'remarks' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            DB::beginTransaction();

            $authenticatedStaffId = Auth::id();
            if (!$authenticatedStaffId) {
                // WARNING: Fallback for testing, handle properly in production
                $authenticatedStaffId = 1;
            }

            $updateData = $request->only([
                'branch_id',
                'hrms_staff_id',
                'hrms_leave_type_id',
                'date_from',
                'session_from',
                'date_to',
                'session_to',
                'leave_purpose',
                'attachment_url',
                'status',
                'remarks',
            ]);

            $updateData['updated_by'] = $authenticatedStaffId;
            $updateData['updated_at'] = now();

            $effectiveStartDate = Carbon::parse($request->input('date_from', $leave->date_from));
            $effectiveEndDate = Carbon::parse($request->input('date_to', $leave->date_to));
            $leaveDays = $effectiveStartDate->diffInDays($effectiveEndDate) + 1;

            $oldStatus = $leave->status;
            $newStatus = $request->input('status', $oldStatus);

            $leaveType = HRMSLeaveType::findOrFail($request->input('hrms_leave_type_id', $leave->hrms_leave_type_id));
            $entitlement = null;

            // Conditionally check and adjust entitlement only if the leave type uses a leave model
            if ($leaveType->leave_model) { // Using the existing 'leave_model' boolean
                $entitlement = HRMSLeaveEntitlement::where('hrms_staff_id', $request->input('hrms_staff_id', $leave->hrms_staff_id))
                    ->where('hrms_leave_type_id', $request->input('hrms_leave_type_id', $leave->hrms_leave_type_id))
                    ->where('year', $effectiveStartDate->year)
                    ->first();

                if (!$entitlement) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Leave entitlement record not found for this staff member and leave type for the year ' . $effectiveStartDate->year . '.',
                        'errors' => ['entitlement' => ['Please ensure leave entitlements are set up for this leave type.']]
                    ], Response::HTTP_INTERNAL_SERVER_ERROR);
                }

                // --- Entitlement Adjustment Logic ---
                if ($oldStatus === 'APPROVED' && $newStatus !== 'APPROVED') {
                    $entitlement->consumed_days -= $leaveDays;
                    $entitlement->remaining_days += $leaveDays;
                    $entitlement->save();
                    $this->clearAttendanceForLeave($leave);
                } elseif ($oldStatus !== 'APPROVED' && $newStatus === 'APPROVED') {
                    if ($entitlement->remaining_days < $leaveDays) {
                        DB::rollBack();
                        return response()->json([
                            'message' => 'Validation Error',
                            'errors' => ['status' => ['Insufficient leave entitlement to approve this leave. Remaining: ' . $entitlement->remaining_days . ' days. Requested: ' . $leaveDays . ' days.']]
                        ], Response::HTTP_UNPROCESSABLE_ENTITY);
                    }
                    $entitlement->consumed_days += $leaveDays;
                    $entitlement->remaining_days -= $leaveDays;
                    $entitlement->save();
                    $this->updateAttendanceForLeave($leave);
                }
            } else {
                // If not entitlement-based, still manage attendance status changes
                if ($oldStatus === 'APPROVED' && $newStatus !== 'APPROVED') {
                    $this->clearAttendanceForLeave($leave);
                } elseif ($oldStatus !== 'APPROVED' && $newStatus === 'APPROVED') {
                    $this->updateAttendanceForLeave($leave);
                }
            }

            if ($newStatus === 'APPROVED') {
                $updateData['approved_by'] = $authenticatedStaffId;
                $updateData['approved_at'] = now();
                $updateData['rejected_by'] = null;
                $updateData['rejected_at'] = null;
            } elseif ($newStatus === 'REJECTED') {
                $updateData['rejected_by'] = $authenticatedStaffId;
                $updateData['rejected_at'] = now();
                $updateData['approved_by'] = null;
                $updateData['approved_at'] = null;
            } elseif ($newStatus === 'PENDING') {
                $updateData['approved_by'] = null;
                $updateData['approved_at'] = null;
                $updateData['rejected_by'] = null;
                $updateData['rejected_at'] = null;
            } elseif (in_array($newStatus, ['DRAFT', 'CANCELLED', 'SUBMITTED'])) {
                $updateData['approved_by'] = null;
                $updateData['approved_at'] = null;
                $updateData['rejected_by'] = null;
                $updateData['rejected_at'] = null;
            }

            $leave->update($updateData);

            DB::commit();

            $leave->load(['branch', 'staff', 'leaveType', 'creator', 'updater', 'approver', 'rejecter']);

            return response()->json([
                'success' => true,
                'message' => 'Leave application updated successfully!',
                'data' => $leave
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update leave application.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified leave from storage.
     *
     * This method performs a soft delete on the leave record.
     * It also clears associated attendance records and refunds entitlement if the leave was approved.
     *
     * @param  int  $id The ID of the leave to delete.
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $leave = HRMSLeave::findOrFail($id);

        try {
            DB::beginTransaction();

            $leaveType = HRMSLeaveType::findOrFail($leave->hrms_leave_type_id);

            // Conditionally refund entitlement only if the leave was approved AND it uses a leave model
            if ($leave->status === 'APPROVED') {
                if ($leaveType->leave_model) { // Using the existing 'leave_model' boolean
                    $leaveStartDate = Carbon::parse($leave->date_from);
                    $leaveEndDate = Carbon::parse($leave->date_to);
                    $leaveDays = $leaveStartDate->diffInDays($leaveEndDate) + 1;

                    $entitlement = HRMSLeaveEntitlement::where('hrms_staff_id', $leave->hrms_staff_id)
                        ->where('hrms_leave_type_id', $leave->hrms_leave_type_id)
                        ->where('year', $leaveStartDate->year)
                        ->first();

                    if ($entitlement) {
                        $entitlement->consumed_days -= $leaveDays;
                        $entitlement->remaining_days += $leaveDays;
                        $entitlement->save();
                    }
                }
                // Always clear attendance if the leave was approved, regardless of entitlement-based status
                $this->clearAttendanceForLeave($leave);
            }

            $leave->delete();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Leave application deleted successfully (soft deleted).'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete leave application.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Helper method to update attendance records for an approved leave.
     *
     * @param HRMSLeave $leave The leave record.
     * @return void
     */
    protected function updateAttendanceForLeave(HRMSLeave $leave): void
    {
        $start = Carbon::parse($leave->date_from);
        $end = Carbon::parse($leave->date_to);

        while ($start->lte($end)) {
            $attendance = HRMSAttendance::firstOrNew([
                'hrms_staff_id'    => $leave->hrms_staff_id,
                'attendance_date'  => $start->toDateString(),
            ]);

            $isSameStart = $start->isSameDay($leave->date_from);
            $isSameEnd   = $start->isSameDay($leave->date_to);

            $timeInStatus  = null;
            $timeOutStatus = null;

            if ($leave->date_from === $leave->date_to) {
                // One-day leave
                if ($leave->session_from === 'AM' && $leave->session_to === 'AM') {
                    $timeInStatus = 'onleave';
                } elseif ($leave->session_from === 'PM' && $leave->session_to === 'PM') {
                    $timeOutStatus = 'onleave';
                } else {
                    $timeInStatus = 'onleave';
                    $timeOutStatus = 'onleave';
                }
            } else {
                // Multi-day leave
                if ($isSameStart) {
                    if ($leave->session_from === 'AM') {
                        $timeInStatus = 'onleave';
                        $timeOutStatus = 'onleave';
                    } elseif ($leave->session_from === 'PM') {
                        $timeOutStatus = 'onleave';
                    }
                } elseif ($isSameEnd) {
                    if ($leave->session_to === 'AM') {
                        $timeInStatus = 'onleave';
                    } elseif ($leave->session_to === 'PM') {
                        $timeInStatus = 'onleave';
                        $timeOutStatus = 'onleave';
                    }
                } else {
                    $timeInStatus = 'onleave';
                    $timeOutStatus = 'onleave';
                }
            }

            $attendance->time_in_status     = $timeInStatus;
            $attendance->time_out_status    = $timeOutStatus;
            $attendance->total_working_hours = 0;
            $attendance->remark              = $leave->leave_purpose;

            $attendance->save();
            $start->addDay();
        }
    }


    /**
     * Helper method to clear attendance records for a leave that is no longer approved.
     *
     * @param HRMSLeave $leave The leave record.
     * @return void
     */
    protected function clearAttendanceForLeave(HRMSLeave $leave): void
    {
        $start = Carbon::parse($leave->date_from);
        $end = Carbon::parse($leave->date_to);

        while ($start->lte($end)) {
            $attendance = HRMSAttendance::where('hrms_staff_id', $leave->hrms_staff_id)
                ->where('attendance_date', $start->toDateString())
                ->first();

            if ($attendance) {
                $isSameStart = $start->isSameDay($leave->date_from);
                $isSameEnd   = $start->isSameDay($leave->date_to);

                if ($leave->date_from === $leave->date_to) {
                    // One-day leave
                    if ($leave->session_from === 'AM' && $leave->session_to === 'AM') {
                        if ($attendance->time_in_status === 'onleave') {
                            $attendance->time_in_status = null;
                        }
                    } elseif ($leave->session_from === 'PM' && $leave->session_to === 'PM') {
                        if ($attendance->time_out_status === 'onleave') {
                            $attendance->time_out_status = null;
                        }
                    } else {
                        if ($attendance->time_in_status === 'onleave') {
                            $attendance->time_in_status = null;
                        }
                        if ($attendance->time_out_status === 'onleave') {
                            $attendance->time_out_status = null;
                        }
                    }
                } else {
                    // Multi-day leave
                    if ($isSameStart) {
                        if ($leave->session_from === 'AM') {
                            if ($attendance->time_in_status === 'onleave') {
                                $attendance->time_in_status = null;
                            }
                            if ($attendance->time_out_status === 'onleave') {
                                $attendance->time_out_status = null;
                            }
                        } elseif ($leave->session_from === 'PM') {
                            if ($attendance->time_out_status === 'onleave') {
                                $attendance->time_out_status = null;
                            }
                        }
                    } elseif ($isSameEnd) {
                        if ($leave->session_to === 'AM') {
                            if ($attendance->time_in_status === 'onleave') {
                                $attendance->time_in_status = null;
                            }
                        } elseif ($leave->session_to === 'PM') {
                            if ($attendance->time_in_status === 'onleave') {
                                $attendance->time_in_status = null;
                            }
                            if ($attendance->time_out_status === 'onleave') {
                                $attendance->time_out_status = null;
                            }
                        }
                    } else {
                        if ($attendance->time_in_status === 'onleave') {
                            $attendance->time_in_status = null;
                        }
                        if ($attendance->time_out_status === 'onleave') {
                            $attendance->time_out_status = null;
                        }
                    }
                }

                // If both are cleared, clean up remark and hours
                if (
                    $attendance->time_in_status === null &&
                    $attendance->time_out_status === null
                ) {
                    $attendance->remark = null;
                    $attendance->total_working_hours = null;
                }

                $attendance->save();
            }

            $start->addDay();
        }
    }

    public function getLeavesByStaff($staffId)
    {
        try {
            // Get leaves for the staff, eager load relationships if needed
            $leaves = HRMSLeave::with(['leaveType', 'staff'])
                ->where('hrms_staff_id', $staffId)
                ->orderBy('created_at', 'desc')
                ->get();

            return HRMSLeaveResource::collection($leaves)
                ->response()
                ->setStatusCode(Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch leaves for staff.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
