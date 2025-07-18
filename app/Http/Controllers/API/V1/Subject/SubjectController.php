<?php

namespace App\Http\Controllers\Api\V1\Subject;

use App\Http\Controllers\Controller;
use App\Models\Subject\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        return response()->json(Subject::all());
    }

    public function show($id)
    {
        $subject = Subject::find($id);
        if (! $subject) {
            return response()->json(['message' => 'Subject not found'], 404);
        }
        return response()->json($subject);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                 => 'required|string|max:100',
            'code'                 => 'required|string|max:20|unique:subjects',
            'type'                 => 'required|in:Theory,Practical,Both',
            'full_mark_theory'     => 'nullable|integer',
            'full_mark_practical'  => 'nullable|integer',
            'pass_mark_theory'     => 'nullable|integer',
            'pass_mark_practical'  => 'nullable|integer',
            'status'               => 'nullable|integer',
        ]);

        $subject = Subject::create($validated);

        return response()->json($subject, 201);
    }

    public function update(Request $request, $id)
    {
        $subject = Subject::find($id);
        if (! $subject) {
            return response()->json(['message' => 'Subject not found'], 404);
        }

        $validated = $request->validate([
            'name'                 => 'sometimes|required|string|max:100',
            'code'                 => 'sometimes|required|string|max:20|unique:subjects,code,'.$subject->id,
            'type'                 => 'sometimes|required|in:Theory,Practical,Both',
            'full_mark_theory'     => 'nullable|integer',
            'full_mark_practical'  => 'nullable|integer',
            'pass_mark_theory'     => 'nullable|integer',
            'pass_mark_practical'  => 'nullable|integer',
            'status'               => 'nullable|integer',
        ]);

        $subject->update($validated);

        return response()->json($subject);
    }

    public function destroy($id)
    {
        $subject = Subject::find($id);
        if (! $subject) {
            return response()->json(['message' => 'Subject not found'], 404);
        }

        $subject->delete();

        return response()->json(['message' => 'Subject deleted successfully']);
    }
}
