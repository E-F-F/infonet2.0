<?php

namespace Modules\CRMS\Http\Controllers\People;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\CRMS\Models\CRMSPeopleIncome;

class CRMSPeopleIncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $per_page = $request->per_page ?? 10;

        $peopleIncome = CRMSPeopleIncome::paginate($per_page);

        return response()->json($peopleIncome);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'is_active' => 'required|boolean',
        ]);

        $peopleIncome = CRMSPeopleIncome::create($validated);

        return response()->json([
            'message' => 'People Income created successfully.',
            'data' => $peopleIncome
        ], 201);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $peopleIncome = CRMSPeopleIncome::findOrFail($id);

        return response()->json($peopleIncome);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string',
            'is_active' => 'sometimes|required|boolean',
        ]);

        $peopleIncome = CRMSPeopleIncome::findOrFail($id);
        $peopleIncome->update($validated);

        return response()->json([
            'message' => 'People Income updated successfully.',
            'data' => $peopleIncome
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $peopleIncome = CRMSPeopleIncome::findOrFail($id);
        $peopleIncome->delete();

        return response()->json([
            'message' => 'People Income deleted successfully.'
        ]);
    }
}
