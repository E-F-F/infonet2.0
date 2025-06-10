<?php

namespace Modules\HRMS\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\HRMS\Models\HRMSTrainingAwardType;

class HRMSTrainingAwardTypeController extends Controller
{
    public function index()
    {
        $trainingAwardTypes = HRMSTrainingAwardType::all();
        return view('hrms::training_management.training_award_types.index', compact('trainingAwardTypes'));
    }

    public function show($id)
    {
        $trainingAwardTypes = HRMSTrainingAwardType::findOrFail($id);
        return response()->json($trainingAwardTypes);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:hrms_training_award_type,name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Name has been taken',
            ], 422);
        }

        $trainingAwardTypes = HRMSTrainingAwardType::create([
            'name' => $request->name,
            'is_active' => $request->has('is_active'), // Check if checkbox was sent
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Training award type added successfully!',
            'trainingAwardType' => $trainingAwardTypes
        ]);
    }

    public function update(Request $request, $id)
    {
        $trainingAwardTypes = HRMSTrainingAwardType::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:hrms_training_award_type,name,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Name has been taken',
            ], 422);
        }

        $trainingAwardTypes->update([
            'name' => $request->name,
        ]);

        return response()->json(['success' => true, 'trainingAwardType' => $trainingAwardTypes]);
    }

    public function destroy($id)
    {
        $trainingAwardTypes = HRMSTrainingAwardType::findOrFail($id);
        $trainingAwardTypes->delete();

        return response()->json(['success' => true, 'message' => 'Training type deleted successfully']);
    }
}
