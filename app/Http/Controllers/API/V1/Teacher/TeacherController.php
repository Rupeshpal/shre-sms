<?php

namespace App\Http\Controllers\Api\V1\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Teacher\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index()
    {
        return response()->json(Teacher::all());
    }

    public function show($id)
    {
        $teacher = Teacher::find($id);
        if (! $teacher) {
            return response()->json(['message' => 'Teacher not found'], 404);
        }
        return response()->json($teacher);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'teacher_code'      => 'nullable|string|max:20',
            'first_name'        => 'required|string|max:100',
            'last_name'         => 'required|string|max:100',
            'email'             => 'nullable|string|email|max:150',
            'primary_contact'   => 'nullable|string|max:20',
            'gender'            => 'nullable|in:Male,Female,Other',
            'blood_group'       => 'nullable|string|max:5',
            'date_of_birth'     => 'nullable|date',
            'date_of_joining'   => 'nullable|date',
            'marital_status'    => 'nullable|in:Single,Married,Other',
            'qualification'     => 'nullable|string|max:150',
            'work_experience'   => 'nullable|string|max:50',
            'father_name'       => 'nullable|string|max:100',
            'mother_name'       => 'nullable|string|max:100',
            'house'             => 'nullable|string|max:50',
            'mother_tongue'     => 'nullable|string|max:50',
            'status'            => 'nullable|boolean',
        ]);

        $teacher = Teacher::create($validated);

        return response()->json(
            [
                'message' => 'Teacher created successfully',
                'teacher' => $teacher,
            ] , 201
        );
    }

    public function update(Request $request, $id)
    {
        $teacher = Teacher::find($id);
        if (! $teacher) {
            return response()->json(['message' => 'Teacher not found'], 404);
        }

        $validated = $request->validate([
            'teacher_code'      => 'nullable|string|max:20',
            'first_name'        => 'sometimes|required|string|max:100',
            'last_name'         => 'sometimes|required|string|max:100',
            'email'             => 'nullable|string|email|max:150',
            'primary_contact'   => 'nullable|string|max:20',
            'gender'            => 'nullable|in:Male,Female,Other',
            'blood_group'       => 'nullable|string|max:5',
            'date_of_birth'     => 'nullable|date',
            'date_of_joining'   => 'nullable|date',
            'marital_status'    => 'nullable|in:Single,Married,Other',
            'qualification'     => 'nullable|string|max:150',
            'work_experience'   => 'nullable|string|max:50',
            'father_name'       => 'nullable|string|max:100',
            'mother_name'       => 'nullable|string|max:100',
            'house'             => 'nullable|string|max:50',
            'mother_tongue'     => 'nullable|string|max:50',
            'status'            => 'nullable|boolean',
        ]);

        $teacher->update($validated);

        return response()->json($teacher);
    }

    public function destroy($id)
    {
        $teacher = Teacher::find($id);
        if (! $teacher) {
            return response()->json(['message' => 'Teacher not found'], 404);
        }

        $teacher->delete();

        return response()->json(['message' => 'Teacher deleted successfully']);
    }
}
