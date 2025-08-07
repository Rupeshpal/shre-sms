<?php

namespace App\Http\Controllers\Api\V1\Assignment;

use App\Http\Controllers\Controller;
use App\Models\Assignment\Assignment;
use App\Http\Resources\Assignment\AssignmentResource;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AssignmentController extends Controller
{
    public function index()
    {
        try {
            $assignments = Assignment::with(['teacher', 'subject', 'section', 'class'])->get();
            return AssignmentResource::collection($assignments);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to load assignments',
                'error' => $e->getMessage()
            ], status: 500);
        }
    }

    public function show($id)
    {
        try {
            $assignment = Assignment::with(['teacher', 'subject', 'section', 'class'])->find($id);

            if (! $assignment) {
                return response()->json(['message' => 'Assignment not found'], 404);
            }

            return new AssignmentResource($assignment);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to load assignment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

 public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'teacherId'   => 'required|exists:teachers,id',
            'subjectId'   => 'required|exists:subjects,id',
            'sectionId'   => 'required|exists:sections,id',
            'classId'     => 'required|exists:classes,id',
            'dueDate'     => 'required|date',
            'attachment'  => 'nullable|string|max:255',
        ]);

        $data = [
            'title'       => $validated['title'],
            'description' => $validated['description'],
            'teacher_id'  => $validated['teacherId'],
            'subject_id'  => $validated['subjectId'],
            'section_id'  => $validated['sectionId'],
            'class_id'    => $validated['classId'],
            'due_date'    => $validated['dueDate'],
            'attachment'  => $validated['attachment'] ?? null,
        ];

        $assignment = Assignment::create($data);
        $assignment->load(['teacher', 'subject', 'section', 'class']);

        return response()->json([
            'message' => 'Assignment created successfully',
            'data'    => new AssignmentResource($assignment)
        ], 201);
    } catch (ValidationException $e) {
        return response()->json([
            'message' => 'Validation failed',
            'errors'  => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Unexpected error',
            'error'   => $e->getMessage()
        ], 500);
    }
}


    public function update(Request $request, $id)
    {
        try {
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
            $assignment->load(['teacher', 'subject', 'section', 'class']);

            return response()->json([
                'message' => 'Assignment updated successfully',
                'data'    => new AssignmentResource($assignment)
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Unexpected error',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $assignment = Assignment::find($id);
            if (! $assignment) {
                return response()->json(['message' => 'Assignment not found'], 404);
            }

            $assignment->delete();

            return response()->json(['message' => 'Assignment deleted successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Unexpected error',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
