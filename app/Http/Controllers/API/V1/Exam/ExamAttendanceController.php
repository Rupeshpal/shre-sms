<?php

namespace App\Http\Controllers\API\V1\Exam;

use App\Http\Controllers\Controller;
use App\Models\Exam\ExamAttendance;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class ExamAttendanceController extends Controller
{
    public function index()
    {
        $attendances = ExamAttendance::with(['class', 'section', 'student'])->get();

        return response()->json([
            'data' => $attendances->map(function ($item) {
                return $this->formatResponse($item);
            }),
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'rollNo'     => 'required|string',
                'classId'    => 'required|exists:classes,id',
                'sectionId'  => 'required|exists:sections,id',
                'studentId'  => 'required|exists:student_personal_info,id',
                'science'    => 'nullable|numeric',
                'chemistry'  => 'nullable|numeric',
                'math'       => 'nullable|numeric',
                'social'     => 'nullable|numeric',
            ]);

            $data = [
                'roll_no'     => $validated['rollNo'],
                'class_id'    => $validated['classId'],
                'section_id'  => $validated['sectionId'],
                'student_id'  => $validated['studentId'],
                'science'     => $validated['science'] ?? null,
                'chemistry'   => $validated['chemistry'] ?? null,
                'math'        => $validated['math'] ?? null,
                'social'      => $validated['social'] ?? null,
            ];

            $attendance = ExamAttendance::create($data);
            $attendance->load(['class', 'section', 'student']);

            return response()->json([
                'message' => 'Attendance created successfully',
                'data'    => $this->formatResponse($attendance),
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error saving data',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $attendance = ExamAttendance::with(['class', 'section', 'student'])->find($id);
        if (! $attendance) {
            return response()->json(['message' => 'Attendance not found'], 404);
        }

        return response()->json([
            'data' => $this->formatResponse($attendance)
        ]);
    }

    public function update(Request $request, $id)
    {
        $attendance = ExamAttendance::find($id);
        if (! $attendance) {
            return response()->json(['message' => 'Attendance not found'], 404);
        }

        $validated = $request->validate([
            'rollNo'     => 'nullable|string',
            'classId'    => 'nullable|exists:classes,id',
            'sectionId'  => 'nullable|exists:sections,id',
            'studentId'  => 'nullable|exists:student_personal_info,id',
            'science'    => 'nullable|numeric',
            'chemistry'  => 'nullable|numeric',
            'math'       => 'nullable|numeric',
            'social'     => 'nullable|numeric',
        ]);

        $data = [
            'roll_no'     => $validated['rollNo'] ?? $attendance->roll_no,
            'class_id'    => $validated['classId'] ?? $attendance->class_id,
            'section_id'  => $validated['sectionId'] ?? $attendance->section_id,
            'student_id'  => $validated['studentId'] ?? $attendance->student_id,
            'science'     => $validated['science'] ?? $attendance->science,
            'chemistry'   => $validated['chemistry'] ?? $attendance->chemistry,
            'math'        => $validated['math'] ?? $attendance->math,
            'social'      => $validated['social'] ?? $attendance->social,
        ];

        $attendance->update($data);
        $attendance->load(['class', 'section', 'student']);

        return response()->json([
            'message' => 'Attendance updated successfully',
            'data'    => $this->formatResponse($attendance)
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

    /**
     * Convert snake_case to camelCase for JSON response
     */
    protected function formatResponse($attendance)
    {
        return [
            'id'          => $attendance->id,
            'rollNo'      => $attendance->roll_no,
            'classId'     => $attendance->class_id,
            'className'   => optional($attendance->class)->class_name,
            'sectionId'   => $attendance->section_id,
            'sectionName' => optional($attendance->section)->section_name,
            'studentId'   => $attendance->student_id,
            'studentName' => optional($attendance->student)->first_name . ' ' . optional($attendance->student)->last_name,
            'science'     => $attendance->science ==1 ? 'Present' : 'Absent',
            'chemistry'   => $attendance->chemistry==1 ? 'Present' : 'Absent',
            'math'        => $attendance->math ==1 ? 'Present' : 'Absent',
            'social'      => $attendance->social ? 'Present' : 'Absent',
            'createdAt'   => $attendance->created_at ? $attendance->created_at->
             toDateTimeString() : null,
            'updatedAt'   => $attendance->updated_at ? $attendance->updated_at->toDateTimeString() : null,
        ];
    }
}
