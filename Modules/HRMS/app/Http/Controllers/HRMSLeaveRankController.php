<?php

namespace Modules\HRMS\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\HRMS\Models\HRMSLeaveRank; // Ensure this matches your model's actual namespace
use Illuminate\Validation\Rule;

class HRMSLeaveRankController extends Controller
{
    /**
     * Display a listing of the HRMSLeaveRank.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = HRMSLeaveRank::query();

        if ($search = $request->get('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $leaveRanks = $query->get();
        return view('hrms::leave_ranks.index', compact('leaveRanks')); // Adjust view path if using modules (e.g., hrms::leave_ranks.index)
    }

    /**
     * Show the form for creating a new HRMSLeaveRank.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('hrms::leave_ranks.create'); // Adjust view path if using modules (e.g., hrms::leave_ranks.create)
    }

    /**
     * Store a newly created HRMSLeaveRank in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'name' => 'required|string|max:255|unique:hrms_leave_rank,name',
            // 'is_active' => 'boolean', // is_active is optional, defaults to true in migration
        ]);

        try {
            // Create a new leave rank
            HRMSLeaveRank::create([
                'name' => $request->input('name'),
                // 'is_active' => $request->has('is_active'), // Check if checkbox is ticked
            ]);

            // Redirect with a success message
            return redirect()->route('hrms.leave_ranks.index') // Adjust route name if using modules
                ->with('success', 'Leave rank created successfully.');
        } catch (\Exception $e) {
            // Handle any errors during creation
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating leave rank: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified HRMSLeaveRank.
     *
     * @param  \Modules\HRMS\Models\HRMSLeaveRank  $leaveRank
     * @return \Illuminate\View\View
     */
    public function edit(HRMSLeaveRank $leaveRank)
    {
        return view('hrms::leave_ranks.edit', compact('leaveRank')); // Adjust view path if using modules (e.g., hrms::leave_ranks.edit)
    }

    /**
     * Update the specified HRMSLeaveRank in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Modules\HRMS\Models\HRMSLeaveRank  $leaveRank
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, HRMSLeaveRank $leaveRank)
    {
        // Validate incoming request data, ignoring the current leave rank's name for uniqueness
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('hrms_leave_rank', 'name')->ignore($leaveRank->id),
            ],
            'is_active' => 'boolean',
        ]);

        try {
            // Update the leave rank
            $leaveRank->update([
                'name' => $request->input('name'),
                'is_active' => $request->has('is_active'), // Check if checkbox is ticked
            ]);

            // Redirect with a success message
            return redirect()->route('hrms.leave_ranks.index') // Adjust route name if using modules
                ->with('success', 'Leave rank updated successfully.');
        } catch (\Exception $e) {
            // Handle any errors during update
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating leave rank: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified HRMSLeaveRank from storage.
     *
     * @param  \Modules\HRMS\Models\HRMSLeaveRank  $leaveRank
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(HRMSLeaveRank $leaveRank)
    {
        try {
            // Soft delete the leave rank
            $leaveRank->delete();

            // Redirect with a success message
            return redirect()->route('hrms.leave_ranks.index') // Adjust route name if using modules
                ->with('success', 'Leave rank deleted successfully.');
        } catch (\Exception $e) {
            // Handle any errors during deletion
            return redirect()->back()
                ->with('error', 'Error deleting leave rank: ' . $e->getMessage());
        }
    }
}
