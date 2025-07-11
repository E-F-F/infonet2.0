<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bank;
use Illuminate\Http\JsonResponse;

class BankController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Bank::all());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:bank,code',
            'is_active' => 'boolean',
        ]);

        $bank = Bank::create($data);

        return response()->json($bank, 201);
    }

    public function show($id): JsonResponse
    {
        $bank = Bank::findOrFail($id);
        return response()->json($bank);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $bank = Bank::findOrFail($id);

        $data = $request->validate([
            'name' => 'sometimes|required|string|max:100',
            'code' => 'sometimes|required|string|max:20|unique:bank,code,' . $id,
            'is_active' => 'boolean',
        ]);

        $bank->update($data);

        return response()->json($bank);
    }

    public function destroy($id): JsonResponse
    {
        $bank = Bank::findOrFail($id);
        $bank->delete();

        return response()->json(['message' => 'Deleted successfully.']);
    }
}
