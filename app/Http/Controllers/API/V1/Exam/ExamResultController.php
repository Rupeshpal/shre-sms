<?php

namespace App\Http\Controllers\Api\V1\Exam;

use App\Http\Controllers\Controller;
use App\Models\Exam\ExamResult;
use Illuminate\Http\Request;

class ExamResultController extends Controller
{
    public function index()
    {
        return response()->json(ExamResult::all());
    }

    public function show($admissionNo)
    {
        $result = ExamResult::find($admissionNo);
        if (! $result) {
            return response()->json(['message' => 'Exam result not found'], 404);
        }
        return response()->json($result);
    }

public function store(Request $request)
{
    try {
        $validated = $request->validate([
            // removed: 'admissionNo' => 'required|unique:exam_results,admissionNo',
            'name'          => 'nullable|string',
            'rollNo'        => 'nullable|string',
            'class'         => 'nullable|string',
            'section'       => 'nullable|string',
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

        // Generate unique admission number
        $generatedAdmissionNo = 'A' . time();

        // Merge into validated data
        $result = ExamResult::create(array_merge($validated, [
            'admissionNo' => $generatedAdmissionNo,
        ]));

        return response()->json([
            'message' => 'Exam result created successfully',
            'data'    => $result
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
        $result = ExamResult::find($admissionNo);
        if (! $result) {
            return response()->json(['message' => 'Exam result not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'nullable|string',
            'rollNo' => 'nullable|string',
            'class' => 'nullable|string',
            'section' => 'nullable|string',
            'science' => 'nullable|integer',
            'chemistry' => 'nullable|integer',
            'math' => 'nullable|integer',
            'social' => 'nullable|integer',
            'obtainedMarks' => 'nullable|integer',
            'total' => 'nullable|integer',
            'percentage' => 'nullable|string',
            'grade' => 'nullable|string',
            'result' => 'nullable|string',
        ]);

        $result->update($validated);

        return response()->json(['message' => 'Exam result updated successfully', 'data' => $result]);
    }

    public function destroy($admissionNo)
    {
        $result = ExamResult::find($admissionNo);
        if (! $result) {
            return response()->json(['message' => 'Exam result not found'], 404);
        }

        $result->delete();
        return response()->json(['message' => 'Exam result deleted successfully']);
    }
}
