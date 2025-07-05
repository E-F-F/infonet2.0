<?php

namespace Modules\HRMS\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\HRMS\Models\HRMSTrainingType;

class HRMSTrainingTypeController extends Controller
{
    /**
     * Display a listing of the training types.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $trainingTypes = HRMSTrainingType::all();
        // For API, return JSON data instead of a view
        return response()->json($trainingTypes);
    }

    /**
     * Display the specified training type.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $trainingType = HRMSTrainingType::findOrFail($id); // Changed variable name
        return response()->json($trainingType);
    }

    /**
     * Store a newly created training type in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:hrms_training_type,name',
            'is_active' => 'boolean', // Added validation for is_active
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error', // More general message
                'errors' => $validator->errors() // Provide specific validation errors
            ], 422);
        }

        $trainingType = HRMSTrainingType::create([ // Changed variable name
            'name' => $request->name,
            'is_active' => $request->has('is_active'), // Correctly handles checkbox/boolean
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Training type added successfully!',
            'data' => $trainingType // Use 'data' for the resource
        ], 201); // 201 Created status for successful resource creation
    }

    /**
     * Update the specified training type in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $trainingType = HRMSTrainingType::findOrFail($id); // Changed variable name

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:hrms_training_type,name,' . $id,
            'is_active' => 'boolean', // Added validation for is_active
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error', // More general message
                'errors' => $validator->errors() // Provide specific validation errors
            ], 422);
        }

        $trainingType->update([
            'name' => $request->name,
            'is_active' => $request->has('is_active'), // Now updates is_active
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Training type updated successfully!',
            'data' => $trainingType // Use 'data' for the resource
        ]);
    }

    /**
     * Remove the specified training type from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $trainingType = HRMSTrainingType::findOrFail($id); // Changed variable name
        $trainingType->delete();

        return response()->json([
            'success' => true,
            'message' => 'Training type deleted successfully'
        ]);
    }
}
