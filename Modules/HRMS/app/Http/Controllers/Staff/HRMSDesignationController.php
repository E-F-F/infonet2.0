<?php

namespace Modules\HRMS\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\HRMS\Models\HRMSDesignation;
use Modules\HRMS\Transformers\HRMSDesignationResource;

/**
 * HRMSDesignationController
 *
 * This API controller manages CRUD operations for HRMSDesignation records.
 * It handles validation, creation, retrieval, updating, and deletion of designations.
 */
class HRMSDesignationController extends Controller
{
    /**
     * Display a listing of the designations.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = HRMSDesignation::query();
        $query = HRMSDesignation::with(['department', 'parentDesignation', 'leaveRank']);

        if ($search = $request->get('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $designations = $query->get();
        return view('hrms::designation.index', compact('designations'));

        return HRMSDesignationResource::collection($designations);
    }

    /**
     * Display the specified designation.
     *
     * @param  int  $id The ID of the designation.
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $designation = HRMSDesignation::findOrFail($id);
        return response()->json($designation);
    }

    /**
     * Store a newly created designation in storage.
     *
     * @param  \Illuminate\Http\Request  $request The incoming request.
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Define validation rules for creating a designation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:hrms_designation,name|max:255',
            'hrms_department_id' => 'required|exists:hrms_department,id',
            'parent_designation_id' => 'nullable|exists:hrms_designation,id',
            'hrms_leave_rank_id' => 'nullable|exists:hrms_leave_rank,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $designation = HRMSDesignation::create([
                'name' => $request->name,
                'hrms_department_id' => $request->hrms_department_id,
                'parent_designation_id' => $request->parent_designation_id,
                'hrms_leave_rank_id' => $request->hrms_leave_rank_id,
                'is_active' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Designation added successfully!',
                'data' => $designation->load(['department', 'parentDesignation', 'leaveRank']),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating designation: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified designation in storage.
     *
     * @param  \Illuminate\Http\Request  $request The incoming request.
     * @param  int  $id The ID of the designation to update.
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $designation = HRMSDesignation::findOrFail($id);

        // Define validation rules for updating a designation
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|unique:hrms_designation,name,' . $id . '|max:255',
            'hrms_department_id' => 'sometimes|required|exists:hrms_department,id',
            'parent_designation_id' => 'sometimes|nullable|exists:hrms_designation,id',
            'hrms_leave_rank_id' => 'sometimes|nullable|exists:hrms_leave_rank,id',
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
            $updateData = $request->only(['name', 'hrms_department_id', 'parent_designation_id', 'hrms_leave_rank_id']);
            if ($request->has('is_active')) {
                $updateData['is_active'] = $request->is_active;
            }

            $designation->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Designation updated successfully!',
                'data' => $designation
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update designation.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified designation from storage.
     *
     * @param  int  $id The ID of the designation to delete.
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $designation = HRMSDesignation::findOrFail($id);

        try {
            $designation->delete();

            return response()->json([
                'success' => true,
                'message' => 'Designation deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete designation.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
