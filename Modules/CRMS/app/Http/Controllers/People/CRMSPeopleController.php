<?php

namespace Modules\CRMS\Http\Controllers\People;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\CRMS\Models\CRMSPeople;

class CRMSPeopleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $peoples = CRMSPeople::query()
            ->when(!is_null($request->branch_id), fn($q, $branchId) => $q->where('branch_id', $branchId))
            ->when(!is_null($request->name), fn($q, $name) => $q->where('customer_name', 'like', "%$name%"))
            ->when(!is_null($request->company), fn($q, $company) => $q->where('company_name', 'like', "%$company%"))
            ->when(!is_null($request->ic), fn($q, $ic) => $q->where('id_number', 'like', "%$ic%"))
            ->when(!is_null($request->reg_no), fn($q, $regNo) => $q->where('sst_reg_no', 'like', "%$regNo%"))
            ->when(!is_null($request->chassis_no), fn($q, $chassisNo) => $q->whereHas('vehicleInfo', fn($q) => $q->where('chassis_no', 'like', "%$chassisNo%")))
            ->when(!is_null($request->engine_no), fn($q, $engineNo) => $q->whereHas('vehicleInfo', fn($q) => $q->where('engine_no', 'like', "%$engineNo%")))
            ->when(!is_null($request->stock_no), fn($q, $stockNo) => $q->whereHas('vehicleInfo', fn($q) => $q->where('stock_no', 'like', "%$stockNo%")))
            ->with('vehicleInfo')
            ->get();
        return response()->json($peoples);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'branch_id' => 'nullable|exists:branch,id',
            'status' => 'nullable|string',
            'under_company_registration' => 'nullable|boolean',
            'customer_name' => 'nullable|string',
            'company_name' => 'nullable|string',
            'crms_corporate_group_id' => 'nullable|exists:crms_corporate_group,id',
            'id_type' => 'nullable|string',
            'id_number' => 'nullable|string',
            'tin' => 'nullable|string',
            'sst_reg_no' => 'nullable|string',
            'sa' => 'nullable|exists:hrms_staff,id',
            'telephone_o' => 'nullable|string',
            'telephone_h' => 'nullable|string',
            'owner_hp_no' => 'nullable|string',
            'user_name' => 'nullable|string',
            'user_hp_no' => 'nullable|string',
            'fax_no' => 'nullable|string',
            'email' => 'nullable|email',
            'postal_address' => 'nullable|string',
            'postal_postcode' => 'nullable|string',
            'postal_city' => 'nullable|string',
            'postal_state' => 'nullable|string',
            'postal_country' => 'nullable|string',
            'primary_address' => 'nullable|string',
            'primary_postcode' => 'nullable|string',
            'primary_city' => 'nullable|string',
            'primary_state' => 'nullable|string',
            'primary_country' => 'nullable|string',
            'zone' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'dob' => 'nullable|date',
            'race' => 'nullable|exists:crms_people_race,id',
            'religion' => 'nullable|string',
            'monthly_house_income' => 'nullable|exists:crms_people_income,id',
            'marital_status' => 'nullable|string',
            'company_size' => 'nullable|string',
            'sector' => 'nullable|string',
            'nature_of_business' => 'nullable|exists:crms_business_nature,id',
            'occupation' => 'nullable|exists:crms_people_occupation,id',
            'grading' => 'nullable|string',
            'is_corporate' => 'boolean',
            'lifestyle_interest' => 'nullable|string',
            'last_contact_date' => 'nullable|date',
            'link_customer' => 'nullable|string',
            'link_customer_type' => 'nullable|string',
            'terms' => 'nullable|string',
            'price_scheme' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $people = CRMSPeople::create($validated);
        return response()->json($people, 201);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $people = CRMSPeople::with('vehicleInfo')->findOrFail($id);
        return response()->json($people);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
