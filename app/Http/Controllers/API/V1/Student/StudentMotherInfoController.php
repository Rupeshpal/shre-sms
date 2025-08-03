<?php

namespace App\Http\Controllers\Api\V1\Student;
use App\Http\Controllers\Controller;
use App\Models\Student\StudentMotherInfo;
use Illuminate\Http\Request;

class StudentMotherInfoController extends Controller
{
    public function index()
    {
        return response()->json(StudentMotherInfo::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id'        => 'required|exists:student_personal_info,id',
            'name'              => 'nullable|string|max:100',
            'email'             => 'nullable|string|email|max:100',
            'phone_number'      => 'nullable|string|max:20',
            'occupation'        => 'nullable|string|max:100',
            'temporary_address' => 'nullable|string|max:255',
            'permanent_address' => 'nullable|string|max:255',
            'nationality'       => 'nullable|string|max:50',
            'monthly_income'    => 'nullable|numeric',
        ]);

        $mother = StudentMotherInfo::create($validated);

        return response()->json([
            'message' => 'Mother info created successfully',
            'data'    => $mother
        ], 201);
    }

    public function show($id)
    {
        $mother = StudentMotherInfo::find($id);
        if (! $mother) {
            return response()->json(['message' => 'Mother info not found'], 404);
        }
        return response()->json($mother);
    }

    public function update(Request $request, $id)
    {
        $mother = StudentMotherInfo::find($id);
        if (! $mother) {
            return response()->json(['message' => 'Mother info not found'], 404);
        }

        $validated = $request->validate([
            'student_id'        => 'sometimes|required|exists:student_personal_info,id',
            'name'              => 'nullable|string|max:100',
            'email'             => 'nullable|string|email|max:100',
            'phone_number'      => 'nullable|string|max:20',
            'occupation'        => 'nullable|string|max:100',
            'temporary_address' => 'nullable|string|max:255',
            'permanent_address' => 'nullable|string|max:255',
            'nationality'       => 'nullable|string|max:50',
            'monthly_income'    => 'nullable|numeric',
        ]);

        $mother->update($validated);

        return response()->json([
            'message' => 'Mother info updated successfully',
            'data'    => $mother
        ]);
    }

    public function destroy($id)
    {
        $mother = StudentMotherInfo::find($id);
        if (! $mother) {
            return response()->json(['message' => 'Mother info not found'], 404);
        }

        $mother->delete();

        return response()->json(['message' => 'Mother info deleted successfully']);
    }
}
