<?php

namespace Modules\HRMS\Http\Controllers\Roster;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\HRMS\Models\HRMSOffday;
use Illuminate\Validation\Rule;

class HRMSOffdayController extends Controller
{
    // List all offdays
    public function index(): JsonResponse
    {
        $offdays = HRMSOffday::all();
        return response()->json($offdays);
    }

    // Store a new offday
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string',
            'holiday_date' => 'required|date',
            'effective_date' => 'nullable|date',
            'recurring_interval' => ['required', Rule::in(['weekly', 'one time', 'monthly', 'quarterly', 'annually'])],
            'recurring_end_date' => 'required|date|after_or_equal:effective_date',
            'holiday_type' => ['required', Rule::in(['sundayOffday', 'special'])],
            'status' => ['required', Rule::in(['active', 'disabled'])],
        ]);

        $offday = HRMSOffday::create($data);
        return response()->json($offday, 201);
    }

    // Show a specific offday
    public function show($id): JsonResponse
    {
        $offday = HRMSOffday::findOrFail($id);
        return response()->json($offday);
    }

    // Update an offday
    public function update(Request $request, $id): JsonResponse
    {
        $offday = HRMSOffday::findOrFail($id);

        $data = $request->validate([
            'name' => 'sometimes|string',
            'holiday_date' => 'sometimes|date',
            'effective_date' => 'sometimes|date',
            'recurring_interval' => ['sometimes', Rule::in(['weekly', 'one time', 'monthly', 'quarterly', 'annually'])],
            'recurring_end_date' => 'sometimes|date|after_or_equal:effective_date',
            'holiday_type' => ['sometimes', Rule::in(['sundayOffday', 'special'])],
            'status' => ['sometimes', Rule::in(['active', 'disabled'])],
        ]);

        $offday->update($data);
        return response()->json($offday);
    }

    // Delete an offday
    public function destroy($id): JsonResponse
    {
        $offday = HRMSOffday::findOrFail($id);
        $offday->delete();

        return response()->json(['message' => 'Offday deleted successfully.']);
    }
}
