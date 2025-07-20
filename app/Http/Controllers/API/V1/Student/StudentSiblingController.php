<?php
namespace App\Http\Controllers\Api\V1\Student;
use App\Http\Controllers\Controller;
use App\Models\Student\StudentSibling;
use Illuminate\Http\Request;

class StudentSiblingController extends Controller
{
    public function index()
    {
        return response()->json(StudentSibling::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id'   => 'required|exists:student_personal_info,id',
            'name'         => 'nullable|string|max:100',
            'admission_no' => 'nullable|string|max:50',
            'section'      => 'nullable|string|max:50',
            'roll_no'      => 'nullable|string|max:20',
        ]);

        $sibling = StudentSibling::create($validated);

        return response()->json([
            'message' => 'Sibling created successfully',
            'data'    => $sibling
        ], 201);
    }

    public function show($id)
    {
        $sibling = StudentSibling::find($id);
        if (! $sibling) {
            return response()->json(['message' => 'Sibling not found'], 404);
        }
        return response()->json($sibling);
    }

    public function update(Request $request, $id)
    {
        $sibling = StudentSibling::find($id);
        if (! $sibling) {
            return response()->json(['message' => 'Sibling not found'], 404);
        }

        $validated = $request->validate([
            'student_id'   => 'sometimes|required|exists:student_personal_info,id',
            'name'         => 'nullable|string|max:100',
            'admission_no' => 'nullable|string|max:50',
            'section'      => 'nullable|string|max:50',
            'roll_no'      => 'nullable|string|max:20',
        ]);

        $sibling->update($validated);

        return response()->json([
            'message' => 'Sibling updated successfully',
            'data'    => $sibling
        ]);
    }

    public function destroy($id)
    {
        $sibling = StudentSibling::find($id);
        if (! $sibling) {
            return response()->json(['message' => 'Sibling not found'], 404);
        }

        $sibling->delete();

        return response()->json(['message' => 'Sibling deleted successfully']);
    }
}
