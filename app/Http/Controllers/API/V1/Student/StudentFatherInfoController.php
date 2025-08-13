<?php

namespace App\Http\Controllers\API\V1\Student;

use App\Http\Controllers\Controller;
use App\Models\Student\StudentFatherInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StudentFatherInfoController extends Controller
{
    private function convertCamelToSnake(array $input): array
    {
        return collect($input)
            ->mapWithKeys(fn($value, $key) => [Str::snake($key) => $value])
            ->toArray();
    }

    private function formatResponse(StudentFatherInfo $father): array
    {
        return [
            'id'               => $father->id,
            'studentId'        => $father->student_id,
            'studentName'      => $father->student?->first_name.' '. $father->student->last_name ?? null, // Assuming there's a relationship defined
            'name'             => $father->name,
            'email'            => $father->email,
            'phoneNumber'      => $father->phone_number,
            'occupation'       => $father->occupation,
            'temporaryAddress' => $father->temporary_address,
            'permanentAddress' => $father->permanent_address,
            'nationality'      => $father->nationality,
            'monthlyIncome'    => $father->monthly_income,
            'createdAt'        => $father->created_at?->toIso8601String(),
            'updatedAt'        => $father->updated_at?->toIso8601String(),
        ];
    }

    public function index()
    {
        $fathers = StudentFatherInfo::all()->map(fn($father) => $this->formatResponse($father));
        return response()->json($fathers);
    }

    public function store(Request $request)
    {
        $data = $this->convertCamelToSnake($request->all());

        $validated = validator($data, [
            'student_id'        => 'required|exists:student_personal_info,id',
            'name'              => 'nullable|string|max:100',
            'email'             => 'nullable|string|email|max:100',
            'phone_number'      => 'nullable|string|max:20',
            'occupation'        => 'nullable|string|max:100',
            'temporary_address' => 'nullable|string|max:255',
            'permanent_address' => 'nullable|string|max:255',
            'nationality'       => 'nullable|string|max:50',
            'monthly_income'    => 'nullable|numeric',
        ])->validate();

        $father = StudentFatherInfo::create($validated);

        return response()->json([
            'message' => 'Father info created successfully',
            'data'    => $this->formatResponse($father)
        ], 201);
    }

    public function show($id)
    {
        $father = StudentFatherInfo::find($id);
        if (!$father) {
            return response()->json(['message' => 'Father info not found'], 404);
        }
        return response()->json($this->formatResponse($father));
    }

    public function update(Request $request, $id)
    {
        $father = StudentFatherInfo::find($id);
        if (!$father) {
            return response()->json(['message' => 'Father info not found'], 404);
        }

        $data = $this->convertCamelToSnake($request->all());

        $validated = validator($data, [
            'student_id'        => 'sometimes|required|exists:student_personal_info,id',
            'name'              => 'nullable|string|max:100',
            'email'             => 'nullable|string|email|max:100',
            'phone_number'      => 'nullable|string|max:20',
            'occupation'        => 'nullable|string|max:100',
            'temporary_address' => 'nullable|string|max:255',
            'permanent_address' => 'nullable|string|max:255',
            'nationality'       => 'nullable|string|max:50',
            'monthly_income'    => 'nullable|numeric',
        ])->validate();

        $father->update($validated);

        return response()->json([
            'message' => 'Father info updated successfully',
            'data'    => $this->formatResponse($father)
        ]);
    }

    public function destroy($id)
    {
        $father = StudentFatherInfo::find($id);
        if (!$father) {
            return response()->json(['message' => 'Father info not found'], 404);
        }

        $father->delete();

        return response()->json(['message' => 'Father info deleted successfully']);
    }
}
