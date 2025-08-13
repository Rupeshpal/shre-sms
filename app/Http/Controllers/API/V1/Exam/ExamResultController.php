<?php

    namespace App\Http\Controllers\Api\V1\Exam;

use App\Http\Controllers\Controller;
use App\Models\Exam\ExamResult;
use Illuminate\Http\Request;

class ExamResultController extends Controller
{
    public function index()
    {
        $results = ExamResult::all();

        return response()->json([
            'data' => $results->map(function ($result) {
                return $this->formatResponse($result);
            }),
        ]);
    }

    public function show($admissionNo)
    {
        $result = ExamResult::where('admissionNo', $admissionNo)->first();

        if (! $result) {
            return response()->json(['message' => 'Exam result not found'], 404);
        }

        return response()->json([
            'data' => $this->formatResponse($result),
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'          => ' required |string',
                'rollNo'        => ' required |string',
                'classId'       => ' required |integer|exists:classes,id',
                'sectionId'     => ' required |integer |exists:sections,id',
                'science'       => ' required |integer',
                'chemistry'     => ' required |integer',
                'math'          => ' required |integer',
                'social'        => ' required |integer',
                'obtainedMarks' => ' required |integer',
                'total'         => ' required |integer',
                'percentage'    => ' required |string',
                'grade'         => ' required |string',
                'result'        => ' required |string',
            ]);

            $generatedAdmissionNo = 'A' . time();

            $examResult = ExamResult::create(array_merge($validated, [
                'admissionNo' => $generatedAdmissionNo,
            ]));

            return response()->json([
                'message' => 'Exam result created successfully',
                'data'    => $this->formatResponse($examResult),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error saving data',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $admissionNo)
    {
        $result = ExamResult::where('admissionNo', $admissionNo)->first();

        if (! $result) {
            return response()->json(['message' => 'Exam result not found'], 404);
        }

        $validated = $request->validate([
            'name'          => 'nullable|string',
            'rollNo'        => 'nullable|string',
            'classId'       => 'nullable|integer |exists:classes,id',
            'sectionId'     => 'nullable| integer |exists:sections,id',
            'science'       => 'nullable|integer',
            'chemistry'     => 'nullable|integer',
            'math'          => 'nullable|integer',
            'social'        => 'nullable|integer',
            'obtainedMarks' => 'nullable|integer',
            'total'         => 'nullable|integer',
            'percentage'    => 'nullable|string',
            'grade'         => 'nullable|string',
            'result'        => 'nullable|string',
        ]);

        $result->update($validated);

        return response()->json([
            'message' => 'Exam result updated successfully',
            'data'    => $this->formatResponse($result),
        ]);
    }

    public function destroy($admissionNo)
    {
        $result = ExamResult::where('admissionNo', $admissionNo)->first();

        if (! $result) {
            return response()->json(['message' => 'Exam result not found'], 404);
        }

        $result->delete();

        return response()->json(['message' => 'Exam result deleted successfully']);
    }

    /**
     * Return camelCase formatted response
     */
    protected function formatResponse($result)
    {
        return [
            'id'            => $result->id,
            'admissionNo'   => $result->admissionNo,
            'name'          => $result->name,
            'rollNo'        => $result->rollNo,
            'classId'       => (int) $result->classId,
            'className'     => optional($result->classInfo)->class_name,
            'sectionId'     => (int) $result->sectionId,
            'sectionName'   => optional($result->sectionInfo)->section_name,
            'science'       => (int) $result->science,
            'chemistry'     => (int) $result->chemistry,
            'math'          => (int) $result->math,
            'social'        => (int) $result->social,
            'obtainedMarks' => (int) $result->obtainedMarks,
            'total'         => (int) $result->total,
            'percentage'    => $result->percentage,
            'grade'         => $result->grade,
            'result'        => $result->result,
            'createdAt'     => $result->created_at->toIso8601String(),
            'updatedAt'     => $result->updated_at->toIso8601String(),
        ];
    }
}
