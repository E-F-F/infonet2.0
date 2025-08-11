<?php

namespace Modules\CRMS\Http\Controllers\People;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\CRMS\Models\CRMSPeopleRace;

class CRMSPeopleRaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $per_page = $request->per_page ?? 10;

        $peopleRace = CRMSPeopleRace::paginate($per_page);

        return response()->json($peopleRace);
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

        $peopleRace = CRMSPeopleRace::create($validated);

        return response()->json([
            'message' => 'People Race created successfully.',
            'data' => $peopleRace
        ], 201);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $peopleRace = CRMSPeopleRace::findOrFail($id);

        return response()->json($peopleRace);
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

        $peopleRace = CRMSPeopleRace::findOrFail($id);
        $peopleRace->update($validated);

        return response()->json([
            'message' => 'People Race updated successfully.',
            'data' => $peopleRace
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $peopleRace = CRMSPeopleRace::findOrFail($id);
        $peopleRace->delete();

        return response()->json([
            'message' => 'People Race deleted successfully.'
        ]);
    }
}
