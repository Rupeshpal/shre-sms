<?php

namespace App\Http\Controllers\Exam;

use App\Http\Controllers\Controller;
use App\Models\Exam\ExamAttendance;
use Illuminate\Http\Request;

class ExamAttendanceController extends Controller
{
    public function index()
    {
        return response()->json(ExamAttendance::all());
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'admissionNo' => 'required|string|unique:exam_attendances,admissionNo',
                'rollNo' => 'nullable|string',
                'class' => 'nullable|string',
                'section' => 'nullable|string',
                'student' => 'nullable|string',
                'science' => 'nullable|string',
                'chemistry' => 'nullable|string',
                'math' => 'nullable|string',
                'social' => 'nullable|string',
            ]);

            $attendance = ExamAttendance::create($validated);

            return response()->json([
                'message' => 'Attendance created successfully',
                'data' => $attendance
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error saving data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $attendance = ExamAttendance::find($id);
        if (! $attendance) {
            return response()->json(['message' => 'Attendance not found'], 404);
        }
        return response()->json($attendance);
    }

    public function update(Request $request, $id)
    {
        $attendance = ExamAttendance::find($id);
        if (! $attendance) {
            return response()->json(['message' => 'Attendance not found'], 404);
        }

        $validated = $request->validate([
            'rollNo' => 'nullable|string',
            'class' => 'nullable|string',
            'section' => 'nullable|string',
            'student' => 'nullable|string',
            'science' => 'nullable|string',
            'chemistry' => 'nullable|string',
            'math' => 'nullable|string',
            'social' => 'nullable|string',
        ]);

        $attendance->update($validated);

        return response()->json([
            'message' => 'Attendance updated successfully',
            'data' => $attendance
        ]);
    }

    public function destroy($id)
    {
        $attendance = ExamAttendance::find($id);
        if (! $attendance) {
            return response()->json(['message' => 'Attendance not found'], 404);
        }

        $attendance->delete();

        return response()->json(['message' => 'Attendance deleted successfully']);
    }
}
