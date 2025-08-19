<?php

namespace Modules\CRMS\Http\Controllers\People;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\CRMS\Models\CRMSPeopleFollowUp;

class CRMSPeopleFollowUpController extends Controller
{
    /**
     * Display a listing of follow-ups with related data.
     */
    public function index(Request $request)
    {
        $followUps = CRMSPeopleFollowUp::with([
            'person:id,name',
            'vehicleMake:id,name',
            'vehicleModel:id,name',
            'vehicleBodyType:id,name',
            'vehicleColour:id,name'
        ])
            ->latest()
            ->paginate(20);

        return response()->json($followUps);
    }

    /**
     * Store a newly created follow-up.
     */
    public function store(Request $request)
    {
        $data = $request->only((new CRMSPeopleFollowUp)->getFillable());

        $followUp = CRMSPeopleFollowUp::create($data);

        return response()->json($followUp, 201);
    }

    /**
     * Show a specific follow-up with related data.
     */
    public function show($id)
    {
        $followUp = CRMSPeopleFollowUp::with([
            'person:id,name',
            'vehicleMake:id,name',
            'vehicleModel:id,name',
            'vehicleBodyType:id,name',
            'vehicleColour:id,name'
        ])->findOrFail($id);

        return response()->json($followUp);
    }

    /**
     * Update an existing follow-up.
     */
    public function update(Request $request, $id)
    {
        $followUp = CRMSPeopleFollowUp::findOrFail($id);
        $followUp->update($request->only($followUp->getFillable()));

        return response()->json($followUp);
    }

    /**
     * Remove a follow-up.
     */
    public function destroy($id)
    {
        $followUp = CRMSPeopleFollowUp::findOrFail($id);
        $followUp->delete();

        return response()->json(['message' => 'Follow-up deleted successfully']);
    }
}
