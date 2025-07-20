<?php
namespace App\Http\Controllers\Api\V1\Student;
use App\Http\Controllers\Controller;
use App\Models\Student\StudentFatherInfo;
use Illuminate\Http\Request;

class StudentFatherInfoController extends Controller
{
    public function index()
    {
        return response()->json(StudentFatherInfo::all());
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

        $father = StudentFatherInfo::create($validated);

        return response()->json([
            'message' => 'Father info created successfully',
            'data'    => $father
        ], 201);
    }

    public function show($id)
    {
        $father = StudentFatherInfo::find($id);
        if (! $father) {
            return response()->json(['message' => 'Father info not found'], 404);
        }
        return response()->json($father);
    }

    public function update(Request $request, $id)
    {
        $father = StudentFatherInfo::find($id);
        if (! $father) {
            return response()->json(['message' => 'Father info not found'], 404);
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

        $father->update($validated);

        return response()->json([
            'message' => 'Father info updated successfully',
            'data'    => $father
        ]);
    }

    public function destroy($id)
    {
        $father = StudentFatherInfo::find($id);
        if (! $father) {
            return response()->json(['message' => 'Father info not found'], 404);
        }

        $father->delete();

        return response()->json(['message' => 'Father info deleted successfully']);
    }
}
