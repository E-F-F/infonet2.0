<?php

namespace Modules\HRMS\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HRMSEventController extends Controller
{
    public function index()
    {
        return view('hrms::event_management.events.index');
    }

    public function show($id)
    {
        return view('hrms::event_management.events.show');
    }

    public function store(Request $request) {}

    public function update(Request $request, $id) {}

    public function destroy($id) {}
}
