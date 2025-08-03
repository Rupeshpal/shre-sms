<?php

namespace App\Http\Controllers\Api\V1\Assignment;

use App\Http\Controllers\Controller;
use App\Models\Assignment\Assignment;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AssignmentController extends Controller
{
    public function index()
    {
        try {
            return response()->json(Assignment::all());
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to load assignments',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $assignment = Assignment::find($id);
            if (! $assignment) {
                return response()->json(['message' => 'Assignment not found'], 404);
            }
            return response()->json($assignment);
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

            return response()->json([
                'message' => 'Assignment created successfully',
                'data' => $assignment
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

            return response()->json([
                'message' => 'Assignment updated successfully',
                'data' => $assignment
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