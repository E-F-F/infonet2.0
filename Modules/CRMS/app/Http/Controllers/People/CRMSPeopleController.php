<?php

namespace Modules\CRMS\Http\Controllers\People;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\CRMS\Models\CRMSPeople;
use Modules\CRMS\Transformers\CRMSPeopleResource;

class CRMSPeopleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $per_page = $request->per_page ?? 10;
        $activePassive = $request->active_passive; // e.g., 'Active' or 'Passive'
        $name = $request->name; // Partial or full name search
        $lastContactFrom = $request->last_contact_from; // e.g., 2025-01-01
        $lastContactTo = $request->last_contact_to; // e.g., 2025-08-31

        $people = CRMSPeople::select([
            'id',
            'customer_name',
            'people_status',
            'people_type',
            'last_contact_date',
            'owner_phone',
            'crms_company_id',
            'hrms_staff_id'
        ])
            ->when($activePassive, function ($query, $activePassive) {
                return $query->where('people_status', $activePassive);
            })
            ->when($name, function ($query, $name) {
                return $query->where('customer_name', 'like', "%{$name}%");
            })
            ->when($lastContactFrom && $lastContactTo, function ($query) use ($lastContactFrom, $lastContactTo) {
                return $query->whereBetween('last_contact_date', [$lastContactFrom, $lastContactTo]);
            })
            ->when($lastContactFrom && !$lastContactTo, function ($query) use ($lastContactFrom) {
                return $query->whereDate('last_contact_date', '>=', $lastContactFrom);
            })
            ->when(!$lastContactFrom && $lastContactTo, function ($query) use ($lastContactTo) {
                return $query->whereDate('last_contact_date', '<=', $lastContactTo);
            })
            ->with([
                'company:id,company_name',
                'salesperson:id,name'
            ])
            ->paginate($per_page);

        return response()->json($people);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            // Foreign keys
            'hrms_staff_id' => 'nullable|exists:hrms_staff,id',
            'crms_company_id' => 'nullable|exists:crms_company,id',
            'crms_people_race_id' => 'nullable|exists:crms_people_race,id',
            'crms_people_income_id' => 'nullable|exists:crms_people_income,id',
            'crms_people_occupation_id' => 'nullable|exists:crms_people_occupation,id',
            'crms_business_nature_id' => 'nullable|exists:crms_business_nature,id',

            // Main attributes
            'people_type' => 'nullable|string|max:50',
            'people_status' => 'nullable|string|max:50',
            'grading' => 'nullable|string|max:50',
            'last_contact_date' => 'nullable|date',
            'under_company_registration' => 'nullable|boolean',

            // Personal Details
            'customer_name' => 'required|string|max:255',
            'id_type' => 'nullable|string|max:50',
            'id_number' => 'nullable|string|max:50',
            'tin' => 'nullable|string|max:50',
            'dob' => 'nullable|date',
            'owner_phone' => 'required|string|max:20',
            'home_no' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',

            // Other Contact
            'contact_person_name' => 'nullable|string|max:255',
            'contact_person_phone' => 'nullable|string|max:20',
            'office_no' => 'nullable|string|max:20',
            'fax_no' => 'nullable|string|max:20',

            // Home Address
            'primary_address' => 'required|string|max:255',
            'primary_postcode' => 'required|string|max:10',
            'primary_city' => 'required|string|max:100',
            'primary_state' => 'required|string|max:100',
            'primary_country' => 'required|string|max:100',

            // Postal Address
            'postal_address' => 'nullable|string|max:255',
            'postal_postcode' => 'nullable|string|max:10',
            'postal_city' => 'nullable|string|max:100',
            'postal_state' => 'nullable|string|max:100',
            'postal_country' => 'nullable|string|max:100',

            // Other Info
            'zone' => 'nullable|string|max:100',
            'religion' => 'nullable|string|max:100',
            'marital_status' => 'nullable|string|max:50',
            'is_corporate' => 'boolean',
            'lifestyle_interest' => 'nullable|string'
        ]);

        $people = CRMSPeople::create($validatedData);

        return response()->json([
            'message' => 'People created successfully',
            'data' => $people
        ], 201);
    }

    /**
     * Show the specified resource from storage.
     */
    public function show($id)
    {
        $people = CRMSPeople::with([
            'company:id,company_name',
            'staff.personal:id,full_name', // Load staff personal details
            'race:id,name',
            'income:id,income_range',
            'occupation:id,name',
            'businessNature:id,name'
        ])->find($id);

        if (!$people) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        return new CRMSPeopleResource($people);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Find the specific resource by its ID
        $people = CRMSPeople::find($id);

        // If the resource doesn't exist, return a 404 Not Found response
        if (!$people) {
            return response()->json(['message' => 'People not found'], 404);
        }

        // Validate the incoming data from the request
        // The validation rules are similar to the store method
        $validatedData = $request->validate([
            // Foreign keys
            'hrms_staff_id' => 'nullable|exists:hrms_staff,id',
            'crms_company_id' => 'nullable|exists:crms_company,id',
            'crms_people_race_id' => 'nullable|exists:crms_people_race,id',
            'crms_people_income_id' => 'nullable|exists:crms_people_income,id',
            'crms_people_occupation_id' => 'nullable|exists:crms_people_occupation,id',
            'crms_business_nature_id' => 'nullable|exists:crms_business_nature,id',

            // Main attributes
            'people_type' => 'nullable|string|max:50',
            'people_status' => 'nullable|string|max:50',
            'grading' => 'nullable|string|max:50',
            'last_contact_date' => 'nullable|date',
            'under_company_registration' => 'nullable|boolean',

            // Personal Details
            'customer_name' => 'required|string|max:255',
            'id_type' => 'nullable|string|max:50',
            'id_number' => 'nullable|string|max:50',
            'tin' => 'nullable|string|max:50',
            'dob' => 'nullable|date',
            'owner_phone' => 'required|string|max:20',
            'home_no' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',

            // Other Contact
            'contact_person_name' => 'nullable|string|max:255',
            'contact_person_phone' => 'nullable|string|max:20',
            'office_no' => 'nullable|string|max:20',
            'fax_no' => 'nullable|string|max:20',

            // Home Address
            'primary_address' => 'required|string|max:255',
            'primary_postcode' => 'required|string|max:10',
            'primary_city' => 'required|string|max:100',
            'primary_state' => 'required|string|max:100',
            'primary_country' => 'required|string|max:100',

            // Postal Address
            'postal_address' => 'nullable|string|max:255',
            'postal_postcode' => 'nullable|string|max:10',
            'postal_city' => 'nullable|string|max:100',
            'postal_state' => 'nullable|string|max:100',
            'postal_country' => 'nullable|string|max:100',

            // Other Info
            'zone' => 'nullable|string|max:100',
            'religion' => 'nullable|string|max:100',
            'marital_status' => 'nullable|string|max:50',
            'is_corporate' => 'boolean',
            'lifestyle_interest' => 'nullable|string'
        ]);

        // Update the model with the validated data
        $people->update($validatedData);

        // Return a success response with the updated data
        return response()->json([
            'message' => 'People updated successfully',
            'data' => $people
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
