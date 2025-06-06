<?php

namespace Modules\HRMS\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\HRMS\Models\HRMSDesignation; // Ensure this matches your model's actual namespace
use Illuminate\Validation\Rule;

class HRMSDesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = HRMSDesignation::query();

        if ($search = $request->get('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $designations = $query->get();
        return view('hrms::designation.index', compact('designations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('hrms::designation.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'name' => 'required|string|max:255|unique:hrms_designation,name',
            // 'is_active' => 'boolean', // is_active is optional, defaults to true in migration
        ]);

        try {
            HRMSDesignation::create([
                'name' => $request->input('name'),
                // 'is_active' => $request->has('is_active'), // Check if checkbox is ticked
            ]);

            return redirect()->route('hrms.designation.index')
                ->with('success', 'Designation created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating designation: ' . $e->getMessage());
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('hrms::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HRMSDesignation $designation)
    {
        return view('hrms::designation.edit', compact('designation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HRMSDesignation $designation)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('hrms_designation', 'name')->ignore($designation->id),
            ],
            'is_active' => 'boolean',
        ]);

        try {
            $designation->update([
                'name' => $request->input('name'),
                'is_active' => $request->has('is_active'),
            ]);

            // Redirect with a success message
            return redirect()->route('hrms.designation.index') // Adjust route name if using modules
                ->with('success', 'Designation updated successfully.');
        } catch (\Exception $e) {
            // Handle any errors during update
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating leave designation: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HRMSDesignation $designation)
    {
        try {
            // Soft delete the leave rank
            $designation->delete();

            // Redirect with a success message
            return redirect()->route('hrms.designation.index') // Adjust route name if using modules
                ->with('success', 'Designation deleted successfully.');
        } catch (\Exception $e) {
            // Handle any errors during deletion
            return redirect()->back()
                ->with('error', 'Error deleting leave designation: ' . $e->getMessage());
        }
    }
}
