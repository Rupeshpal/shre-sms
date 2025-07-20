<?php

namespace App\Http\Controllers\Api\V1\Student;

use App\Http\Controllers\Controller;
use App\Models\Student\StudentGuardianInfo;
use Illuminate\Http\Request;

class StudentGuardianInfoController extends Controller
{
    public function index()
    {
        return response()->json(StudentGuardianInfo::all());
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
        ]);

        $guardian = StudentGuardianInfo::create($validated);

        return response()->json([
            'message' => 'Guardian info created successfully',
            'data'    => $guardian
        ], 201);
    }

    public function show($id)
    {
        $guardian = StudentGuardianInfo::find($id);
        if (! $guardian) {
            return response()->json(['message' => 'Guardian info not found'], 404);
        }
        return response()->json($guardian);
    }

    public function update(Request $request, $id)
    {
        $guardian = StudentGuardianInfo::find($id);
        if (! $guardian) {
            return response()->json(['message' => 'Guardian info not found'], 404);
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
        ]);

        $guardian->update($validated);

        return response()->json([
            'message' => 'Guardian info updated successfully',
            'data'    => $guardian
        ]);
    }

    public function destroy($id)
    {
        $guardian = StudentGuardianInfo::find($id);
        if (! $guardian) {
            return response()->json(['message' => 'Guardian info not found'], 404);
        }

        $guardian->delete();

        return response()->json(['message' => 'Guardian info deleted successfully']);
    }
}
