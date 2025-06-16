<?php

namespace Modules\HRMS\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\HRMS\Models\HRMSEvent;
use Modules\HRMS\Models\HRMSEventType;
use Illuminate\Validation\ValidationException;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Auth;

class HRMSEventController extends Controller
{
    use LogsActivity;

    public function index(Request $request)
    {
        $query = $request->input('q');

        $events = HRMSEvent::when($query, function ($q) use ($query) {
            $q->where('title', 'like', '%' . $query . '%');
        })
            ->with('eventType')
            ->latest()
            ->paginate(10);

        $eventTypes = HRMSEventType::where('is_active', true)->whereNull('deleted_at')->get();

        return view('hrms::event_management.events.index', compact('events', 'query', 'eventTypes'));
    }

    public function create()
    {
        return view('hrms::event_management.events.create');
    }

    public function show($id)
    {
        // Retrieve the event by its ID
        $event = HRMSEvent::find($id);

        // Check if the event exists
        if (!$event) {
            abort(404, 'Event not found.');
        }

        // Pass the event data to the view
        return view('hrms::event_management.events.show', compact('event'));
    }


    public function store(Request $request)
    {
        $eventRules = [
            'hrms_event_type_id' => ['required', 'exists:hrms_event_type,id'],
            'title'              => ['required', 'string'],
            'start_date'         => ['required', 'date'],
            'end_date'           => ['required', 'date'],
            'event_company'      => ['required', 'string'],
            'event_branch'       => ['required', 'string'],
            'event_venue'        => ['required', 'string'],
            'remarks'            => ['nullable', 'string'],
        ];

        $validatedData = $request->validate($eventRules);

        // Create a new HRMSEvent record
        $event = HRMSEvent::create($validatedData);

        $activityLog = sprintf(
            '%s has created an Event named "%s" on %s',
            Auth::user()->name,
            $event->title,
            now()->format('Y-m-d H:i:s')
        );

        $this->addActivityLog($event, $activityLog);

        return redirect()->route('hrms.event.index')->with('success', 'Event created successfully!');
    }

    public function edit($id)
    {
        // Retrieve the event by its ID
        $event = HRMSEvent::find($id);

        // Check if the event exists
        if (!$event) {
            abort(404, 'Event not found.');
        }

        // Pass the event data to the edit view
        return view('hrms::event_management.events.edit', compact('event'));
    }


    public function update(Request $request, $id)
    {
        $eventRules = [
            'hrms_event_type_id' => ['required', 'exists:hrms_event_type,id'],
            'title'              => ['required', 'string'],
            'start_date'         => ['required', 'date'],
            'end_date'           => ['required', 'date'],
            'event_company'      => ['required', 'string'],
            'event_branch'       => ['required', 'string'],
            'event_venue'        => ['required', 'string'],
            'remarks'            => ['nullable', 'string'],
        ];

        $validatedData = $request->validate($eventRules);

        // Find the event by its ID
        $event = HRMSEvent::find($id);

        // Check if the event exists
        if (!$event) {
            abort(404, 'Event not found.');
        }

        // Capture original data
        $originalData = $event->getOriginal();

        // Update the event with validated data
        $event->update($validatedData);

        // Determine changes
        $formattedChanges = [];
        foreach ($validatedData as $key => $value) {
            if ($originalData[$key] != $value) {
                $formattedChanges[] = sprintf(
                    '%s changed from "%s" to "%s"',
                    ucfirst(str_replace('_', ' ', $key)), // Convert attribute to a readable format
                    $originalData[$key],
                    $value
                );
            }
        }

        // Join all formatted changes into a single string
        $changeLogMessage = implode(', ', $formattedChanges);

        // Get the current date and time
        $timestamp = now()->format('Y-m-d H:i:s');

        // Log the changes
        if (!empty($formattedChanges)) {
            $changeLog = sprintf(
                '%s has updated Event %s on %s with changes: %s',
                Auth::user()->name,
                $event->title,
                $timestamp,
                $changeLogMessage
            );
            $this->addActivityLog($event, $changeLog);
        }



        return redirect()->route('hrms.event.index')->with('success', 'Event updated successfully!');
    }

    public function destroy($id)
    {
        // Find the event by its ID
        $event = HRMSEvent::find($id);

        // Check if the event exists
        if (!$event) {
            abort(404, 'Event not found.');
        }

        // Soft delete the event
        $event->delete();

        return redirect()->route('hrms.event.index')->with('success', 'Event deleted successfully!');
    }
}
