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
            $exams = Exam::all();

            return response()->json([
                'data' => $exams->map(function ($exam) {
                    return $this->formatResponse($exam);
                }),
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching exams: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'Failed to fetch exams'], 500);
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
                'message' => 'Exams created successfully',
                'data'    => $createdExams,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating exams: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'Failed to create exams'], 500);
        }
    }

    public function show($id)
    {
        try {
            $exam = Exam::find($id);
            if (! $exam) {
                return response()->json(['message' => 'Exam not found'], 404);
            }

            return response()->json([
                'data' => $this->formatResponse($exam),
            ]);
        } catch (\Exception $e) {
            Log::error("Error fetching exam ID {$id}: " . $e->getMessage());
            return response()->json(['message' => 'Failed to fetch exam'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $exam = Exam::find($id);
            if (! $exam) {
                return response()->json(['message' => 'Exam not found'], 404);
            }

            $validated = $request->validate([
                'class'      => 'nullable|string',
                'section'    => 'nullable|string',
                'subject'    => 'nullable|string',
                'date'       => 'nullable|date',
                'passMark'   => 'nullable|integer',
                'fullMark'   => 'nullable|integer',
                'startTime'  => 'nullable|string',
                'duration'   => 'nullable|string',
                'roomNo'     => 'nullable|string',
                'examType'   => 'nullable|string|max:255',
            ]);

            $fieldsMap = [
                'class'     => 'class',
                'section'   => 'section',
                'subject'   => 'subject',
                'date'      => 'date',
                'passMark'  => 'pass_mark',
                'fullMark'  => 'full_mark',
                'startTime' => 'start_time',
                'duration'  => 'duration',
                'roomNo'    => 'room_no',
                'examType'  => 'exam_type',
            ];

            $data = [];
            foreach ($fieldsMap as $inputKey => $dbColumn) {
                $data[$dbColumn] = $validated[$inputKey] ?? $exam->$dbColumn;
            }

            $exam->update($data);

            return response()->json([
                'message' => 'Exam updated successfully',
                'data'    => $this->formatResponse($exam),
            ]);
        } catch (\Exception $e) {
            Log::error("Error updating exam ID {$id}: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'Failed to update exam'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $exam = Exam::find($id);
            if (! $exam) {
                return response()->json(['message' => 'Exam not found'], 404);
            }

            $exam->delete();

            return response()->json(['message' => 'Exam deleted successfully']);
        } catch (\Exception $e) {
            Log::error("Error deleting exam ID {$id}: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'Failed to delete exam'], 500);
        }
    }

    protected function formatResponse($exam)
    {
        return [
            'id'          => $exam->id,
            'classId'     => $exam->class ? (int) $exam->class : null,
            'className'   => optional($exam->classInfo)->class_name,
            'sectionId'   => $exam->section ? (int) $exam->section : null,
            'sectionName' => optional($exam->sectionInfo)->section_name,
            'subjectId'   => $exam->subject ? (int) $exam->subject : null,
            'subjectName' => optional($exam->subjectInfo)->name,
            'date'        => $exam->date,
            'passMark'    => $exam->pass_mark !== null ? (int) $exam->pass_mark : null,
            'fullMark'    => $exam->full_mark !== null ? (int) $exam->full_mark : null,
            'startTime'   => $exam->start_time,
            'duration'    => $exam->duration,
            'roomNo'      => $exam->room_no,
            'examType'    => $exam->exam_type,
        ];
    }
}
