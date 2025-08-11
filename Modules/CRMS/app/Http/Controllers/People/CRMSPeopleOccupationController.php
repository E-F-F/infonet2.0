<?php

namespace Modules\CRMS\Http\Controllers\People;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\CRMS\Models\CRMSPeopleOccupation;

class CRMSPeopleOccupationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $per_page = $request->per_page ?? 10;

        $peopleOccupation = CRMSPeopleOccupation::paginate($per_page);

        return response()->json($peopleOccupation);
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

        $peopleOccupation = CRMSPeopleOccupation::create($validated);

        return response()->json([
            'message' => 'People Occupation created successfully.',
            'data' => $peopleOccupation
        ], 201);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $peopleOccupation = CRMSPeopleOccupation::findOrFail($id);

        return response()->json($peopleOccupation);
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

        $peopleOccupation = CRMSPeopleOccupation::findOrFail($id);
        $peopleOccupation->update($validated);

        return response()->json([
            'message' => 'People Occupation updated successfully.',
            'data' => $peopleOccupation
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $peopleOccupation = CRMSPeopleOccupation::findOrFail($id);
        $peopleOccupation->delete();

        return response()->json([
            'message' => 'People Occupation deleted successfully.'
        ]);
    }
}
