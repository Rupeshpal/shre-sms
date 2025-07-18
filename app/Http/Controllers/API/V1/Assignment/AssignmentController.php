<?php

namespace App\Http\Controllers\Api\V1\Assignment;

use App\Http\Controllers\Controller;
use App\Models\Assignment\Assignment;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index()
    {
        return response()->json(Assignment::all());
    }

    public function show($id)
    {
        $assignment = Assignment::find($id);
        if (! $assignment) {
            return response()->json(['message' => 'Assignment not found'], 404);
        }
        return response()->json($assignment);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'teacher_id'   => 'required|exists:teachers,id',
            'subject_id'   => 'required|exists:subjects,id',
            'section_id'   => 'required|exists:sections,id',
            'class_id'     => 'required|exists:classes,id',
            'due_date'     => 'required|date',
            'attachment'   => 'nullable|string|max:255',
        ]);

        $assignment = Assignment::create($validated);

        return response()->json($assignment, 201);
    }

    public function update(Request $request, $id)
    {
        $assignment = Assignment::find($id);
        if (! $assignment) {
            return response()->json(['message' => 'Assignment not found'], 404);
        }

        $validated = $request->validate([
            'title'        => 'sometimes|required|string|max:255',
            'description'  => 'sometimes|required|string',
            'teacher_id'   => 'sometimes|required|exists:teachers,id',
            'subject_id'   => 'sometimes|required|exists:subjects,id',
            'section_id'   => 'sometimes|required|exists:sections,id',
            'class_id'     => 'sometimes|required|exists:classes,id',
            'due_date'     => 'sometimes|required|date',
            'attachment'   => 'nullable|string|max:255',
        ]);

        $assignment->update($validated);

        return response()->json($assignment);
    }

    public function destroy($id)
    {
        $assignment = Assignment::find($id);
        if (! $assignment) {
            return response()->json(['message' => 'Assignment not found'], 404);
        }

        $assignment->delete();

        return response()->json(['message' => 'Assignment deleted successfully']);
    }
}
