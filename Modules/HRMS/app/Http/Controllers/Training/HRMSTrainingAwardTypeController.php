<?php

namespace Modules\HRMS\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\HRMS\Models\HRMSTrainingAwardType;

class HRMSTrainingAwardTypeController extends Controller
{
    /**
     * Display a listing of the training award types.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $trainingAwardTypes = HRMSTrainingAwardType::all();
        // For API, return JSON data instead of a view
        return response()->json($trainingAwardTypes);
    }

    /**
     * Display the specified training award type.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $trainingAwardType = HRMSTrainingAwardType::findOrFail($id); // Singular variable name
        return response()->json($trainingAwardType);
    }

    /**
     * Store a newly created training award type in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:hrms_training_award_type,name',
            'is_active' => 'boolean', // Add validation for is_active if it's sent
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error', // More general message
                'errors' => $validator->errors() // Provide specific validation errors
            ], 422);
        }

        $trainingAwardType = HRMSTrainingAwardType::create([ // Singular variable name
            'name' => $request->name,
            'is_active' => $request->has('is_active'), // Correctly handles checkbox/boolean
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Training award type added successfully!',
            'data' => $trainingAwardType // Use 'data' for the resource
        ], 201); // 201 Created status for successful resource creation
    }

    /**
     * Update the specified training award type in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $trainingAwardType = HRMSTrainingAwardType::findOrFail($id); // Singular variable name

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:hrms_training_award_type,name,' . $id,
            'is_active' => 'boolean', // Add validation for is_active if it's sent
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error', // More general message
                'errors' => $validator->errors() // Provide specific validation errors
            ], 422);
        }

        $trainingAwardType->update([
            'name' => $request->name,
            'is_active' => $request->has('is_active'), // Now updates is_active
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Training award type updated successfully!',
            'data' => $trainingAwardType // Use 'data' for the resource
        ]);
    }

    /**
     * Remove the specified training award type from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $trainingAwardType = HRMSTrainingAwardType::findOrFail($id); // Singular variable name
        $trainingAwardType->delete();

        return response()->json([
            'success' => true,
            'message' => 'Training award type deleted successfully' // Corrected message
        ]);
    }
}
