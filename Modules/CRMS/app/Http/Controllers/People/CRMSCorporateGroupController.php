<?php

namespace Modules\CRMS\Http\Controllers\People;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\CRMS\Models\CRMSCorporateGroup;

class CRMSCorporateGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $per_page = $request->per_page ?? 10;

        $corporateGroup = CRMSCorporateGroup::paginate($per_page);

        return response()->json($corporateGroup);
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

        $corporateGroup = CRMSCorporateGroup::create($validated);

        return response()->json([
            'message' => 'Corporate Group created successfully.',
            'data' => $corporateGroup
        ], 201);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $corporateGroup = CRMSCorporateGroup::findOrFail($id);

        return response()->json($corporateGroup);
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

        $corporateGroup = CRMSCorporateGroup::findOrFail($id);
        $corporateGroup->update($validated);

        return response()->json([
            'message' => 'Corporate Group updated successfully.',
            'data' => $corporateGroup
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $corporateGroup = CRMSCorporateGroup::findOrFail($id);
        $corporateGroup->delete();

        return response()->json([
            'message' => 'Corporate Group deleted successfully.'
        ]);
    }
}
