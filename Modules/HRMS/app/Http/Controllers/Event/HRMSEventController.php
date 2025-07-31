<?php

namespace Modules\HRMS\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\HRMS\Models\HRMSEvent;
use Modules\HRMS\Models\HRMSEventType;
use Illuminate\Support\Facades\Validator; // Use Validator facade for explicit validation
use Illuminate\Support\Facades\DB; // For database transactions
use Illuminate\Support\Facades\Auth; // For authenticated user details
// use App\Traits\LogsActivity; // Assuming this trait is available and correctly implemented for API context

/**
 * HRMSEventController
 *
 * This API controller manages CRUD operations for HRMSEvent records.
 * It handles validation, creation, retrieval, updating, and deletion of events.
 */
class HRMSEventController extends Controller
{
    // If LogsActivity trait is used, ensure it's compatible with API context (e.g., doesn't rely on sessions/redirects)
    // use LogsActivity;

    /**
     * Display a listing of the event records.
     *
     * @param  \Illuminate\Http\Request  $request The incoming request for filtering.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = $request->input('q');
        $type = $request->input('type');
        $from = $request->input('from');
        $to = $request->input('to');

        $events = HRMSEvent::when($query, fn($q) => $q->where('title', 'like', "%$query%"))
            ->when($type, fn($q) => $q->where('hrms_event_type_id', $type))
            ->when($from, fn($q) => $q->whereDate('start_date', '>=', $from))
            ->when($to, fn($q) => $q->whereDate('end_date', '<=', $to))
            ->with('eventType')
            ->latest()
            ->paginate(10);

        return response()->json($events);
    }

    /**
     * Display the specified event record.
     *
     * @param  int  $id The ID of the event record.
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Retrieve the event by its ID with its event type
        $event = HRMSEvent::with('eventType')->findOrFail($id); // findOrFail handles 404 automatically

        return response()->json($event);
    }

    /**
     * Store a newly created event record in storage.
     *
     * @param  \Illuminate\Http\Request  $request The incoming request.
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hrms_event_type_id' => ['required', 'integer', 'exists:hrms_event_type,id'],
            'title'              => ['required', 'string', 'max:255'],
            'start_date'         => ['required', 'date'],
            'end_date'           => ['required', 'date', 'after_or_equal:start_date'],
            'event_company'      => ['required', 'string', 'max:255'],
            'event_branch'       => ['required', 'string', 'max:255'],
            'event_venue'        => ['required', 'string', 'max:255'],
            'remarks'            => ['nullable', 'string'],
            'is_active'          => ['boolean'], // Added validation for is_active
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }

        try {
            DB::beginTransaction();

            // Prepare data for creation
            $data = $request->only([
                'hrms_event_type_id',
                'title',
                'start_date',
                'end_date',
                'event_company',
                'event_branch',
                'event_venue',
                'remarks',
            ]);
            // Handle is_active, defaulting to false if not provided
            $data['is_active'] = $request->has('is_active') ? (bool)$request->is_active : false;
            // Initialize activity_logs as an empty array if not present, or ensure it's an array
            $data['activity_logs'] = [];

            // Create a new HRMSEvent record
            $event = HRMSEvent::create($data);

            // Log the activity
            // In a real API, Auth::user() might refer to an API token user.
            // Ensure Auth::user()->name is available or use Auth::id() or a specific staff ID.
            $userName = Auth::check() ? Auth::user()->name : 'System/API User'; // Fallback for API
            $activityLog = sprintf(
                '%s has created an Event named "%s" on %s',
                $userName,
                $event->title,
                now()->format('Y-m-d H:i:s')
            );

            // Append to activity_logs (assuming addActivityLog is part of a trait or helper)
            // If `addActivityLog` updates the model, ensure it's done within the transaction.
            // For direct model update, you can do:
            $event->activity_logs = array_merge($event->activity_logs ?? [], [$activityLog]);
            $event->save(); // Save the updated activity logs

            DB::commit(); // Commit the transaction

            // Reload the event with relationships for the response
            $event->load('eventType');

            return response()->json([
                'success' => true,
                'message' => 'Event created successfully!',
                'data' => $event
            ], 201); // 201 Created
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback on error
            return response()->json([
                'success' => false,
                'message' => 'Failed to create event.',
                'error' => $e->getMessage()
            ], 500); // 500 Internal Server Error
        }
    }

    /**
     * Update the specified event record in storage.
     *
     * @param  \Illuminate\Http\Request  $request The incoming request.
     * @param  int  $id The ID of the event record to update.
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $event = HRMSEvent::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'hrms_event_type_id' => ['sometimes', 'required', 'integer', 'exists:hrms_event_type,id'],
            'title'              => ['sometimes', 'required', 'string', 'max:255'],
            'start_date'         => ['sometimes', 'required', 'date'],
            'end_date'           => ['sometimes', 'required', 'date', 'after_or_equal:start_date'],
            'event_company'      => ['sometimes', 'required', 'string', 'max:255'],
            'event_branch'       => ['sometimes', 'required', 'string', 'max:255'],
            'event_venue'        => ['sometimes', 'required', 'string', 'max:255'],
            'remarks'            => ['nullable', 'string'],
            'is_active'          => ['sometimes', 'boolean'], // Added validation for is_active
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Capture original data for logging changes
            $originalData = $event->getOriginal();

            // Prepare data for update
            $updateData = $request->only([
                'hrms_event_type_id',
                'title',
                'start_date',
                'end_date',
                'event_company',
                'event_branch',
                'event_venue',
                'remarks',
            ]);

            // Handle is_active if present in the request
            if ($request->has('is_active')) {
                $updateData['is_active'] = (bool)$request->is_active;
            }

            // Update the event with validated data
            $event->update($updateData);

            // Determine changes for activity log
            $formattedChanges = [];
            foreach ($updateData as $key => $value) {
                // Ensure originalData has the key and compare
                if (array_key_exists($key, $originalData) && $originalData[$key] != $value) {
                    // Special handling for dates if needed, otherwise direct string conversion is fine
                    $oldValue = $originalData[$key];
                    $newValue = $value;

                    // If dates are involved, format them for readability
                    if (in_array($key, ['start_date', 'end_date']) && $oldValue instanceof \Illuminate\Support\Carbon) {
                        $oldValue = $oldValue->format('Y-m-d');
                        $newValue = \Illuminate\Support\Carbon::parse($newValue)->format('Y-m-d');
                    }

                    $formattedChanges[] = sprintf(
                        '%s changed from "%s" to "%s"',
                        ucfirst(str_replace('_', ' ', $key)), // Convert attribute to a readable format
                        $oldValue,
                        $newValue
                    );
                }
            }

            // Log the changes if any
            if (!empty($formattedChanges)) {
                $userName = Auth::check() ? Auth::user()->name : 'System/API User'; // Fallback for API
                $timestamp = now()->format('Y-m-d H:i:s');
                $changeLogMessage = implode(', ', $formattedChanges);

                $changeLog = sprintf(
                    '%s has updated Event "%s" on %s with changes: %s',
                    $userName,
                    $event->title,
                    $timestamp,
                    $changeLogMessage
                );

                // Append to activity_logs
                $event->activity_logs = array_merge($event->activity_logs ?? [], [$changeLog]);
                $event->save(); // Save the updated activity logs
            }

            DB::commit();

            // Reload the event with relationships for the response
            $event->load('eventType');

            return response()->json([
                'success' => true,
                'message' => 'Event updated successfully!',
                'data' => $event
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update event.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified event record from storage.
     *
     * This method performs a soft delete on the event record.
     *
     * @param  int  $id The ID of the event record to delete.
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $event = HRMSEvent::findOrFail($id);

        try {
            DB::beginTransaction(); // Wrap delete in transaction for consistency

            // Log the deletion activity
            $userName = Auth::check() ? Auth::user()->name : 'System/API User';
            $activityLog = sprintf(
                '%s has deleted Event named "%s" on %s',
                $userName,
                $event->title,
                now()->format('Y-m-d H:i:s')
            );
            // Append to activity_logs before deleting (if you want to capture deletion in logs)
            // Note: This log will be saved to the model *before* it's soft-deleted.
            $event->activity_logs = array_merge($event->activity_logs ?? [], [$activityLog]);
            $event->save(); // Save the updated activity logs before deletion

            // Soft delete the event
            $event->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Event deleted successfully (soft deleted).'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete event.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
