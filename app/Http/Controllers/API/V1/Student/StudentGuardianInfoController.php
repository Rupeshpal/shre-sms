<?php

namespace App\Http\Controllers\Api\V1\Student;

use App\Http\Controllers\Controller;
use App\Models\Student\StudentGuardianInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StudentGuardianInfoController extends Controller
{
    // Convert camelCase input keys to snake_case for DB
    private function convertCamelToSnake(array $input): array
    {
        return collect($input)
            ->mapWithKeys(fn($value, $key) => [Str::snake($key) => $value])
            ->toArray();
    }

    // Format DB model to camelCase keys for API response
    private function formatResponse(StudentGuardianInfo $guardian): array
    {
        return [
            'id'               => $guardian->id,
            'studentId'        => $guardian->student_id,
            'studentName'      => $guardian->student?->first_name . ' ' . $guardian->student?->last_name ?? null, // Assumes relationship
            'name'             => $guardian->name,
            'email'            => $guardian->email,
            'phoneNumber'      => $guardian->phone_number,
            'occupation'       => $guardian->occupation,
            'temporaryAddress' => $guardian->temporary_address,
            'permanentAddress' => $guardian->permanent_address,
            'nationality'      => $guardian->nationality,
            'createdAt'        => $guardian->created_at?->toIso8601String(),
            'updatedAt'        => $guardian->updated_at?->toIso8601String(),
        ];
    }

    public function index()
    {
        $guardians = StudentGuardianInfo::all()->map(fn($g) => $this->formatResponse($g));
        return response()->json($guardians);
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
        ])->validate();

        $guardian = StudentGuardianInfo::create($validated);

        return response()->json([
            'message' => 'Guardian info created successfully',
            'data'    => $this->formatResponse($guardian)
        ], 201);
    }

    public function show($id)
    {
        $guardian = StudentGuardianInfo::find($id);
        if (!$guardian) {
            return response()->json(['message' => 'Guardian info not found'], 404);
        }
        return response()->json($this->formatResponse($guardian));
    }

    public function update(Request $request, $id)
    {
        $guardian = StudentGuardianInfo::find($id);
        if (!$guardian) {
            return response()->json(['message' => 'Guardian info not found'], 404);
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
        ])->validate();

        $guardian->update($validated);

        return response()->json([
            'message' => 'Guardian info updated successfully',
            'data'    => $this->formatResponse($guardian)
        ]);
    }

    public function destroy($id)
    {
        $guardian = StudentGuardianInfo::find($id);
        if (!$guardian) {
            return response()->json(['message' => 'Guardian info not found'], 404);
        }

        $guardian->delete();

        return response()->json(['message' => 'Guardian info deleted successfully']);
    }
}
