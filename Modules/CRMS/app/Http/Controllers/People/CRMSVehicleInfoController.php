<?php

namespace Modules\CRMS\Http\Controllers\People;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\CRMS\Models\CRMSPeople;
use Modules\CRMS\Models\CRMSVehicleInfo;

class CRMSVehicleInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $per_page = $request->per_page ?? 10;

        $vehicles = CRMSVehicleInfo::query()
            ->when($request->filled('crms_people_id'), fn($q) => $q->where('crms_people_id', $request->crms_people_id))
            ->when($request->filled('branch_id'), fn($q) => $q->where('branch_id', $request->branch_id))
            ->when($request->filled('chassis_no'), fn($q) => $q->where('chassis_no', 'like', '%' . $request->chassis_no . '%'))
            ->with([
                'make:id,name',   // optional: if you define relationship
                'model:id,name',  // optional: if you define relationship
            ])
            ->paginate($per_page);

        return response()->json($vehicles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'branch_id' => 'nullable|exists:branch,id',
            'crms_people_id' => 'nullable|exists:crms_people,id',
            'registration_no' => 'nullable|string',
            'stock_no' => 'nullable|string',
            'date_in' => 'nullable|date',
            'date_to_hq' => 'nullable|date',
            'colour' => 'nullable|string',
            'accesories_std' => 'nullable|string',
            'accesories_opt' => 'nullable|string',
            'body' => 'nullable|string',
            'chassis_no' => 'nullable|string',
            'engine_no' => 'nullable|string',
            'manufacture_year' => 'nullable|string',
            'ims_vehicle_make_id' => 'nullable|exists:ims_vehicle_make,id',
            'ims_vehicle_model_id' => 'nullable|exists:ims_vehicle_model,id',
            'type' => 'nullable|string',
            'rec_net_sp' => 'nullable|string',
            'accesories_otrCost' => 'nullable|string',
            'rec_otr_sp_stdAcc' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $vehicle = CRMSVehicleInfo::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Vehicle info saved.',
            'data' => $vehicle
        ], 201);
    }



    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $people = CRMSPeople::with([
            'vehicleInfo',
            'branch:id,name',
            'corporateGroup:id,name',
            'race:id,name',
            'income:id,name',
            'businessNature:id,name',
            'occupation:id,name',
            'staff:id,name',
        ])->findOrFail($id);

        return response()->json($people);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $people = CRMSPeople::findOrFail($id);

        $validated = $request->validate([
            'branch_id' => 'nullable|exists:branch,id',
            'status' => 'nullable|string',
            'under_company_registration' => 'nullable|boolean',

            // Required Fields – use sometimes|required for PATCH-like update
            'customer_name' => 'sometimes|required|string',
            'sst_reg_no' => 'sometimes|required|string',
            'gst_reg_no' => 'sometimes|required|string',
            'phone_no' => 'sometimes|required|string',
            'primary_address' => 'sometimes|required|string',
            'primary_postcode' => 'sometimes|required|string',
            'primary_city' => 'sometimes|required|string',
            'primary_state' => 'sometimes|required|string',
            'primary_country' => 'sometimes|required|string',

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

        $people->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Customer updated successfully.',
            'data' => $people
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
