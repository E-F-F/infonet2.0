<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BranchController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Branch::with('company')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'branch_of' => 'required|exists:company,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:branch,code',
            'address' => 'nullable|string',
            'print_name' => 'nullable|string',
            'company_reg_no' => 'nullable|string',
            'description' => 'nullable|string',
            'work_minutes_per_day' => 'nullable|integer',
            'epf_employer_no' => 'nullable|string',
            'contact_person_name' => 'nullable|string',
            'contact_phone_no' => 'nullable|string',
            'socso_employer_no' => 'nullable|string',
            'lhdn_employer_no' => 'nullable|string',
            'hrdp_no' => 'nullable|string',
            'bank_account_no' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $branch = Branch::create($data);

        return response()->json($branch, 201);
    }

    public function show($id): JsonResponse
    {
        $branch = Branch::with('company')->findOrFail($id);
        return response()->json($branch);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $branch = Branch::findOrFail($id);

        $data = $request->validate([
            'branch_of' => 'sometimes|required|exists:company,id',
            'name' => 'sometimes|required|string|max:255',
            'code' => 'sometimes|required|string|max:50|unique:branch,code,' . $id,
            'address' => 'nullable|string',
            'print_name' => 'nullable|string',
            'company_reg_no' => 'nullable|string',
            'description' => 'nullable|string',
            'work_minutes_per_day' => 'nullable|integer',
            'epf_employer_no' => 'nullable|string',
            'contact_person_name' => 'nullable|string',
            'contact_phone_no' => 'nullable|string',
            'socso_employer_no' => 'nullable|string',
            'lhdn_employer_no' => 'nullable|string',
            'hrdp_no' => 'nullable|string',
            'bank_account_no' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $branch->update($data);

        return response()->json($branch);
    }

    public function destroy($id): JsonResponse
    {
        $branch = Branch::findOrFail($id);
        $branch->delete();

        return response()->json(['message' => 'Branch soft deleted.']);
    }

    public function trashed(): JsonResponse
    {
        $trashed = Branch::onlyTrashed()->get();
        return response()->json($trashed);
    }

    public function restore($id): JsonResponse
    {
        $branch = Branch::onlyTrashed()->findOrFail($id);
        $branch->restore();

        return response()->json(['message' => 'Branch restored.']);
    }
}
