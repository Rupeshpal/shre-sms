<?php

namespace App\Http\Controllers\Api\V1\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Teacher\TeacherAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class TeacherAttendanceController extends Controller
{
    public function index()
    {
        try {
            $attendances = TeacherAttendance::all();

            return response()->json([
                'data' => $attendances->map(fn($attendance) => $this->formatResponse($attendance))
            ]);
        } catch (\Exception $e) {
            return $this->serverError($e);
        }
    }

    public function show($id)
    {
        try {
            $attendance = TeacherAttendance::find($id);

            if (! $attendance) {
                return response()->json(['message' => 'Attendance not found'], 404);
            }

            return response()->json([
                'data' => $this->formatResponse($attendance)
            ]);
        } catch (\Exception $e) {
            return $this->serverError($e);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'teacherId'         => 'required|exists:teachers,id',
                'attendancePercent' => 'nullable|string|max:10',
            ]);

            $data = [
                'teacher_id'         => $validated['teacherId'],
                'attendance_percent' => $validated['attendancePercent'] ?? null,
            ];

            $attendance = TeacherAttendance::create($data);

            return response()->json([
                'message' => 'Attendance created successfully',
                'data'    => $this->formatResponse($attendance),
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return $this->serverError($e);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $attendance = TeacherAttendance::find($id);

            if (! $attendance) {
                return response()->json(['message' => 'Attendance not found'], 404);
            }

            $validated = $request->validate([
                'teacherId'         => 'sometimes|required|exists:teachers,id',
                'attendancePercent' => 'nullable|string|max:10',
            ]);

            $data = [];

            if (isset($validated['teacherId'])) {
                $data['teacher_id'] = $validated['teacherId'];
            }

            if (array_key_exists('attendancePercent', $validated)) {
                $data['attendance_percent'] = $validated['attendancePercent'];
            }

            $attendance->update($data);

            return response()->json([
                'message' => 'Attendance updated successfully',
                'data'    => $this->formatResponse($attendance),
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return $this->serverError($e);
        }
    }

    public function destroy($id)
    {
        try {
            $attendance = TeacherAttendance::find($id);

            if (! $attendance) {
                return response()->json(['message' => 'Attendance not found'], 404);
            }

            $attendance->delete();

            return response()->json(['message' => 'Attendance deleted successfully']);
        } catch (\Exception $e) {
            return $this->serverError($e);
        }
    }

    private function formatResponse($attendance)
    {
        return [
            'id'                => $attendance->id,
            'teacherId'         => (int) $attendance->teacher_id,
            'teacherName'     => optional($attendance->teacher)->first_name . ' ' . optional($attendance->teacher)->last_name,
            'attendancePercent' => $attendance->attendance_percent  ,
            'createdAt'         => optional($attendance->created_at)->toIso8601String(),
            'updatedAt'         => optional($attendance->updated_at)->toIso8601String(),
        ];
    }

    private function serverError(\Exception $e)
    {
        Log::error('Server Error', ['error' => $e->getMessage()]);

        return response()->json([
            'message' => 'Internal server error',
            'error'   => app()->isLocal() ? $e->getMessage() : null
        ], 500);
    }
}
