<?php

namespace Modules\HRMS\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\HRMS\Models\HRMSEvent;

class HRMSEventController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');

        $events = HRMSEvent::when($query, function ($q) use ($query) {
            $q->where('title', 'like', '%' . $query . '%');
        })
            ->latest()
            ->paginate(10);

        return view('hrms::event_management.events.index', compact('events', 'query'));
    }

    public function show($id)
    {
        return view('hrms::event_management.events.show');
    }

    public function store(Request $request) {}

    public function update(Request $request, $id) {}

    public function destroy($id) {}
}
