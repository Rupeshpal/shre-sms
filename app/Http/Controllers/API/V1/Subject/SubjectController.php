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
    try {
        $validated = $request->validate([
            'name'                 => 'required|string|max:100',
            'code'                 => 'required|string|max:20',
            'type'                 => 'required|in:Theory,Practical,Both',
            'full_mark_theory'     => 'nullable|integer',
            'full_mark_practical'  => 'nullable|integer',
            'pass_mark_theory'     => 'nullable|integer',
            'pass_mark_practical'  => 'nullable|integer',
            'status'               => 'nullable|boolean',
        ]);

        if (Subject::where('code', $validated['code'])->exists()) {
            $validated['code'] .= '_' . uniqid();
        }

        $subject = Subject::create($validated);

        return response()->json([
            'message' => 'Subject created successfully',
            'data'    => $subject
        ], 201);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'message' => 'Validation failed',
            'errors'  => $e->errors()
        ], 422);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Unexpected error occurred',
            'error'   => $e->getMessage()
        ], 500);
    }
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
            'status'               => 'nullable|boolean',
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
