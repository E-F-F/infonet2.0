<?php

namespace Modules\HRMS\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\HRMS\Models\HRMSEventType;

/**
 * HRMSEventTypeController
 *
 * This API controller manages CRUD operations for HRMSEventType records.
 * It handles validation, creation, retrieval, updating, and deletion of event types.
 */
class HRMSEventTypeController extends Controller
{
    /**
     * Display a listing of the event types.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $eventTypes = HRMSEventType::all();
        // For API, return JSON data instead of a view
        return response()->json($eventTypes);
    }

    /**
     * Display the specified event type.
     *
     * @param  int  $id The ID of the event type.
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $eventType = HRMSEventType::findOrFail($id); // Singular variable name
        return response()->json($eventType);
    }

    /**
     * Store a newly created event type in storage.
     *
     * @param  \Illuminate\Http\Request  $request The incoming request.
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Define validation rules for creating an event type
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:hrms_event_type,name|max:255',
            'is_active' => 'boolean', // Validate that is_active is a boolean if present
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }

        try {
            $eventType = HRMSEventType::create([
                'name' => $request->name,
                'is_active' => $request->has('is_active') ? $request->is_active : false, // Explicitly set to false if not present
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Event type added successfully!',
                'data' => $eventType
            ], 201); // 201 Created
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create event type.',
                'error' => $e->getMessage()
            ], 500); // 500 Internal Server Error
        }
    }

    /**
     * Update the specified event type in storage.
     *
     * @param  \Illuminate\Http\Request  $request The incoming request.
     * @param  int  $id The ID of the event type to update.
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $eventType = HRMSEventType::findOrFail($id); // Singular variable name

        // Define validation rules for updating an event type
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|unique:hrms_event_type,name,' . $id . '|max:255',
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

            $eventType->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Event type updated successfully!',
                'data' => $eventType
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update event type.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified event type from storage.
     *
     * @param  int  $id The ID of the event type to delete.
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $eventType = HRMSEventType::findOrFail($id); // Singular variable name

        // Before deleting, consider if there are any related 'HRMSEvent' records.
        // If there are, you might want to prevent deletion, or soft delete them too,
        // or reassign them. For now, we'll assume cascading delete or that it's okay
        // if the database handles foreign key constraints (e.g., ON DELETE CASCADE)
        // or if you're using soft deletes on HRMSEvent.
        // For simplicity, we're just deleting the type here.

        try {
            $eventType->delete();

            return response()->json([
                'success' => true,
                'message' => 'Event type deleted successfully (soft deleted).'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete event type.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}