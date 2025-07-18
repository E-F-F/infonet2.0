<?php

namespace Modules\HRMS\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\HRMS\Models\HRMSDesignation; // Ensure this matches your model's actual namespace
use Illuminate\Validation\Rule;
use Modules\HRMS\Transformers\HRMSDesignationResource; // Ensure this matches your resource's actual namespace

class HRMSDesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = HRMSDesignation::with(['department', 'parentDesignation', 'leaveRank']);

        if ($search = $request->get('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $designations = $query->get();

        return HRMSDesignationResource::collection($designations);
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
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:hrms_designation,name',
            'hrms_department_id' => 'nullable|exists:hrms_department,id',
            'parent_designation_id' => 'nullable|exists:hrms_designation,id',
            'hrms_leave_rank_id' => 'nullable|exists:hrms_leave_rank,id',
            'is_active' => 'sometimes|boolean',
        ]);

        try {
            $designation = HRMSDesignation::create([
                'name' => $validated['name'],
                'hrms_department_id' => $validated['hrms_department_id'] ?? null,
                'parent_designation_id' => $validated['parent_designation_id'] ?? null,
                'hrms_leave_rank_id' => $validated['hrms_leave_rank_id'] ?? null,
                'is_active' => $request->has('is_active') ? (bool) $validated['is_active'] : true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Designation created successfully.',
                'data' => $designation->load(['department', 'parentDesignation', 'leaveRank']),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating designation: ' . $e->getMessage(),
            ], 500);
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
