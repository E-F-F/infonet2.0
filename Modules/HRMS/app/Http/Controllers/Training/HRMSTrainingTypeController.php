<?php

namespace Modules\HRMS\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\HRMS\Models\HRMSTrainingType;

class HRMSTrainingTypeController extends Controller
{
    public function index()
    {
        $trainingTypes = HRMSTrainingType::all();
        return view('hrms::training_management.training_types.index', compact('trainingTypes'));
    }

    public function show($id)
    {
        $trainingTypes = HRMSTrainingType::findOrFail($id);
        return response()->json($trainingTypes);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:hrms_training_type,name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Name has been taken',
            ], 422);
        }

        $trainingTypes = HRMSTrainingType::create([
            'name' => $request->name,
            'is_active' => $request->has('is_active'), // Check if checkbox was sent
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Training type added successfully!',
            'trainingType' => $trainingTypes 
        ]);
    }

    public function update(Request $request, $id)
    {
        $trainingTypes = HRMSTrainingType::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:hrms_training_type,name,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Name has been taken',
            ], 422);
        }

        $trainingTypes->update([
            'name' => $request->name,
        ]);

        return response()->json(['success' => true, 'trainingType' => $trainingTypes]);
    }

    public function destroy($id)
    {
        $trainingTypes = HRMSTrainingType::findOrFail($id);
        $trainingTypes->delete();

        return response()->json(['success' => true, 'message' => 'Training type deleted successfully']);
    }
}
