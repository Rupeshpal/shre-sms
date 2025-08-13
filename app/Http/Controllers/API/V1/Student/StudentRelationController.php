<?php

namespace App\Http\Controllers\Api\V1\Student;

use App\Http\Controllers\Controller;
use App\Models\Student\StudentRelation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StudentRelationController extends Controller
{
    private function convertCamelToSnake(array $input): array
    {
        return collect($input)
            ->mapWithKeys(fn($value, $key) => [Str::snake($key) => $value])
            ->toArray();
    }

    private function formatResponse(StudentRelation $relation): array
    {
        return [
            'id'               => $relation->id,
            'studentId'        => $relation->student_id,
            'studentName'      => $relation->student?->first_name . ' ' . $relation->student?->last_name ?? null,
            'relation'         => $relation->relation,
            'name'             => $relation->name,
            'email'            => $relation->email,
            'phoneNumber'      => $relation->phone_number,
            'occupation'       => $relation->occupation,
            'temporaryAddress' => $relation->temporary_address,
            'permanentAddress' => $relation->permanent_address,
            'nationality'      => $relation->nationality,
            'monthlyIncome'    => $relation->monthly_income,
            'document'         => $relation->document,
            'createdAt'        => $relation->created_at?->toIso8601String(),
            'updatedAt'        => $relation->updated_at?->toIso8601String(),
        ];
    }

    public function index()
    {
        $relations = StudentRelation::all()->map(fn($relation) => $this->formatResponse($relation));
        return response()->json($relations);
    }

    public function store(Request $request)
    {
        $data = $this->convertCamelToSnake($request->all());

        $validated = validator($data, [
            'student_id'        => 'required|exists:student_personal_info,id',
            'relation'          => 'required|in:Father,Mother,Guardian',
            'name'              => 'nullable|string|max:100',
            'email'             => 'nullable|email|max:100',
            'phone_number'      => 'nullable|string|max:20',
            'occupation'        => 'nullable|string|max:100',
            'temporary_address' => 'nullable|string|max:255',
            'permanent_address' => 'nullable|string|max:255',
            'nationality'       => 'nullable|string|max:50',
            'monthly_income'    => 'nullable|numeric',
            'document'          => 'nullable|file|mimes:jpg,png,pdf|max:2048',
        ])->validate();

        if ($request->hasFile('document')) {
            $validated['document'] = $request->file('document')->store('student_relation_docs', 'public');
        }

        $relation = StudentRelation::create($validated);

        return response()->json([
            'message' => 'Saved successfully',
            'data'    => $this->formatResponse($relation),
        ], 201);
    }

    public function show($id)
    {
        $relation = StudentRelation::find($id);
        if (!$relation) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json($this->formatResponse($relation));
    }

    public function update(Request $request, $id)
    {
        $relation = StudentRelation::find($id);
        if (!$relation) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $data = $this->convertCamelToSnake($request->all());

        $validated = validator($data, [
            'student_id'        => 'sometimes|required|exists:student_personal_info,id',
            'relation'          => 'sometimes|required|in:Father,Mother,Guardian',
            'name'              => 'nullable|string|max:100',
            'email'             => 'nullable|email|max:100',
            'phone_number'      => 'nullable|string|max:20',
            'occupation'        => 'nullable|string|max:100',
            'temporary_address' => 'nullable|string|max:255',
            'permanent_address' => 'nullable|string|max:255',
            'nationality'       => 'nullable|string|max:50',
            'monthly_income'    => 'nullable|numeric',
            'document'          => 'nullable|file|mimes:jpg,png,pdf|max:2048',
        ])->validate();

        if ($request->hasFile('document')) {
            $validated['document'] = $request->file('document')->store('student_relation_docs', 'public');
        }

        $relation->update($validated);

        return response()->json([
            'message' => 'Updated successfully',
            'data'    => $this->formatResponse($relation),
        ]);
    }

    public function destroy($id)
    {
        $relation = StudentRelation::find($id);
        if (!$relation) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $relation->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
