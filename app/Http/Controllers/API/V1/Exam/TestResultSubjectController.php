<?php

namespace App\Http\Controllers\Api\V1\Exam;

use App\Http\Controllers\Controller;
use App\Models\Exam\TestResultSubject;
use Illuminate\Http\Request;

class TestResultSubjectController extends Controller
{
    public function index()
    {
        $subjects = TestResultSubject::with('testResult')->get();

        return response()->json([
            'data' => $subjects->map(fn ($subject) => $this->formatResponse($subject))
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'testResultId'   => 'required|exists:test_results,id',
                'name'           => 'nullable|string',
                'fullMarks'      => 'nullable|integer',
                'passMarks'      => 'nullable|integer',
                'obtainedMarks'  => 'nullable|integer',
                'result'         => 'nullable|string',
            ]);

            $subject = TestResultSubject::create($validated);

            return response()->json([
                'message' => 'Subject added successfully',
                'data'    => $this->formatResponse($subject->load('testResult')),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error saving data',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        $subject = TestResultSubject::with('testResult')->find($id);

        if (!$subject) {
            return response()->json(['message' => 'Subject not found'], 404);
        }

        return response()->json([
            'data' => $this->formatResponse($subject)
        ]);
    }

    public function update(Request $request, $id)
    {
        $subject = TestResultSubject::find($id);

        if (!$subject) {
            return response()->json(['message' => 'Subject not found'], 404);
        }

        $validated = $request->validate([
            'testResultId'   => 'sometimes|required|exists:test_results,id',
            'name'           => 'nullable|string',
            'fullMarks'      => 'nullable|integer',
            'passMarks'      => 'nullable|integer',
            'obtainedMarks'  => 'nullable|integer',
            'result'         => 'nullable|string',
        ]);

        $subject->update($validated);

        return response()->json([
            'message' => 'Subject updated successfully',
            'data'    => $this->formatResponse($subject->load('testResult')),
        ]);
    }

    public function destroy($id)
    {
        $subject = TestResultSubject::find($id);

        if (!$subject) {
            return response()->json(['message' => 'Subject not found'], 404);
        }

        $subject->delete();

        return response()->json(['message' => 'Subject deleted successfully']);
    }

    private function formatResponse($subject)
    {
        return [
            'id'             => $subject->id,
            'testResultId'   => (int) $subject->testResultId,
            'testResultName' => optional($subject->testResult)->testName,
            'name'           => $subject->name,
            'fullMarks'      => (int) $subject->fullMarks,
            'passMarks'      => (int) $subject->passMarks,
            'obtainedMarks'  => (int) $subject->obtainedMarks,
            'result'         => $subject->result,
            'createdAt'      => optional($subject->created_at)->toIso8601String(),
            'updatedAt'      => optional($subject->updated_at)->toIso8601String(),
        ];
    }
}
