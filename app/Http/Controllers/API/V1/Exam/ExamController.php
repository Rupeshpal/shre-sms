<?php
namespace App\Http\Controllers\Api\V1\Exam;
use App\Http\Controllers\Controller;
use App\Models\Exam\Exam;
use Illuminate\Http\Request;
class ExamController extends Controller
{ 
    public function index()
    {
        return response()->json(Exam::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'class'     => 'nullable|string',
            'section'   => 'nullable|string',
            'subject'   => 'nullable|string',
            'date'      => 'nullable|date',
            'passMark'  => 'nullable|integer',
            'startTime' => 'nullable|string',
            'duration'  => 'nullable|string',
            'roomNo'    => 'nullable|string',
        ]);

        $exam = Exam::create($validated);

        return response()->json(['message' => 'Exam created successfully', 'data' => $exam], 201);
    }

    public function show($id)
    {
        $exam = Exam::find($id);
        if (! $exam) {
            return response()->json(['message' => 'Exam not found'], 404);
        }
        return response()->json($exam);
    }

    public function update(Request $request, $id)
    {
        $exam = Exam::find($id);
        if (! $exam) {
            return response()->json(['message' => 'Exam not found'], 404);
        }

        $validated = $request->validate([
            'class'     => 'nullable|string',
            'section'   => 'nullable|string',
            'subject'   => 'nullable|string',
            'date'      => 'nullable|date',
            'passMark'  => 'nullable|integer',
            'startTime' => 'nullable|string',
            'duration'  => 'nullable|string',
            'roomNo'    => 'nullable|string',
        ]);

        $exam->update($validated);

        return response()->json(['message' => 'Exam updated successfully', 'data' => $exam]);
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
}
