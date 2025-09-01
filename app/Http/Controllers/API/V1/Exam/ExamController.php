<?php

namespace App\Http\Controllers\API\V1\Exam;

use App\Http\Controllers\Controller;
use App\Models\Exam\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ExamController extends Controller
{
    public function index()
    {
        try {
            $exams = Exam::with(['classInfo', 'sectionInfo', 'subjectInfo'])->get();

            return response()->json([
                'status'  => true,
                'message' => 'Exams fetched successfully',
                'data'    => $exams->map(function ($exam) {
                    return $this->formatResponse($exam);
                }),
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching exams: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'status'  => false,
                'message' => 'Failed to fetch exams',
                'data'    => [],
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'classId'     => 'nullable|integer|exists:classes,id',
                'sectionId'   => 'nullable|integer|exists:sections,id',
                'startTime'   => 'nullable|string',
                'duration'    => 'nullable|string',
                'endTime'     => 'nullable|date',
                'examType'    => 'required|string|max:255',
                'subjects'    => 'required|array|min:1',
                'subjects.*.subjectId' => 'required|integer|exists:subjects,id',
                'subjects.*.date'      => 'required|date',
                'subjects.*.passMark'  => 'nullable|integer',
                'subjects.*.fullMark'  => 'required|integer',
                'subjects.*.roomNo'    => 'nullable|string',
            ]);

            $createdExams = [];

            foreach ($validated['subjects'] as $subject) {
                $data = [
                    'class'      => $validated['classId'] ?? null,
                    'section'    => $validated['sectionId'] ?? null,
                    'subject'    => $subject['subjectId'],
                    'date'       => $subject['date'],
                    'end_time'   => $validated['endTime'] ?? null,
                    'pass_mark'  => $subject['passMark'] ?? null,
                    'full_mark'  => $subject['fullMark'] ?? null,
                    'start_time' => $validated['startTime'] ?? null,
                    'duration'   => $validated['duration'] ?? null,
                    'room_no'    => $subject['roomNo'] ?? null,
                    'exam_type'  => $validated['examType'],
                ];

                $exam = Exam::create($data);
                $createdExams[] = $this->formatResponse($exam);
            }

            return response()->json([
                'status'  => true,
                'message' => 'Exams created successfully',
                'data'    => $createdExams,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Failed to create exams',
                'data'    => [],
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $exam = Exam::with(['classInfo', 'sectionInfo', 'subjectInfo'])->find($id);

            if (! $exam) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Exam not found',
                    'data'    => [],
                ], 404);
            }

            return response()->json([
                'status'  => true,
                'message' => 'Exam fetched successfully',
                'data'    => $this->formatResponse($exam),
            ]);
        } catch (\Exception $e) {
            Log::error("Error fetching exam ID {$id}: " . $e->getMessage());
            return response()->json([
                'status'  => false,
                'message' => 'Failed to fetch exam',
                'data'    => [],
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $exam = Exam::find($id);
            if (! $exam) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Exam not found',
                    'data'    => [],
                ], 404);
            }

            $validated = $request->validate([
                'classId'   => 'nullable|integer|exists:classes,id',
                'sectionId' => 'nullable|integer|exists:sections,id',
                'subjectId' => 'nullable|integer|exists:subjects,id',
                'date'      => 'nullable|date',
                'passMark'  => 'nullable|integer',
                'endTime'   => 'nullable|date',
                'fullMark'  => 'nullable|integer',
                'startTime' => 'nullable|string',
                'duration'  => 'nullable|string',
                'roomNo'    => 'nullable|string',
                'examType'  => 'nullable|string|max:255',
            ]);

            $data = [
                'class'      => $validated['classId'] ?? $exam->class_id,
                'section'    => $validated['sectionId'] ?? $exam->section_id,
                'subject'    => $validated['subjectId'] ?? $exam->subject_id,
                'date'       => $validated['date'] ?? $exam->date,
                'pass_mark'  => $validated['passMark'] ?? $exam->pass_mark,
                'end_time'   => $validated['endTime'] ?? $exam->end_time,
                'full_mark'  => $validated['fullMark'] ?? $exam->full_mark,
                'start_time' => $validated['startTime'] ?? $exam->start_time,
                'duration'   => $validated['duration'] ?? $exam->duration,
                'room_no'    => $validated['roomNo'] ?? $exam->room_no,
                'exam_type'  => $validated['examType'] ?? $exam->exam_type,
            ];

            $exam->update($data);

            return response()->json([
                'status'  => true,
                'message' => 'Exam updated successfully',
                'data'    => $this->formatResponse($exam),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Failed to update exam',
                'data'    => [],
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $exam = Exam::find($id);
            if (! $exam) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Exam not found',
                    'data'    => [],
                ], 404);
            }

            $exam->delete();

            return response()->json([
                'status'  => true,
                'message' => 'Exam deleted successfully',
                'data'    => [],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Failed to delete exam',
                'data'    => [],
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    protected function formatResponse($exam)
    {
        return [
            'id'          => $exam->id,
            'classId'     => $exam->class,
            'className'   => optional($exam->classInfo)->class_name,
            'sectionId'   => $exam->section,
            'sectionName' => optional($exam->sectionInfo)->section_name,
            'subjectId'   => $exam->subject,
            'subjectName' => optional($exam->subjectInfo)->name,
            'date'        => $exam->date,
            'passMark'    => $exam->pass_mark,
            'fullMark'    => $exam->full_mark,
            'endTime'     => $exam->end_time,
            'startTime'   => $exam->start_time,
            'duration'    => $exam->duration,
            'roomNo'      => $exam->room_no,
            'examType'    => $exam->exam_type,
        ];
    }
}
