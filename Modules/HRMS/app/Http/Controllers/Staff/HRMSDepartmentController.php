<?php

namespace Modules\HRMS\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\HRMS\Models\HRMSDepartment;

/**
 * HRMSDepartmentController
 *
 * This API controller manages CRUD operations for HRMSDepartment records.
 * It handles validation, creation, retrieval, updating, and deletion of department records.
 */
class HRMSDepartmentController extends Controller
{
    /**
     * Display a listing of the departments.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $departments = HRMSDepartment::all();
        return response()->json($departments);
    }

    /**
     * Display the specified department.
     *
     * @param  int  $id The ID of the department.
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $department = HRMSDepartment::findOrFail($id);
        return response()->json($department);
    }

    /**
     * Store a newly created department in storage.
     *
     * @param  \Illuminate\Http\Request  $request The incoming request.
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Define validation rules for creating a department
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:hrms_department,name|max:255',
            'code' => 'required|string|unique:hrms_department,code|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $department = HRMSDepartment::create([
                'name' => $request->name,
                'code' => $request->code,
                'is_active' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Department added successfully!',
                'data' => $department
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create department.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified department in storage.
     *
     * @param  \Illuminate\Http\Request  $request The incoming request.
     * @param  int  $id The ID of the department to update.
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $department = HRMSDepartment::findOrFail($id);

        // Define validation rules for updating a department
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|unique:hrms_department,name,' . $id . '|max:255',
            'code' => 'sometimes|required|string|unique:hrms_department,code,' . $id . '|max:50',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Prepare data for update, ensuring 'is_active' is handled if present
            $updateData = $request->only(['name', 'code']);
            if ($request->has('is_active')) {
                $updateData['is_active'] = $request->is_active;
            }

            $department->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Department updated successfully!',
                'data' => $department
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update department.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified department from storage.
     *
     * @param  int  $id The ID of the department to delete.
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $department = HRMSDepartment::findOrFail($id);

        try {
            $department->delete();

            return response()->json([
                'success' => true,
                'message' => 'Department deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete department.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
