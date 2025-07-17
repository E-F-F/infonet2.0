<?php

namespace Modules\HRMS\Http\Controllers\Roster;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\HRMS\Models\HRMSHoliday;
use Illuminate\Validation\Rule;

class HRMSHolidayController extends Controller
{
    // List all holidays
    public function index(): JsonResponse
    {
        $holidays = HRMSHoliday::all();
        return response()->json($holidays);
    }

    // Store a new holiday
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|unique:hrms_holiday,name',
            'holiday_date' => 'required|date|unique:hrms_holiday,holiday_date',
            'effective_date' => 'required|date',
            'type' => 'nullable|string',
            'status' => 'nullable|string|in:active,inactive',
        ]);

        $holiday = HRMSHoliday::create($data);
        return response()->json($holiday, 201);
    }

    // Show a single holiday
    public function show($id): JsonResponse
    {
        $holiday = HRMSHoliday::findOrFail($id);
        return response()->json($holiday);
    }

    // Update a holiday
    public function update(Request $request, $id): JsonResponse
    {
        $holiday = HRMSHoliday::findOrFail($id);

        $data = $request->validate([
            'name' => ['sometimes', 'string', Rule::unique('hrms_holiday')->ignore($holiday->id)],
            'holiday_date' => ['sometimes', 'date', Rule::unique('hrms_holiday')->ignore($holiday->id)],
            'effective_date' => 'sometimes|date',
            'type' => 'nullable|string',
            'status' => 'nullable|string|in:active,inactive',
        ]);

        $holiday->update($data);
        return response()->json($holiday);
    }

    // Delete a holiday
    public function destroy($id): JsonResponse
    {
        $holiday = HRMSHoliday::findOrFail($id);
        $holiday->delete();

        return response()->json(['message' => 'Holiday deleted successfully']);
    }
}
