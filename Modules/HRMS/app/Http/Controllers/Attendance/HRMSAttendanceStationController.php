<?php

namespace Modules\HRMS\Http\Controllers\AttendanceStation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\HRMS\Models\HRMSAttendanceStation;

/**
 * HRMSAttendanceStationController
 *
 * This API controller manages CRUD operations for HRMSAttendanceStation records.
 * It handles validation, creation, retrieval, updating, and deletion of attendance station records.
 */
class HRMSAttendanceStationController extends Controller
{
    /**
     * Display a listing of the attendance stations.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $stations = HRMSAttendanceStation::with('branch')->get();
        return response()->json($stations);
    }

    /**
     * Display the specified attendance station.
     *
     * @param  int  $id The ID of the attendance station.
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $station = HRMSAttendanceStation::with('branch')->findOrFail($id);
        return response()->json($station);
    }

    /**
     * Store a newly created attendance station in storage.
     *
     * @param  \Illuminate\Http\Request  $request The incoming request.
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:hrms_attendance_station,name|max:255',
            'code' => 'nullable|string|unique:hrms_attendance_station,code|max:50',
            'branch_id' => 'required|integer|exists:branch,id',
            'serial_number' => 'nullable|string|max:255',
            'hashed_password' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sensor_id' => 'nullable|string|max:255',
            'auto_inout' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $station = HRMSAttendanceStation::create([
                'name' => $request->name,
                'code' => $request->code,
                'branch_id' => $request->branch_id,
                'serial_number' => $request->serial_number,
                'hashed_password' => $request->hashed_password,
                'is_active' => $request->has('is_active') ? $request->is_active : true,
                'sensor_id' => $request->sensor_id,
                'auto_inout' => $request->has('auto_inout') ? $request->auto_inout : true,
            ]);

            $station->load('branch');

            return response()->json([
                'success' => true,
                'message' => 'Attendance station created successfully!',
                'data' => $station
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create attendance station.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified attendance station in storage.
     *
     * @param  \Illuminate\Http\Request  $request The incoming request.
     * @param  int  $id The ID of the attendance station to update.
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $station = HRMSAttendanceStation::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|unique:hrms_attendance_station,name,' . $id . '|max:255',
            'code' => 'sometimes|nullable|string|unique:hrms_attendance_station,code,' . $id . '|max:50',
            'branch_id' => 'sometimes|required|integer|exists:branch,id',
            'serial_number' => 'sometimes|nullable|string|max:255',
            'hashed_password' => 'sometimes|nullable|string|max:255',
            'is_active' => 'sometimes|boolean',
            'sensor_id' => 'sometimes|nullable|string|max:255',
            'auto_inout' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $updateData = $request->only([
                'name',
                'code',
                'branch_id',
                'serial_number',
                'hashed_password',
                'sensor_id',
            ]);

            if ($request->has('is_active')) {
                $updateData['is_active'] = $request->is_active;
            }
            if ($request->has('auto_inout')) {
                $updateData['auto_inout'] = $request->auto_inout;
            }

            $station->update($updateData);

            $station->load('branch');

            return response()->json([
                'success' => true,
                'message' => 'Attendance station updated successfully!',
                'data' => $station
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update attendance station.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified attendance station from storage.
     *
     * @param  int  $id The ID of the attendance station to delete.
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $station = HRMSAttendanceStation::findOrFail($id);

        try {
            $station->delete();

            return response()->json([
                'success' => true,
                'message' => 'Attendance station deleted successfully (soft deleted).'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete attendance station.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
