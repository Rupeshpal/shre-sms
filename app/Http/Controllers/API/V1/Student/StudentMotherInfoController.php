<?php

namespace App\Http\Controllers\API\V1\Student;

use App\Http\Controllers\Controller;
use App\Models\Student\StudentMotherInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StudentMotherInfoController extends Controller
{
    // Convert camelCase input keys to snake_case for validation and model
    private function convertCamelToSnake(array $input): array
    {
        return collect($input)
            ->mapWithKeys(fn($value, $key) => [Str::snake($key) => $value])
            ->toArray();
    }

    // Format the response with camelCase keys
    private function formatResponse(StudentMotherInfo $mother): array
    {
        return [
            'id'               => $mother->mother_id,
            'studentId'        => $mother->student_id,
            'studentName'      => $mother->student?->first_name . ' ' . $mother->student?->last_name ?? null,
            'name'             => $mother->name,
            'email'            => $mother->email,
            'phoneNumber'      => $mother->phone_number,
            'occupation'       => $mother->occupation,
            'temporaryAddress' => $mother->temporary_address,
            'permanentAddress' => $mother->permanent_address,
            'nationality'      => $mother->nationality,
            'monthlyIncome'    => $mother->monthly_income,
            'createdAt'        => $mother->created_at?->toIso8601String(),
            'updatedAt'        => $mother->updated_at?->toIso8601String(),
        ];
    }

    public function index()
    {
        $mothers = StudentMotherInfo::all()->map(fn($mother) => $this->formatResponse($mother));
        return response()->json($mothers);
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

        $mother = StudentMotherInfo::create($validated);

        return response()->json([
            'message' => 'Mother info created successfully',
            'data'    => $this->formatResponse($mother),
        ], 201);
    }

    public function show($id)
    {
        $mother = StudentMotherInfo::find($id);
        if (!$mother) {
            return response()->json(['message' => 'Mother info not found'], 404);
        }

        return response()->json($this->formatResponse($mother));
    }

    public function update(Request $request, $id)
    {
        $mother = StudentMotherInfo::find($id);
        if (!$mother) {
            return response()->json(['message' => 'Mother info not found'], 404);
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

        $mother->update($validated);

        return response()->json([
            'message' => 'Mother info updated successfully',
            'data'    => $this->formatResponse($mother),
        ]);
    }

    public function destroy($id)
    {
        $mother = StudentMotherInfo::find($id);
        if (!$mother) {
            return response()->json(['message' => 'Mother info not found'], 404);
        }

        $mother->delete();

        return response()->json(['message' => 'Mother info deleted successfully']);
    }
}
