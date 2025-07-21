<?php

namespace Modules\HRMS\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\HRMS\Classes\AttendanceService;

class HRMSController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $result = $this->attendanceService->test();
        return response()->json($result);
    }
}
