<?php

namespace Modules\HRMS\Http\Controllers\Roster;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Modules\HRMS\Models\HRMSRosterGroup;
use Modules\HRMS\Models\HRMSHoliday;
use Modules\HRMS\Models\HRMSOffday;
use Modules\HRMS\Models\HRMSRosterDayAssignment;
use Modules\HRMS\Models\HRMSStaffRosterGroupAssignment;

class HRMSRosterGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = HRMSRosterGroup::query();

            // Add search functionality if 'search' parameter is present
            if ($request->has('search') && !empty($request->search)) {
                $searchTerm = '%' . $request->search . '%';
                $query->where('name', 'like', $searchTerm);
            }

            // Add filtering by 'is_active'
            if ($request->has('is_active') && in_array($request->is_active, ['0', '1'])) {
                $query->where('is_active', (bool)$request->is_active);
            }

            // Add pagination
            $perPage = $request->input('per_page', 10); // Default to 10 items per page
            $rosterGroups = $query->paginate($perPage);

            return response()->json([
                'status' => 'success',
                'message' => 'Roster groups retrieved successfully.',
                'data' => $rosterGroups
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve roster groups: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:hrms_roster_group,name',
                'is_active' => 'boolean',
            ]);

            $rosterGroup = HRMSRosterGroup::create([
                'name' => $request->name,
                'is_active' => $request->input('is_active', true), // Default to true if not provided
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Roster group created successfully.',
                'data' => $rosterGroup
            ], 201); // 201 Created
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422); // 422 Unprocessable Entity
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create roster group: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $rosterGroup = HRMSRosterGroup::find($id);

            if (!$rosterGroup) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Roster group not found.'
                ], 404); // 404 Not Found
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Roster group retrieved successfully.',
                'data' => $rosterGroup
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve roster group: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $rosterGroup = HRMSRosterGroup::find($id);

            if (!$rosterGroup) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Roster group not found.'
                ], 404); // 404 Not Found
            }

            $request->validate([
                'name' => 'sometimes|required|string|max:255|unique:hrms_roster_group,name,' . $id,
                'is_active' => 'sometimes|boolean',
            ]);

            $rosterGroup->update($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Roster group updated successfully.',
                'data' => $rosterGroup
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422); // 422 Unprocessable Entity
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update roster group: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $rosterGroup = HRMSRosterGroup::find($id);

            if (!$rosterGroup) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Roster group not found.'
                ], 404); // 404 Not Found
            }

            $rosterGroup->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Roster group deleted successfully.'
            ], 200); // 200 OK
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete roster group: ' . $e->getMessage()
            ], 500);
        }
    }
}
