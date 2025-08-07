<?php

namespace App\Http\Controllers\Api\V1\Exam;

use App\Http\Controllers\Controller;
use App\Models\Exam\Exam;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index()
    {
        $exams = Exam::all();

        return response()->json([
            'data' => $exams->map(function ($exam) {
                return $this->formatResponse($exam);
            }),
        ]);
    }

    /**
     * Store multiple exam schedules in one request.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'classId'     => 'nullable|integer|exists:classes,id',
            'sectionId'   => 'nullable|integer|exists:sections,id',
            'startTime'   => 'nullable|string',
            'duration'    => 'nullable|string',
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
            ];

            $exam = Exam::create($data);
            $createdExams[] = $this->formatResponse($exam);
        }

        return response()->json([
            'message' => 'Exams created successfully',
            'data'    => $createdExams,
        ], 201);
    }

    public function show($id)
    {
        $exam = Exam::find($id);
        if (! $exam) {
            return response()->json(['message' => 'Exam not found'], 404);
        }

        return response()->json([
            'data' => $this->formatResponse($exam),
        ]);
    }

  public function update(Request $request, $id)
{
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
}


    public function destroy($id)
    {
        $exam = Exam::find($id);
        if (! $exam) {
            return response()->json(['message' => 'Exam not found'], 404);
        }

        $exam->delete();

        return response()->json(['message' => 'Exam deleted successfully']);
    }

    /**
     * Format response to camelCase.
     */
    protected function formatResponse($exam)
    {
        return [
            'id'          => $exam->id,
            'classId'     => (int) $exam->class,
            'className'   => optional($exam->classInfo)->class_name,
            'sectionId'   => (int) $exam->section,
            'sectionName' => optional($exam->sectionInfo)->section_name,
            'subjectId'   => (int) $exam->subject,
            'subjectName' => optional($exam->subjectInfo)->name,
            'date'        => $exam->date,
            'passMark'    => (int) $exam->pass_mark,
            'fullMark'    => (int) $exam->full_mark,
            'startTime'   => $exam->start_time,
            'duration'    => $exam->duration,
            'roomNo'      => $exam->room_no,
        ];
    }
}