<?php

namespace App\Http\Controllers\Api\V1\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Teacher\TeacherBankDetail;
use Illuminate\Http\Request;

class TeacherBankDetailController extends Controller
{
    public function index()
    {
        return response()->json(TeacherBankDetail::all());
    }

    public function show($id)
    {
        $bankDetail = TeacherBankDetail::find($id);
        if (! $bankDetail) {
            return response()->json(['message' => 'Bank detail not found'], 404);
        }
        return response()->json($bankDetail);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'teacher_id'      => 'required|exists:teachers,id',
            'account_name'    => 'nullable|string|max:100',
            'account_number'  => 'nullable|string|max:50',
            'bank_name'       => 'nullable|string|max:100',
            'branch_name'     => 'nullable|string|max:100',
            'pan_number'      => 'nullable|string|max:50',
            'basic_salary'    => 'nullable|string|max:50',
            'contract_type'   => 'nullable|string|max:50',
            'work_location'   => 'nullable|string|max:100',
            'work_shift'      => 'nullable|string|max:100',
            'date_of_leaving' => 'nullable|date',
            'qualification'   => 'nullable|string|max:150',
            'work_experience' => 'nullable|string|max:50',
        ]);

        $bankDetail = TeacherBankDetail::create($validated);

        return response()->json([
            'message' => 'Bank detail created successfully',
            'data'    => $bankDetail
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $bankDetail = TeacherBankDetail::find($id);
        if (! $bankDetail) {
            return response()->json(['message' => 'Bank detail not found'], 404);
        }

        $validated = $request->validate([
            'teacher_id'      => 'sometimes|required|exists:teachers,id',
            'account_name'    => 'nullable|string|max:100',
            'account_number'  => 'nullable|string|max:50',
            'bank_name'       => 'nullable|string|max:100',
            'branch_name'     => 'nullable|string|max:100',
            'pan_number'      => 'nullable|string|max:50',
            'basic_salary'    => 'nullable|string|max:50',
            'contract_type'   => 'nullable|string|max:50',
            'work_location'   => 'nullable|string|max:100',
            'work_shift'      => 'nullable|string|max:100',
            'date_of_leaving' => 'nullable|date',
            'qualification'   => 'nullable|string|max:150',
            'work_experience' => 'nullable|string|max:50',
        ]);

        $bankDetail->update($validated);

        return response()->json([
            'message' => 'Bank detail updated successfully',
            'data'    => $bankDetail
        ]);
    }

    public function destroy($id)
    {
        $bankDetail = TeacherBankDetail::find($id);
        if (! $bankDetail) {
            return response()->json(['message' => 'Bank detail not found'], 404);
        }

        $bankDetail->delete();

        return response()->json(['message' => 'Bank detail deleted successfully']);
    }
}
