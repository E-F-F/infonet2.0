<?php

namespace Modules\HRMS\Http\Controllers\Offence;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\HRMS\Models\HRMSOffenceActionTaken;

/**
 * HRMSOffenceActionTakenController
 *
 * This API controller manages CRUD operations for HRMSOffenceActionTaken records.
 * It handles validation, creation, retrieval, updating, and deletion of offence action taken records.
 */
class HRMSOffenceActionTakenController extends Controller
{
    /**
     * Display a listing of the offence actions taken.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $offenceActions = HRMSOffenceActionTaken::all();
        return response()->json($offenceActions);
    }

    /**
     * Display the specified offence action taken.
     *
     * @param  int  $id The ID of the offence action taken.
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $offenceAction = HRMSOffenceActionTaken::findOrFail($id);
        return response()->json($offenceAction);
    }

    /**
     * Store a newly created offence action taken in storage.
     *
     * @param  \Illuminate\Http\Request  $request The incoming request.
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Define validation rules for creating an offence action taken
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:hrms_offence_action_taken,name|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $offenceAction = HRMSOffenceActionTaken::create([
                'name' => $request->name,
                'is_active' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Offence action taken added successfully!',
                'data' => $offenceAction
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create offence action taken.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified offence action taken in storage.
     *
     * @param  \Illuminate\Http\Request  $request The incoming request.
     * @param  int  $id The ID of the offence action taken to update.
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $offenceAction = HRMSOffenceActionTaken::findOrFail($id);

        // Define validation rules for updating an offence action taken
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|unique:hrms_offence_action_taken,name,' . $id . '|max:255',
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

            $offenceAction->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Offence action taken updated successfully!',
                'data' => $offenceAction
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update offence action taken.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified offence action taken from storage.
     *
     * @param  int  $id The ID of the offence action taken to delete.
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $offenceAction = HRMSOffenceActionTaken::findOrFail($id);

        try {
            $offenceAction->delete();

            return response()->json([
                'success' => true,
                'message' => 'Offence action taken deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete offence action taken.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
