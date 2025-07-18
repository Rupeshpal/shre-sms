<?php

namespace App\Http\Controllers\Api\V1\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Teacher\TeacherAttendance;
use Illuminate\Http\Request;

class TeacherAttendanceController extends Controller
{
    public function index()
    {
        return response()->json(TeacherAttendance::all());
    }

    public function show($id)
    {
        $attendance = TeacherAttendance::find($id);
        if (! $attendance) {
            return response()->json(['message' => 'Attendance not found'], 404);
        }
        return response()->json($attendance);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'teacher_id'         => 'required|exists:teachers,id',
            'attendance_percent' => 'nullable|string|max:10',
        ]);

        $attendance = TeacherAttendance::create($validated);

        return response()->json([
            'message' => 'Attendance created successfully',
            'data'    => $attendance
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $attendance = TeacherAttendance::find($id);
        if (! $attendance) {
            return response()->json(['message' => 'Attendance not found'], 404);
        }

        $validated = $request->validate([
            'teacher_id'         => 'sometimes|required|exists:teachers,id',
            'attendance_percent' => 'nullable|string|max:10',
        ]);

        $attendance->update($validated);

        return response()->json([
            'message' => 'Attendance updated successfully',
            'data'    => $attendance
        ]);
    }

    public function destroy($id)
    {
        $attendance = TeacherAttendance::find($id);
        if (! $attendance) {
            return response()->json(['message' => 'Attendance not found'], 404);
        }

        $attendance->delete();

        return response()->json(['message' => 'Attendance deleted successfully']);
    }
}
