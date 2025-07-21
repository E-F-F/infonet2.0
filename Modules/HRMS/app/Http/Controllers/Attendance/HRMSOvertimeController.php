<?php

namespace Modules\HRMS\Http\Controllers\Overtime;

use App\Http\Controllers\Controller;
use Modules\HRMS\Models\HRMSOvertime;

/**
 * HRMSOvertimeController
 *
 * This API controller manages retrieval operations for HRMSOvertime records.
 * It handles listing and displaying staff overtime records.
 */
class HRMSOvertimeController extends Controller
{
    /**
     * Display a listing of the overtime records.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $overtimes = HRMSOvertime::with('staff')->get();
        return response()->json($overtimes);
    }

    /**
     * Display the specified overtime record.
     *
     * @param  int  $id The ID of the overtime record.
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $overtime = HRMSOvertime::with('staff')->findOrFail($id);
        return response()->json($overtime);
    }
}