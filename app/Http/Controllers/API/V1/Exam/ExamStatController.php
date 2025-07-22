<?php

namespace App\Http\Controllers\Api\V1\Exam;
use App\Http\Controllers\Controller;
use App\Models\Exam\ExamStat;
use Illuminate\Http\Request;

class ExamStatController extends Controller
{
    public function index()
    {
        return response()->json(ExamStat::all());
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'heading' => 'required|string',
                'value'   => 'required|integer',
            ]);

            $stat = ExamStat::create($validated);

            return response()->json([
                'message' => 'Exam stat created successfully',
                'data' => $stat
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error saving data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $stat = ExamStat::find($id);
        if (!$stat) {
            return response()->json(['message' => 'Stat not found'], 404);
        }
        return response()->json($stat);
    }

    public function update(Request $request, $id)
    {
        $stat = ExamStat::find($id);
        if (!$stat) {
            return response()->json(['message' => 'Stat not found'], 404);
        }

        $validated = $request->validate([
            'heading' => 'sometimes|required|string',
            'value'   => 'sometimes|required|integer',
        ]);

        $stat->update($validated);

        return response()->json([
            'message' => 'Exam stat updated successfully',
            'data' => $stat
        ]);
    }

    public function destroy($id)
    {
        $stat = ExamStat::find($id);
        if (!$stat) {
            return response()->json(['message' => 'Stat not found'], 404);
        }

        $stat->delete();

        return response()->json(['message' => 'Exam stat deleted successfully']);
    }
}
