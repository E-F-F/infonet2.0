<?php

namespace Modules\HRMS\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\HRMS\Models\HRMSDepartment;
use Illuminate\Http\JsonResponse;

class DepartmentController extends Controller
{
    /**
     * Display a listing of departments.
     */
    public function index(): JsonResponse
    {
        $departments = HRMSDepartment::all();
        return response()->json(['data' => $departments]);
    }

    /**
     * Store a newly created department.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:100|unique:hrms_department,code',
            'is_active' => 'required|boolean',
        ]);

        $department = HRMSDepartment::create($validated);
        return response()->json(['message' => 'Department created successfully.', 'data' => $department], 201);
    }

    /**
     * Display the specified department.
     */
    public function show($id): JsonResponse
    {
        $department = HRMSDepartment::find($id);

        if (!$department) {
            return response()->json(['message' => 'Department not found.'], 404);
        }

        return response()->json(['data' => $department]);
    }

    /**
     * Update the specified department.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $department = HRMSDepartment::find($id);

        if (!$department) {
            return response()->json(['message' => 'Department not found.'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'code' => 'sometimes|required|string|max:100|unique:hrms_department,code,' . $id,
            'is_active' => 'sometimes|required|boolean',
        ]);

        $department->update($validated);

        return response()->json(['message' => 'Department updated successfully.', 'data' => $department]);
    }

    /**
     * Soft delete the specified department.
     */
    public function destroy($id): JsonResponse
    {
        $department = HRMSDepartment::find($id);

        if (!$department) {
            return response()->json(['message' => 'Department not found.'], 404);
        }

        $department->delete();

        return response()->json(['message' => 'Department deleted successfully.']);
    }
}
