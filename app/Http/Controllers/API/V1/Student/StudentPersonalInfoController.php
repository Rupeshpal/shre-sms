<?php
namespace App\Http\Controllers\Api\V1\Student;
use App\Http\Controllers\Controller;
use App\Models\Student\StudentPersonalInfo;
use Illuminate\Http\Request;

class StudentPersonalInfoController extends Controller
{
    public function index()
    {
        return response()->json(StudentPersonalInfo::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'academic_year'     => 'nullable|string|max:20',
            'admission_number'  => 'nullable|string|max:50',
            'admission_date'    => 'nullable|date',
            'roll_no'           => 'nullable|string|max:20',
            'status'            => 'nullable|boolean',
            'first_name'        => 'required|string|max:100',
            'last_name'         => 'nullable|string|max:100',
            'class'             => 'nullable|string|max:50',
            'section'           => 'nullable|string|max:50',
            'gender'            => 'nullable|string|max:40',
            'date_of_birth'     => 'nullable|date',
            'blood_group'       => 'nullable|string|max:5',
            'house'             => 'nullable|string|max:50',
            'mother_tongue'     => 'nullable|string|max:50',
            'contact_number'    => 'nullable|string|max:20',
            'email'             => 'nullable|string|email|max:100',
        ]);

        $student = StudentPersonalInfo::create($validated);

        return response()->json([
            'message' => 'Student personal info created successfully',
            'data'    => $student
        ], 201);
    }

    public function show($id)
    {
        $student = StudentPersonalInfo::find($id);
        if (! $student) {
            return response()->json(['message' => 'Student not found'], 404);
        }
        return response()->json($student);
    }

    public function update(Request $request, $id)
    {
        $student = StudentPersonalInfo::find($id);
        if (! $student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        $validated = $request->validate([
            'academic_year'     => 'nullable|string|max:20',
            'admission_number'  => 'nullable|string|max:50',
            'admission_date'    => 'nullable|date',
            'roll_no'           => 'nullable|string|max:20',
            'status'            => 'nullable|boolean',
            'first_name'        => 'sometimes|required|string|max:100',
            'last_name'         => 'nullable|string|max:100',
            'class'             => 'nullable|string|max:50',
            'section'           => 'nullable|string|max:50',
            'gender'            => 'nullable|string|max:40',
            'date_of_birth'     => 'nullable|date',
            'blood_group'       => 'nullable|string|max:5',
            'house'             => 'nullable|string|max:50',
            'mother_tongue'     => 'nullable|string|max:50',
            'contact_number'    => 'nullable|string|max:20',
            'email'             => 'nullable|string|email|max:100',
        ]);

        $student->update($validated);

        return response()->json([
            'message' => 'Student personal info updated successfully',
            'data'    => $student
        ]);
    }

    public function destroy($id)
    {
        $student = StudentPersonalInfo::find($id);
        if (! $student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        $student->delete();

        return response()->json(['message' => 'Student personal info deleted successfully']);
    }
}