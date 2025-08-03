<?php

namespace App\Http\Controllers\Api\V1\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Teacher\TeacherRoutine;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Exception;

class TeacherRoutineController extends Controller
{
    public function index()
    {
        try {
            $routines = TeacherRoutine::all();
            return response()->json($routines, 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch routines',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $routine = TeacherRoutine::find($id);
            if (! $routine) {
                return response()->json(['message' => 'Routine not found'], 404);
            }
            return response()->json($routine, 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error fetching routine',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'teacher_id'   => 'required|exists:teachers,id',
                'section_id'   => 'required|exists:sections,id',
                'subject_id'   => 'required|exists:subjects,id',
                'class_id'     => 'required|exists:classes,id',
                'day_of_week'  => 'required|string|max:20',
                'start_time'   => 'nullable|date_format:H:i:s',
                'end_time'     => 'nullable|date_format:H:i:s',
                'room'         => 'nullable|string|max:50',
            ]);

            $routine = TeacherRoutine::create($validated);

            return response()->json([
                'message' => 'Routine created successfully',
                'routine' => $routine
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $e->errors()
            ], 422);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Database error',
                'error'   => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Unexpected error',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $routine = TeacherRoutine::find($id);
            if (! $routine) {
                return response()->json(['message' => 'Routine not found'], 404);
            }

            $validated = $request->validate([
                'teacher_id'   => 'sometimes|required|exists:teachers,id',
                'section_id'   => 'sometimes|required|exists:sections,id',
                'subject_id'   => 'sometimes|required|exists:subjects,id',
                'class_id'     => 'sometimes|required|exists:classes,id',
                'day_of_week'  => 'sometimes|required|string|max:20',
                'start_time'   => 'nullable|date_format:H:i:s',
                'end_time'     => 'nullable|date_format:H:i:s',
                'room'         => 'nullable|string|max:50',
            ]);

            $routine->update($validated);

            return response()->json([
                'message' => 'Routine updated successfully',
                'routine' => $routine
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $e->errors()
            ], 422);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Database error',
                'error'   => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Unexpected error',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $routine = TeacherRoutine::find($id);
            if (! $routine) {
                return response()->json(['message' => 'Routine not found'], 404);
            }

            $routine->delete();

            return response()->json(['message' => 'Routine deleted successfully'], 200);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Database error',
                'error'   => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Unexpected error',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
