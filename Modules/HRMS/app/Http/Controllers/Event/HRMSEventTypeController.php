<?php

namespace Modules\HRMS\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\HRMS\Models\HRMSEventType;

class HRMSEventTypeController extends Controller
{
    public function index()
    {
        $eventTypes = HRMSEventType::all();
        return view('hrms::event_management.event_types.index', compact('eventTypes'));
    }

    public function show($id)
    {
        $eventType = HRMSEventType::findOrFail($id);
        return response()->json($eventType);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:hrms_event_type,name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Name has been taken',
            ], 422);
        }

        $eventType = HRMSEventType::create([
            'name' => $request->name,
            'is_active' => $request->has('is_active'), // Check if checkbox was sent
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Event type added successfully!',
            'eventType' => $eventType // Return the created event type
        ]);
    }

    public function update(Request $request, $id)
    {
        $eventType = HRMSEventType::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:hrms_event_type,name,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Name has been taken',
            ], 422);
        }

        $eventType->update([
            'name' => $request->name,
        ]);

        return response()->json(['success' => true, 'eventType' => $eventType]);
    }

    public function destroy($id)
    {
        $eventType = HRMSEventType::findOrFail($id);
        $eventType->delete();

        return response()->json(['success' => true, 'message' => 'Event type deleted successfully']);
    }
}
