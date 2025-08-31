<?php

namespace App\Http\Controllers\API\V1\Student;

use App\Http\Controllers\Controller;
use App\Models\Student\StudentSibling;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Exception;

class StudentSiblingController extends Controller
{
    private function convertCamelToSnake(array $input): array
    {
        return collect($input)
            ->mapWithKeys(fn($value, $key) => [Str::snake($key) => $value])
            ->toArray();
    }

    private function formatResponse(StudentSibling $sibling): array
    {
        return [
            'id'           => $sibling->id,
            'studentId'    => $sibling->student_id,
            'studentName'  => $sibling->student?->first_name . ' ' . $sibling->student?->last_name ?? null,
            'name'         => $sibling->name,
            'admissionNo'  => $sibling->admission_no,
            'sectionId'    => $sibling->section,
            'sectionName'  => $sibling->section,
            'rollNo'       => $sibling->roll_no,
            'createdAt'    => $sibling->created_at?->toIso8601String(),
            'updatedAt'    => $sibling->updated_at?->toIso8601String(),
        ];
    }

    public function index()
    {
        try {
            $siblings = StudentSibling::all()->map(fn($sibling) => $this->formatResponse($sibling));
            return response()->json(['status' => true, 'data' => $siblings], 200);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $siblingsData = $request->all();

            if (!is_array($siblingsData)) {
                return response()->json(['status' => false, 'message' => 'Invalid input format, expected an array'], 422);
            }

            $createdSiblings = [];

            foreach ($siblingsData as $siblingData) {
                $data = $this->convertCamelToSnake($siblingData);

                $validated = validator($data, [
                    'student_id'   => 'required|exists:student_personal_info,id',
                    'name'         => 'nullable|string|max:100',
                    'section'      => 'nullable|integer|max:50',
                    'roll_no'      => 'nullable|string|max:20',
                    'admission_no' => 'nullable|string|max:50',
                ])->validate();

                if (empty($validated['admission_no'])) {
                    $validated['admission_no'] = $this->generateUniqueAdmissionNumber();
                } else {
                    if ($this->admissionNumberExists($validated['admission_no'])) {
                        return response()->json([
                            'status' => false,
                            'message' => "The admission number {$validated['admission_no']} already exists in student records."
                        ], 422);
                    }
                }

                $sibling = StudentSibling::create($validated);
                $createdSiblings[] = $this->formatResponse($sibling);
            }

            return response()->json([
                'status'  => true,
                'message' => 'Siblings created successfully',
                'data'    => $createdSiblings
            ], 201);

        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $sibling = StudentSibling::find($id);
            if (!$sibling) {
                return response()->json(['status' => false, 'message' => 'Sibling not found'], 404);
            }
            return response()->json(['status' => true, 'data' => $this->formatResponse($sibling)], 200);
        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $sibling = StudentSibling::find($id);
            if (!$sibling) {
                return response()->json(['status' => false, 'message' => 'Sibling not found'], 404);
            }

            $data = $this->convertCamelToSnake($request->all());

            $validated = validator($data, [
                'student_id'   => 'sometimes|required|exists:student_personal_info,id',
                'name'         => 'nullable|string|max:100',
                'admission_no' => 'nullable|string|max:50',
                'section'      => 'nullable|string|max:50',
                'roll_no'      => 'nullable|string|max:20',
            ])->validate();

            if (!empty($validated['admission_no'])
                && $validated['admission_no'] !== $sibling->admission_no
                && $this->admissionNumberExists($validated['admission_no'])) {
                return response()->json(['status' => false, 'message' => 'The admission number already exists in student records.'], 422);
            }

            $sibling->update($validated);

            return response()->json([
                'status'  => true,
                'message' => 'Sibling updated successfully',
                'data'    => $this->formatResponse($sibling)
            ], 200);

        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $sibling = StudentSibling::find($id);
            if (!$sibling) {
                return response()->json(['status' => false, 'message' => 'Sibling not found'], 404);
            }

            $sibling->delete();

            return response()->json(['status' => true, 'message' => 'Sibling deleted successfully'], 200);

        } catch (Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    private function generateUniqueAdmissionNumber($length = 8)
    {
        do {
            $admissionNumber = Str::upper(Str::random($length));
        } while ($this->admissionNumberExists($admissionNumber));

        return $admissionNumber;
    }

    private function admissionNumberExists(string $admissionNumber): bool
    {
        return DB::table('student_personal_info')
            ->where('admission_number', $admissionNumber)
            ->exists();
    }
}
