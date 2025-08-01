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

            // Required Fields based on your schema
            'customer_name' => 'required|string',
            'sst_reg_no' => 'required|string', 
            'gst_reg_no' => 'required|string',
            'phone_no' => 'required|string',
            'primary_address' => 'required|string',
            'primary_postcode' => 'required|string',
            'primary_city' => 'required|string',
            'primary_state' => 'required|string',
            'primary_country' => 'required|string',

            // Optional Fields
            'company_name' => 'nullable|string',
            'crms_corporate_group_id' => 'nullable|exists:crms_corporate_group,id',
            'id_type' => 'nullable|string',
            'id_number' => 'nullable|string',
            'tin' => 'nullable|string',
            'hrms_staff_id' => 'nullable|exists:hrms_staff,id',
            'office_no' => 'nullable|string',
            'home_no' => 'nullable|string',
            'user_name' => 'nullable|string',
            'user_phone_no' => 'nullable|string',
            'fax_no' => 'nullable|string',
            'email' => 'nullable|email',
            'postal_address' => 'nullable|string',
            'postal_postcode' => 'nullable|string',
            'postal_city' => 'nullable|string',
            'postal_state' => 'nullable|string',
            'postal_country' => 'nullable|string',
            'zone' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'dob' => 'nullable|date',
            'crms_people_race_id' => 'nullable|exists:crms_people_race,id',
            'religion' => 'nullable|string',
            'crms_people_income_id' => 'nullable|exists:crms_people_income,id',
            'marital_status' => 'nullable|string',
            'company_size' => 'nullable|string',
            'sector' => 'nullable|string',
            'crms_business_nature_id' => 'nullable|exists:crms_business_nature,id',
            'crms_people_occupation_id' => 'nullable|exists:crms_people_occupation,id',
            'grading' => 'nullable|string',
            'is_corporate' => 'boolean',
            'lifestyle_interest' => 'nullable|string',
            'last_contact_date' => 'nullable|date',
            'link_customer' => 'nullable|string',
            'link_customer_type' => 'nullable|string',
            'terms' => 'nullable|string',
            'price_scheme' => 'nullable|string',
            'notes' => 'nullable|string',
            'log' => 'nullable|string',
        ]);

        $people = CRMSPeople::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Customer added successfully.',
            'data' => $people
        ], 201);
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
