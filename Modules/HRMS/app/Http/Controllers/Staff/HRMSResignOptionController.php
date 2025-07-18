<?php

namespace Modules\HRMS\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\HRMS\Models\HRMSResignOption;

/**
 * HRMSResignOptionController
 *
 * This API controller manages CRUD operations for HRMSResignOption records.
 * It handles validation, creation, retrieval, updating, and deletion of resign option records.
 */
class HRMSResignOptionController extends Controller
{
    /**
     * Display a listing of the resign options.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $resignOptions = HRMSResignOption::all();
        return response()->json($resignOptions);
    }

    /**
     * Display the specified resign option.
     *
     * @param  int  $id The ID of the resign option.
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $resignOption = HRMSResignOption::findOrFail($id);
        return response()->json($resignOption);
    }

    /**
     * Store a newly created resign option in storage.
     *
     * @param  \Illuminate\Http\Request  $request The incoming request.
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Define validation rules for creating a resign option
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:hrms_resign_option,name|max:255',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $resignOption = HRMSResignOption::create([
                'name' => $request->name,
                'is_active' => $request->has('is_active') ? $request->is_active : false,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Resign option added successfully!',
                'data' => $resignOption
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create resign option.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resign option in storage.
     *
     * @param  \Illuminate\Http\Request  $request The incoming request.
     * @param  int  $id The ID of the resign option to update.
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $resignOption = HRMSResignOption::findOrFail($id);

        // Define validation rules for updating a resign option
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|unique:hrms_resign_option,name,' . $id . '|max:255',
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
            $updateData = $request->only(['name']);
            if ($request->has('is_active')) {
                $updateData['is_active'] = $request->is_active;
            }

            $resignOption->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Resign option updated successfully!',
                'data' => $resignOption
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update resign option.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resign option from storage.
     *
     * @param  int  $id The ID of the resign option to delete.
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $resignOption = HRMSResignOption::findOrFail($id);

        try {
            $resignOption->delete();

            return response()->json([
                'success' => true,
                'message' => 'Resign option deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete resign option.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
