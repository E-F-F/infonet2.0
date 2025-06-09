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
        return view('hrms::event_management.event_types.show', compact('eventType'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:hrms_event_type,name',
            // 'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        HRMSEventType::create([
            'name' => $request->name,
            // 'is_active' => $request->is_active ?? true,
        ]);

        return redirect()->route('hrms.event-types.index')->with('success', 'Event type created.');
    }

    public function update(Request $request, $id)
    {
        $eventType = HRMSEventType::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:hrms_event_type,name,' . $id,
            // 'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $eventType->update([
            'name' => $request->name,
            // 'is_active' => $request->is_active ?? true,
        ]);

        return redirect()->route('hrms.event-types.index')->with('success', 'Event type updated.');
    }

    public function destroy($id)
    {
        $eventType = HRMSEventType::findOrFail($id);
        $eventType->delete();

        return redirect()->route('hrms.event-types.index')->with('success', 'Event type deleted.');
    }
}
