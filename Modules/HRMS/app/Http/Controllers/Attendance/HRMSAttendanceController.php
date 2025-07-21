<?php

namespace Modules\HRMS\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use Modules\HRMS\Models\HRMSAttendance;

/**
 * HRMSAttendanceController
 *
 * This API controller manages retrieval operations for HRMSAttendance records.
 * It handles listing and displaying staff attendance records.
 */
class HRMSAttendanceController extends Controller
{
    /**
     * Display a listing of the attendance records.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $attendances = HRMSAttendance::with('staff')->get();
        return response()->json($attendances);
    }

    /**
     * Display the specified attendance record.
     *
     * @param  int  $id The ID of the attendance record.
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $attendance = HRMSAttendance::with('staff')->findOrFail($id);
        return response()->json($attendance);
    }
}
