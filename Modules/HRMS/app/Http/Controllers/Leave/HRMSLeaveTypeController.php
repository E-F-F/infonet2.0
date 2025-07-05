<?php

namespace Modules\HRMS\Http\Controllers\Leave;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\HRMS\Models\HRMSLeaveType;

class HRMSLeaveTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $leaveTypes = HRMSLeaveType::all();
        return response()->json([
            'success' => true,
            'message' => 'Leave types retrieved successfully.',
            'data' => $leaveTypes
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255|unique:hrms_leave_type,name',
                'default_no_of_days' => 'required|integer|min:0',
                'status' => 'required|string|in:active,inactive',
                'earned_rules' => 'nullable|string|max:255',
                'need_blocking' => 'boolean',
                'leave_model' => 'boolean',
                'allow_carry_forward' => 'boolean',
                'require_attachment' => 'boolean',
                'apply_by_hours' => 'boolean',
                'apply_within_days' => 'nullable|integer|min:0',
                'background_color' => 'nullable|string|max:7', // e.g., #RRGGBB
                'remarks' => 'nullable|string',
                'replacement_shift' => 'boolean',
            ]);

            $leaveType = HRMSLeaveType::create($validatedData);
            return response()->json([
                'success' => true,
                'message' => 'Leave type created successfully.',
                'data' => $leaveType
            ], Response::HTTP_CREATED);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create leave type.',
                'errors' => [$e->getMessage()]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $leaveType = HRMSLeaveType::find($id);

        if (!$leaveType) {
            return response()->json([
                'success' => false,
                'message' => 'Leave type not found.'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'message' => 'Leave type retrieved successfully.',
            'data' => $leaveType
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $leaveType = HRMSLeaveType::find($id);

        if (!$leaveType) {
            return response()->json([
                'success' => false,
                'message' => 'Leave type not found.'
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $validatedData = $request->validate([
                'name' => 'sometimes|required|string|max:255|unique:hrms_leave_type,name,' . $id,
                'default_no_of_days' => 'sometimes|required|integer|min:0',
                'status' => 'sometimes|required|string|in:active,inactive',
                'earned_rules' => 'nullable|string|max:255',
                'need_blocking' => 'sometimes|required|boolean',
                'leave_model' => 'sometimes|required|boolean',
                'allow_carry_forward' => 'sometimes|required|boolean',
                'require_attachment' => 'sometimes|required|boolean',
                'apply_by_hours' => 'sometimes|required|boolean',
                'apply_within_days' => 'nullable|integer|min:0',
                'background_color' => 'nullable|string|max:7',
                'remarks' => 'nullable|string',
                'replacement_shift' => 'sometimes|required|boolean',
            ]);

            $leaveType->update($validatedData);
            return response()->json([
                'success' => true,
                'message' => 'Leave type updated successfully.',
                'data' => $leaveType
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update leave type.',
                'errors' => [$e->getMessage()]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $leaveType = HRMSLeaveType::find($id);

        if (!$leaveType) {
            return response()->json([
                'success' => false,
                'message' => 'Leave type not found.'
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $leaveType->delete(); // Soft deletes the record
            return response()->json([
                'success' => true,
                'message' => 'Leave type deleted successfully.'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete leave type.',
                'errors' => [$e->getMessage()]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display a listing of leave types where 'leave_model' is true.
     *
     * @return Response
     */
    public function listModelBasedLeaveTypes()
    {
        $leaveTypes = HRMSLeaveType::where('leave_model', 1)->get();

        return response()->json([
            'success' => true,
            'message' => 'Model-based leave types retrieved successfully.',
            'data' => $leaveTypes
        ]);
    }
}
