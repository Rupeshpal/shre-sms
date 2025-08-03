<?php

namespace App\Http\Controllers\Api\V1\Student;

use App\Http\Controllers\Controller;
use App\Models\Student\StudentRelation;
use Illuminate\Http\Request;

class StudentRelationController extends Controller
{
    public function index()
    {
        return response()->json(StudentRelation::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:student_personal_info,id',
            'relation' => 'required|in:Father,Mother,Guardian',
            'name' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:100',
            'phone_number' => 'nullable|string|max:20',
            'occupation' => 'nullable|string|max:100',
            'temporary_address' => 'nullable|string|max:255',
            'permanent_address' => 'nullable|string|max:255',
            'nationality' => 'nullable|string|max:50',
            'monthly_income' => 'nullable|numeric',
            'document' => 'nullable|file|mimes:jpg,png,pdf|max:2048',
        ]);

        if ($request->hasFile('document')) {
            $validated['document'] = $request->file('document')->store('student_relation_docs', 'public');
        }

        $relation = StudentRelation::create($validated);

        return response()->json(['message' => 'Saved successfully', 'data' => $relation], 201);
    }

    public function show($id)
    {
        $relation = StudentRelation::find($id);
        if (! $relation) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json($relation);
    }

    public function update(Request $request, $id)
    {
        $relation = StudentRelation::find($id);
        if (! $relation) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $validated = $request->validate([
            'student_id' => 'sometimes|required|exists:student_personal_info,id',
            'relation' => 'sometimes|required|in:Father,Mother,Guardian',
            'name' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:100',
            'phone_number' => 'nullable|string|max:20',
            'occupation' => 'nullable|string|max:100',
            'temporary_address' => 'nullable|string|max:255',
            'permanent_address' => 'nullable|string|max:255',
            'nationality' => 'nullable|string|max:50',
            'monthly_income' => 'nullable|numeric',
            'document' => 'nullable|file|mimes:jpg,png,pdf|max:2048',
        ]);

        if ($request->hasFile('document')) {
            $validated['document'] = $request->file('document')->store('student_relation_docs', 'public');
        }

        $relation->update($validated);

        return response()->json(['message' => 'Updated successfully', 'data' => $relation]);
    }

    public function destroy($id)
    {
        $relation = StudentRelation::find($id);
        if (! $relation) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $relation->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
