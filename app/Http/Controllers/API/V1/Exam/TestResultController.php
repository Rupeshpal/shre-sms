<?php

namespace App\Http\Controllers\Api\V1\Exam;

use App\Http\Controllers\Controller;
use App\Models\Exam\TestResult;
use Illuminate\Http\Request;

class TestResultController extends Controller
{
    public function index()
    {
        $results = TestResult::all();

        return response()->json([
            'data' => $results->map(fn ($result) => $this->formatResponse($result))->values()
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'testName'       => 'required|string',
                'status'         => 'required|boolean',
                'rank'           => 'required|integer',
                'totalMarks'     => 'required|integer',
                'passMarks'      => 'required|integer',
                'obtainedMarks'  => 'required|integer',
                'passPercentage' => 'required|integer',
            ]);

            $result = TestResult::create($validated)->fresh();

            return response()->json([
                'message' => 'Test result created successfully',
                'data'    => $this->formatResponse($result),
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
        $result = TestResult::find($id);
        if (! $result) {
            return response()->json(['message' => 'Test result not found'], 404);
        }

        return response()->json([
            'data' => $this->formatResponse($result)
        ]);
    }

    public function update(Request $request, $id)
    {
        $result = TestResult::find($id);
        if (! $result) {
            return response()->json(['message' => 'Test result not found'], 404);
        }

        $validated = $request->validate([
            'testName'       => 'required|string',
            'status'         => 'required|boolean',
            'rank'           => 'required|integer',
            'totalMarks'     => 'required|integer',
            'passMarks'      => 'required|integer',
            'obtainedMarks'  => 'required|integer',
            'passPercentage' => 'required|integer',
        ]);

        $result->update($validated);

        return response()->json([
            'message' => 'Test result updated successfully',
            'data'    => $this->formatResponse($result->fresh()),
        ]);
    }

    public function destroy($id)
    {
        $result = TestResult::find($id);
        if (! $result) {
            return response()->json(['message' => 'Test result not found'], 404);
        }

        $result->delete();

        return response()->json(['message' => 'Test result deleted successfully']);
    }

    protected function formatResponse($result)
    {
        return [
            'id'             => $result->id,
            'testName'       => $result->testName,
            'status'         => (bool) $result->status,
            'rank'           => $result->rank,
            'totalMarks'     => $result->totalMarks,
            'passMarks'      => $result->passMarks,
            'obtainedMarks'  => $result->obtainedMarks,
            'passPercentage' => $result->passPercentage,
            'createdAt'      => optional($result->created_at)->toIso8601String(),
            'updatedAt'      => optional($result->updated_at)->toIso8601String(),
        ];
    }
}