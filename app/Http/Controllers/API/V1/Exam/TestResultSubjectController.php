<?php

namespace App\Http\Controllers\Api\V1\Exam;

use App\Http\Controllers\Controller;
use App\Models\Exam\TestResultSubject;
use Illuminate\Http\Request;

class TestResultSubjectController extends Controller
{
    public function index()
    {
        return response()->json(TestResultSubject::all());
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
                'data'    => $subject
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error saving data', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $subject = TestResultSubject::find($id);
        if (!$subject) {
            return response()->json(['message' => 'Subject not found'], 404);
        }
        return response()->json($subject);
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
            'data'    => $subject
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
}
