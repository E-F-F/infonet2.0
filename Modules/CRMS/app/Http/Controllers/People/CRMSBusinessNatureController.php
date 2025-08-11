<?php

namespace Modules\CRMS\Http\Controllers\People;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\CRMS\Models\CRMSBusinessNature;

class CRMSBusinessNatureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $per_page = $request->per_page ?? 10;

        $businessNature = CRMSBusinessNature::paginate($per_page);

        return response()->json($businessNature);
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

        $businessNature = CRMSBusinessNature::create($validated);

        return response()->json([
            'message' => 'Business Nature created successfully.',
            'data' => $businessNature
        ], 201);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $businessNature = CRMSBusinessNature::findOrFail($id);

        return response()->json($businessNature);
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

        $businessNature = CRMSBusinessNature::findOrFail($id);
        $businessNature->update($validated);

        return response()->json([
            'message' => 'Business Nature updated successfully.',
            'data' => $businessNature
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $businessNature = CRMSBusinessNature::findOrFail($id);
        $businessNature->delete();

        return response()->json([
            'message' => 'Business Nature deleted successfully.'
        ]);
    }
}
