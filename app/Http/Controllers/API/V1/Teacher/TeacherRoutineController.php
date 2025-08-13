<?php

namespace App\Http\Controllers\API\V1\Teacher;

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
            return response()->json([
                'data' => $routines->map(fn($routine) => $this->formatResponse($routine))
            ], 200);
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

            return response()->json([
                'data' => $this->formatResponse($routine)
            ], 200);
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
                'teacherId'   => 'required|exists:teachers,id',
                'sectionId'   => 'required|exists:sections,id',
                'subjectId'   => 'required|exists:subjects,id',
                'classId'     => 'required|exists:classes,id',
                'dayOfWeek'   => 'required|string|max:20',
                'startTime'   => 'nullable|date_format:H:i:s',
                'endTime'     => 'nullable|date_format:H:i:s',
                'room'        => 'nullable|string|max:50',
            ]);

            $routine = TeacherRoutine::create([
                'teacher_id'  => $validated['teacherId'],
                'section_id'  => $validated['sectionId'],
                'subject_id'  => $validated['subjectId'],
                'class_id'    => $validated['classId'],
                'day_of_week' => $validated['dayOfWeek'],
                'start_time'  => $validated['startTime'] ?? null,
                'end_time'    => $validated['endTime'] ?? null,
                'room'        => $validated['room'] ?? null,
            ]);

            return response()->json([
                'message' => 'Routine created successfully',
                'data'    => $this->formatResponse($routine)
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
                'teacherId'   => 'sometimes|required|exists:teachers,id',
                'sectionId'   => 'sometimes|required|exists:sections,id',
                'subjectId'   => 'sometimes|required|exists:subjects,id',
                'classId'     => 'sometimes|required|exists:classes,id',
                'dayOfWeek'   => 'sometimes|required|string|max:20',
                'startTime'   => 'nullable|date_format:H:i:s',
                'endTime'     => 'nullable|date_format:H:i:s',
                'room'        => 'nullable|string|max:50',
            ]);

            $routine->update([
                'teacher_id'  => $validated['teacherId']   ?? $routine->teacher_id,
                'section_id'  => $validated['sectionId']   ?? $routine->section_id,
                'subject_id'  => $validated['subjectId']   ?? $routine->subject_id,
                'class_id'    => $validated['classId']     ?? $routine->class_id,
                'day_of_week' => $validated['dayOfWeek']   ?? $routine->day_of_week,
                'start_time'  => $validated['startTime']   ?? $routine->start_time,
                'end_time'    => $validated['endTime']     ?? $routine->end_time,
                'room'        => $validated['room']        ?? $routine->room,
            ]);

            return response()->json([
                'message' => 'Routine updated successfully',
                'data'    => $this->formatResponse($routine)
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

    private function formatResponse($routine)
    {
        return [
            'id'         => $routine->id,
            'teacherId'  => $routine->teacher_id,
            'teacherName' => optional($routine->teacher)->first_name . ' ' . optional($routine->teacher)->last_name,
            'sectionId'  => $routine->section_id,
            'sectionName' => optional($routine->section)->section_name,
            'subjectId'  => $routine->subject_id,
            'subjectName' => optional($routine->subject)->name,
            'classId'    => $routine->class_id,
            'className'  => optional($routine->class)->class_name,
            'dayOfWeek'  => $routine->day_of_week,
            'startTime'  => $routine->start_time,
            'endTime'    => $routine->end_time,
            'room'       => $routine->room,
            'createdAt'  => optional($routine->created_at)->toIso8601String(),
            'updatedAt'  => optional($routine->updated_at)->toIso8601String(),
        ];
    }
}
