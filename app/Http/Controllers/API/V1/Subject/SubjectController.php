<?php

namespace App\Http\Controllers\API\V1\Subject;

use App\Http\Controllers\Controller;
use App\Models\Subject\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubjectController extends Controller
{
    private function convertCamelToSnake(array $input): array
    {
        return collect($input)
            ->mapWithKeys(fn($value, $key) => [Str::snake($key) => $value])
            ->toArray();
    }

    private function formatResponse(Subject $subject): array
    {
        return [
            'id'                => $subject->id,
            'name'              => $subject->name,
            'code'              => $subject->code,
            'type'              => $subject->type,
            'fullMarkTheory'    => $subject->full_mark_theory,
            'fullMarkPractical' => $subject->full_mark_practical,
            'passMarkTheory'    => $subject->pass_mark_theory,
            'passMarkPractical' => $subject->pass_mark_practical,
            'status'            => (bool) $subject->status,
            'createdAt'         => $subject->created_at?->toIso8601String(),
            'updatedAt'         => $subject->updated_at?->toIso8601String(),
        ];
    }

    public function index()
    {
        $subjects = Subject::all()->map(fn($subject) => $this->formatResponse($subject));

        return response()->json([
            'status'  => true,
            'message' => 'Subjects fetched successfully',
            'data'    => $subjects,
        ]);
    }

    public function show($id)
    {
        $subject = Subject::find($id);

        if (!$subject) {
            return response()->json([
                'status'  => false,
                'message' => 'Subject not found',
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Subject fetched successfully',
            'data'    => $this->formatResponse($subject),
        ]);
    }

    public function store(Request $request)
    {
        try {
            $data = $this->convertCamelToSnake($request->all());

            $validated = validator($data, [
                'name'                 => 'required|string|max:100',
                'code'                 => 'required|string|max:20|unique:subjects,code',
                'type'                 => 'required|in:Theory,Practical,Both',
                'full_mark_theory'     => 'nullable|integer',
                'full_mark_practical'  => 'nullable|integer',
                'pass_mark_theory'     => 'nullable|integer',
                'pass_mark_practical'  => 'nullable|integer',
                'status'               => 'nullable|boolean',
            ])->validate();

            $subject = Subject::create($validated);

            return response()->json([
                'status'  => true,
                'message' => 'Subject created successfully',
                'data'    => $this->formatResponse($subject),
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation failed',
                'errors'  => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Unexpected error occurred',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $subject = Subject::find($id);
        if (!$subject) {
            return response()->json([
                'status'  => false,
                'message' => 'Subject not found',
            ], 404);
        }

        $data = $this->convertCamelToSnake($request->all());

        $validated = validator($data, [
            'name'                 => 'sometimes|required|string|max:100',
            'code'                 => 'sometimes|required|string|max:20|unique:subjects,code,' . $subject->id,
            'type'                 => 'sometimes|required|in:Theory,Practical,Both',
            'full_mark_theory'     => 'nullable|integer',
            'full_mark_practical'  => 'nullable|integer',
            'pass_mark_theory'     => 'nullable|integer',
            'pass_mark_practical'  => 'nullable|integer',
            'status'               => 'nullable|boolean',
        ])->validate();

        $subject->update($validated);

        return response()->json([
            'status'  => true,
            'message' => 'Subject updated successfully',
            'data'    => $this->formatResponse($subject),
        ]);
    }

    public function destroy($id)
    {
        $subject = Subject::find($id);
        if (!$subject) {
            return response()->json([
                'status'  => false,
                'message' => 'Subject not found',
            ], 404);
        }

        $subject->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Subject deleted successfully',
        ]);
    }
}
