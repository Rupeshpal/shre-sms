<?php

namespace App\Http\Controllers\Api\V1\Exam;

use App\Http\Controllers\Controller;
use App\Models\Exam\TestResult;
use Illuminate\Http\Request;

class TestResultController extends Controller
{
    public function index()
    {
        return response()->json(TestResult::all());
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'testName'        => 'nullable|string',
                'status'          => 'nullable|string',
                'rank'            => 'nullable|integer',
                'totalMarks'      => 'nullable|integer',
                'passMarks'       => 'nullable|integer',
                'obtainedMarks'   => 'nullable|integer',
                'passPercentage'  => 'nullable|integer',
            ]);

            $result = TestResult::create($validated);

            return response()->json([
                'message' => 'Test result created successfully',
                'data'    => $result,
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error saving data', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $result = TestResult::find($id);
        if (!$result) {
            return response()->json(['message' => 'Test result not found'], 404);
        }
        return response()->json($result);
    }

    public function update(Request $request, $id)
    {
        $result = TestResult::find($id);
        if (!$result) {
            return response()->json(['message' => 'Test result not found'], 404);
        }

        $validated = $request->validate([
            'testName'        => 'nullable|string',
            'status'          => 'nullable|string',
            'rank'            => 'nullable|integer',
            'totalMarks'      => 'nullable|integer',
            'passMarks'       => 'nullable|integer',
            'obtainedMarks'   => 'nullable|integer',
            'passPercentage'  => 'nullable|integer',
        ]);

        $result->update($validated);

        return response()->json([
            'message' => 'Test result updated successfully',
            'data'    => $result,
        ]);
    }

    public function destroy($id)
    {
        $result = TestResult::find($id);
        if (!$result) {
            return response()->json(['message' => 'Test result not found'], 404);
        }
        $result->delete();

        return response()->json(['message' => 'Test result deleted successfully']);
    }
}
