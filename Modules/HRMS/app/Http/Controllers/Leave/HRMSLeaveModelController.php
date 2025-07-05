<?php

namespace Modules\HRMS\Http\Controllers\Leave;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\HRMS\Models\HRMSLeaveModel;

class HRMSLeaveModelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $leaveModels = HRMSLeaveModel::with(['leaveType', 'leaveRank'])->get();
        return response()->json([
            'success' => true,
            'message' => 'Leave models retrieved successfully.',
            'data' => $leaveModels
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
                'hrms_leave_type_id' => 'required|integer|exists:hrms_leave_type,id',
                'hrms_leave_rank_id' => 'required|integer|exists:hrms_leave_rank,id',
                'year_of_service' => 'required|integer|min:0',
                'entitled_days' => 'required|integer|min:0',
                'carry_forward_days' => 'required|numeric|min:0', // Can be float
            ], [
                'hrms_leave_type_id.exists' => 'The selected leave type does not exist.',
                'hrms_leave_rank_id.exists' => 'The selected leave rank does not exist.',
            ]);

            $leaveModel = HRMSLeaveModel::create($validatedData);
            return response()->json([
                'success' => true,
                'message' => 'Leave model created successfully.',
                'data' => $leaveModel
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
                'message' => 'Failed to create leave model.',
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
        $leaveModel = HRMSLeaveModel::with(['leaveType', 'leaveRank'])->find($id);

        if (!$leaveModel) {
            return response()->json([
                'success' => false,
                'message' => 'Leave model not found.'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'message' => 'Leave model retrieved successfully.',
            'data' => $leaveModel
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
        $leaveModel = HRMSLeaveModel::find($id);

        if (!$leaveModel) {
            return response()->json([
                'success' => false,
                'message' => 'Leave model not found.'
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $validatedData = $request->validate([
                'hrms_leave_type_id' => 'sometimes|required|integer|exists:hrms_leave_type,id',
                'hrms_leave_rank_id' => 'sometimes|required|integer|exists:hrms_leave_rank,id',
                'year_of_service' => 'sometimes|required|integer|min:0',
                'entitled_days' => 'sometimes|required|integer|min:0',
                'carry_forward_days' => 'sometimes|required|numeric|min:0',
            ], [
                'hrms_leave_type_id.exists' => 'The selected leave type does not exist.',
                'hrms_leave_rank_id.exists' => 'The selected leave rank does not exist.',
            ]);

            $leaveModel->update($validatedData);
            return response()->json([
                'success' => true,
                'message' => 'Leave model updated successfully.',
                'data' => $leaveModel
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
                'message' => 'Failed to update leave model.',
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
        $leaveModel = HRMSLeaveModel::find($id);

        if (!$leaveModel) {
            return response()->json([
                'success' => false,
                'message' => 'Leave model not found.'
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $leaveModel->delete(); // Soft deletes the record
            return response()->json([
                'success' => true,
                'message' => 'Leave model deleted successfully.'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete leave model.',
                'errors' => [$e->getMessage()]
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
