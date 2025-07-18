<?php

namespace Modules\HRMS\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\HRMS\Models\HRMSMaritalStatus;

/**
 * HRMSMaritalStatusController
 *
 * This API controller manages CRUD operations for HRMSMaritalStatus records.
 * It handles validation, creation, retrieval, updating, and deletion of marital status records.
 */
class HRMSMaritalStatusController extends Controller
{
    /**
     * Display a listing of the marital statuses.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $maritalStatuses = HRMSMaritalStatus::all();
        return response()->json($maritalStatuses);
    }

    /**
     * Display the specified marital status.
     *
     * @param  int  $id The ID of the marital status.
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $maritalStatus = HRMSMaritalStatus::findOrFail($id);
        return response()->json($maritalStatus);
    }

    /**
     * Store a newly created marital status in storage.
     *
     * @param  \Illuminate\Http\Request  $request The incoming request.
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Define validation rules for creating a marital status
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:hrms_marital_status,name|max:255',
            'code' => 'required|string|max:50',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $maritalStatus = HRMSMaritalStatus::create([
                'name' => $request->name,
                'code' => $request->code,
                'is_active' => $request->has('is_active') ? $request->is_active : false,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Marital status added successfully!',
                'data' => $maritalStatus
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create marital status.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified marital status in storage.
     *
     * @param  \Illuminate\Http\Request  $request The incoming request.
     * @param  int  $id The ID of the marital status to update.
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $maritalStatus = HRMSMaritalStatus::findOrFail($id);

        // Define validation rules for updating a marital status
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|unique:hrms_marital_status,name,' . $id . '|max:255',
            'code' => 'sometimes|required|string|max:50',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Prepare data for update
            $updateData = $request->only(['name', 'code']);
            if ($request->has('is_active')) {
                $updateData['is_active'] = $request->is_active;
            }

            $maritalStatus->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Marital status updated successfully!',
                'data' => $maritalStatus
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update marital status.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified marital status from storage.
     *
     * @param  int  $id The ID of the marital status to delete.
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $maritalStatus = HRMSMaritalStatus::findOrFail($id);

        try {
            $maritalStatus->delete();

            return response()->json([
                'success' => true,
                'message' => 'Marital status deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete marital status.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}