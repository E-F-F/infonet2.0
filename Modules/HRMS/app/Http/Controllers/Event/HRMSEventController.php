<?php

namespace Modules\HRMS\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\HRMS\Models\HRMSEvent;
use Modules\HRMS\Models\HRMSEventType;
use Modules\HRMS\Models\HRMSEventParticipant;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HRMSEventController extends Controller
{
    /**
     * Display a listing of the event records.
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
            ->with(['eventType', 'participants.staff']) // include participants
            ->latest()
            ->paginate(10);

        return response()->json($events);
    }

    /**
     * Display the specified event record.
     */
    public function show($id)
    {
        $event = HRMSEvent::with(['eventType', 'participants.staff'])->findOrFail($id);

        return response()->json($event);
    }

    /**
     * Store a newly created event record.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hrms_event_type_id' => ['required', 'integer', 'exists:hrms_event_type,id'],
            'title'              => ['required', 'string', 'max:255'],
            'start_date'         => ['required', 'date'],
            'end_date'           => ['required', 'date', 'after_or_equal:start_date'],
            'event_company'      => ['nullable', 'string', 'max:255'],
            'event_branch'       => ['nullable', 'string', 'max:255'],
            'event_venue'        => ['nullable', 'string', 'max:255'],
            'remarks'            => ['nullable', 'string'],
            'is_active'          => ['boolean'],
            'participants'       => ['array'], // array of staff IDs
            'participants.*'     => ['integer', 'exists:hrms_staff,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

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
            $data['is_active'] = $request->has('is_active') ? (bool)$request->is_active : false;
            $data['activity_logs'] = [];

            $event = HRMSEvent::create($data);

            // Attach participants
            if ($request->filled('participants')) {
                foreach ($request->participants as $staffId) {
                    HRMSEventParticipant::create([
                        'hrms_event_id' => $event->id,
                        'hrms_staff_id' => $staffId,
                    ]);
                }
            }

            // Log activity
            $userName = Auth::check() ? Auth::user()->name : 'System/API User';
            $event->activity_logs = array_merge($event->activity_logs ?? [], [
                sprintf('%s has created an Event "%s" on %s', $userName, $event->title, now()->format('Y-m-d H:i:s'))
            ]);
            $event->save();

            DB::commit();

            $event->load(['eventType', 'participants.staff']);

            return response()->json([
                'success' => true,
                'message' => 'Event created successfully!',
                'data' => $event
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create event.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified event.
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
            'is_active'          => ['sometimes', 'boolean'],
            'participants'       => ['array'],
            'participants.*'     => ['integer', 'exists:hrms_staff,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $originalData = $event->getOriginal();

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
            if ($request->has('is_active')) {
                $updateData['is_active'] = (bool)$request->is_active;
            }

            $event->update($updateData);

            // Sync participants
            if ($request->filled('participants')) {
                HRMSEventParticipant::where('hrms_event_id', $event->id)->delete();
                foreach ($request->participants as $staffId) {
                    HRMSEventParticipant::create([
                        'hrms_event_id' => $event->id,
                        'hrms_staff_id' => $staffId,
                    ]);
                }
            }

            // Activity log changes
            $formattedChanges = [];
            foreach ($updateData as $key => $value) {
                if (array_key_exists($key, $originalData) && $originalData[$key] != $value) {
                    $oldValue = $originalData[$key];
                    $newValue = $value;

                    if (in_array($key, ['start_date', 'end_date']) && $oldValue instanceof \Illuminate\Support\Carbon) {
                        $oldValue = $oldValue->format('Y-m-d');
                        $newValue = \Illuminate\Support\Carbon::parse($newValue)->format('Y-m-d');
                    }

                    $formattedChanges[] = sprintf('%s changed from "%s" to "%s"', ucfirst(str_replace('_', ' ', $key)), $oldValue, $newValue);
                }
            }

            if (!empty($formattedChanges)) {
                $userName = Auth::check() ? Auth::user()->name : 'System/API User';
                $event->activity_logs = array_merge($event->activity_logs ?? [], [
                    sprintf('%s updated Event "%s" on %s with changes: %s',
                        $userName, $event->title, now()->format('Y-m-d H:i:s'), implode(', ', $formattedChanges))
                ]);
                $event->save();
            }

            DB::commit();

            $event->load(['eventType', 'participants.staff']);

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
     * Remove the specified event.
     */
    public function destroy($id)
    {
        $event = HRMSEvent::findOrFail($id);

        try {
            DB::beginTransaction();

            $userName = Auth::check() ? Auth::user()->name : 'System/API User';
            $event->activity_logs = array_merge($event->activity_logs ?? [], [
                sprintf('%s deleted Event "%s" on %s', $userName, $event->title, now()->format('Y-m-d H:i:s'))
            ]);
            $event->save();

            // deleting participants first (cascade also works if FK cascade set)
            HRMSEventParticipant::where('hrms_event_id', $event->id)->delete();

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
