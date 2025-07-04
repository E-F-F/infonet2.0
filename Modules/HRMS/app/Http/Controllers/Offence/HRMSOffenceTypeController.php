<?php

namespace Modules\HRMS\Http\Controllers\Offence; // Assuming a 'Disciplinary' folder for offences

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\HRMS\Models\HRMSOffenceType;

/**
 * HRMSOffenceTypeController
 *
 * This API controller manages CRUD operations for HRMSOffenceType records.
 * It handles validation, creation, retrieval, updating, and deletion of offence types.
 */
class HRMSOffenceTypeController extends Controller
{
    /**
     * Display a listing of the offence types.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $offenceTypes = HRMSOffenceType::all();
        return response()->json($offenceTypes);
    }

    /**
     * Display the specified offence type.
     *
     * @param  int  $id The ID of the offence type.
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $offenceType = HRMSOffenceType::findOrFail($id); // Singular variable name
        return response()->json($offenceType);
    }

    /**
     * Store a newly created offence type in storage.
     *
     * @param  \Illuminate\Http\Request  $request The incoming request.
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Define validation rules for creating an offence type
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:hrms_offence_type,name|max:255',
            'is_active' => 'boolean', // Validate that is_active is a boolean if present
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }

        try {
            $offenceType = HRMSOffenceType::create([
                'name' => $request->name,
                'is_active' => $request->has('is_active') ? $request->is_active : false, // Explicitly set to false if not present
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Offence type added successfully!',
                'data' => $offenceType
            ], 201); // 201 Created
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create offence type.',
                'error' => $e->getMessage()
            ], 500); // 500 Internal Server Error
        }
    }

    /**
     * Update the specified offence type in storage.
     *
     * @param  \Illuminate\Http\Request  $request The incoming request.
     * @param  int  $id The ID of the offence type to update.
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $offenceType = HRMSOffenceType::findOrFail($id); // Singular variable name

        // Define validation rules for updating an offence type
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|unique:hrms_offence_type,name,' . $id . '|max:255',
            'is_active' => 'sometimes|boolean', // Validate that is_active is a boolean if present
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Prepare data for update, ensuring 'is_active' is handled if present
            $updateData = $request->only('name');
            if ($request->has('is_active')) {
                $updateData['is_active'] = $request->is_active;
            }

            $offenceType->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Offence type updated successfully!',
                'data' => $offenceType
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update offence type.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified offence type from storage.
     *
     * @param  int  $id The ID of the offence type to delete.
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $offenceType = HRMSOffenceType::findOrFail($id); // Singular variable name

        // Before deleting, consider if there are any related 'HRMSOffence' records.
        // If there are, you might want to prevent deletion, or soft delete them too,
        // or reassign them. For now, we'll assume cascading delete or that it's okay
        // if the database handles foreign key constraints (e.g., ON DELETE CASCADE)
        // or if you're using soft deletes on HRMSOffence.
        // If HRMSOffence does NOT use soft deletes, and you delete an OffenceType,
        // it could lead to orphaned HRMSOffence records if your DB doesn't cascade.
        // For simplicity, we're just deleting the type here.

        try {
            $offenceType->delete();

            return response()->json([
                'success' => true,
                'message' => 'Offence type deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete offence type.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
