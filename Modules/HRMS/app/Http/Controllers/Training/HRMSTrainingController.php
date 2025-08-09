<?php

namespace Modules\HRMS\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB; // For database transactions

// Import all related models
use Modules\HRMS\Models\HRMSTraining;
use Modules\HRMS\Models\HRMSTrainingType;
use Modules\HRMS\Models\HRMSTrainingAwardType;
use Modules\HRMS\Models\HRMSTrainingParticipant;
use Modules\HRMS\Models\HRMSStaff; // Assuming this model exists for staff validation
use App\Models\Branch; // Assuming this model exists for branch validation

/**
 * HRMSTrainingController
 *
 * This API controller manages CRUD operations for HRMSTraining records.
 * It handles validation, creation, retrieval, updating, and deletion of training
 * records, including their associated participants.
 */
class HRMSTrainingController extends Controller
{
    /**
     * Display a listing of the training records.
     *
     * Eager loads related models (branch, trainingType, trainingAwardType, participants with staff)
     * to prevent N+1 query issues and provide comprehensive data.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Eager load relationships for efficient data retrieval
        $trainings = HRMSTraining::with([
            'branch',
            'trainingType',
            'trainingAwardType',
            'participants.staff' // Load staff details for each participant
        ])->paginate(10); // Paginate results for better performance

        return response()->json($trainings);
    }

    /**
     * Display the specified training record.
     *
     * Eager loads related models for a complete view of the training.
     *
     * @param  int  $id The ID of the training record.
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $training = HRMSTraining::with([
            'branch',
            'trainingType',
            'trainingAwardType',
            'participants.staff'
        ])->findOrFail($id); // findOrFail will automatically return a 404 if not found

        return response()->json($training);
    }

    /**
     * Store a newly created training record in storage.
     *
     * Handles creation of the main training record and its associated participants
     * within a database transaction to ensure data integrity.
     *
     * @param  \Illuminate\Http\Request  $request The incoming request.
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Define validation rules for the training record
        $validator = Validator::make($request->all(), [
            'branch_id' => 'required|integer|exists:branch,id',
            'training_start_date' => 'required|date',
            'training_end_date' => 'required|date|after_or_equal:training_start_date',
            'hrms_training_type_id' => 'required|integer|exists:hrms_training_type,id',
            'training_name' => 'required|string|max:255',
            'hrms_training_award_type_id' => 'required|integer|exists:hrms_training_award_type,id',
            'participants' => 'nullable|array', // Participants is an optional array of staff IDs
            'participants.*' => 'integer|exists:hrms_staff,id', // Each participant ID must exist in hrms_staff table
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }

        // Use a database transaction to ensure atomicity
        // If any part of the process fails, all changes are rolled back.
        try {
            DB::beginTransaction();

            // Create the main training record
            $training = HRMSTraining::create([
                'branch_id' => $request->branch_id,
                'training_start_date' => $request->training_start_date,
                'training_end_date' => $request->training_end_date,
                'hrms_training_type_id' => $request->hrms_training_type_id,
                'training_name' => $request->training_name,
                'hrms_training_award_type_id' => $request->hrms_training_award_type_id,
            ]);

            // Attach participants if provided
            if ($request->has('participants') && is_array($request->participants)) {
                $participantsData = [];
                foreach (array_unique($request->participants) as $staffId) {
                    $participantsData[] = [
                        'hrms_training_id' => $training->id,
                        'hrms_staff_id' => $staffId,
                    ];
                }
                // Insert all participants at once
                HRMSTrainingParticipant::insert($participantsData);
            }

            DB::commit(); // Commit the transaction

            // Reload the training with relationships for the response
            $training->load(['branch', 'trainingType', 'trainingAwardType', 'participants.staff']);

            return response()->json([
                'success' => true,
                'message' => 'Training created successfully!',
                'data' => $training
            ], 201); // 201 Created
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback on error
            return response()->json([
                'success' => false,
                'message' => 'Failed to create training.',
                'error' => $e->getMessage()
            ], 500); // 500 Internal Server Error
        }
    }

    /**
     * Update the specified training record in storage.
     *
     * Handles updating the main training record and synchronizing its participants.
     * Participants are replaced entirely with the new list provided.
     *
     * @param  \Illuminate\Http\Request  $request The incoming request.
     * @param  int  $id The ID of the training record to update.
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $training = HRMSTraining::findOrFail($id);

        // Define validation rules for the update operation
        $validator = Validator::make($request->all(), [
            'branch_id' => 'sometimes|required|integer|exists:branch,id',
            'training_start_date' => 'sometimes|required|date',
            'training_end_date' => 'sometimes|required|date|after_or_equal:training_start_date',
            'hrms_training_type_id' => 'sometimes|required|integer|exists:hrms_training_type,id',
            'training_name' => 'sometimes|required|string|max:255',
            'hrms_training_award_type_id' => 'sometimes|required|integer|exists:hrms_training_award_type,id',
            'participants' => 'nullable|array',
            'participants.*' => 'integer|exists:hrms_staff,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Update the main training record
            $training->update($request->only([
                'branch_id',
                'training_start_date',
                'training_end_date',
                'hrms_training_type_id',
                'training_name',
                'hrms_training_award_type_id',
            ]));

            // Sync participants: Delete existing and insert new ones
            // This approach replaces all participants. If you need to add/remove individually,
            // more complex logic would be required.
            if ($request->has('participants')) {
                // Delete existing participants for this training
                HRMSTrainingParticipant::where('hrms_training_id', $training->id)->delete();

                // Insert new participants
                if (is_array($request->participants) && !empty($request->participants)) {
                    $participantsData = [];
                    foreach (array_unique($request->participants) as $staffId) {
                        $participantsData[] = [
                            'hrms_training_id' => $training->id,
                            'hrms_staff_id' => $staffId,
                        ];
                    }
                    HRMSTrainingParticipant::insert($participantsData);
                }
            }

            DB::commit();

            // Reload the training with relationships for the response
            $training->load(['branch', 'trainingType', 'trainingAwardType', 'participants.staff']);

            return response()->json([
                'success' => true,
                'message' => 'Training updated successfully!',
                'data' => $training
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update training.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified training record from storage.
     *
     * Deletes the main training record and all its associated participants.
     *
     * @param  int  $id The ID of the training record to delete.
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $training = HRMSTraining::findOrFail($id);

        try {
            DB::beginTransaction();

            // Delete associated participants first
            HRMSTrainingParticipant::where('hrms_training_id', $training->id)->delete();

            // Then delete the training record itself
            $training->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Training deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete training.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
