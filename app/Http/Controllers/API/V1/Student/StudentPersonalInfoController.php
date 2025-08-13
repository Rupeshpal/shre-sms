<?php

namespace App\Http\Controllers\Api\V1\Student;
use App\Enums\StudentStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Student\StudentPersonalInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Exception;

class StudentPersonalInfoController extends Controller
{
    private function convertCamelToSnake(array $input): array
    {
        return collect($input)->mapWithKeys(fn($value, $key) => [Str::snake($key) => $value])->toArray();
    }

    private function formatResponse(StudentPersonalInfo $student): array
    {
        return [
            'id' => $student->id,
            'academicYear' => $student->academic_year,
            'admissionNumber' => $student->admission_number,
            'admissionDate' => $student->admission_date,
            'rollNo' => $student->roll_no,
            'status' => $student->status,
            'firstName' => $student->first_name,
            'lastName' => $student->last_name,
            'class' => [
                'classId' => $student->class_id,
                'className' => $student->class->class_name ?? null
            ],
            'section' => [
                'sectionId' => $student->section_id,
                'sectionName' => $student->section->section_name ?? null
            ],

            'gender' => $student->gender,
            'dateOfBirth' => $student->date_of_birth,
            'bloodGroup' => $student->blood_group,
            'house' => $student->house,
            'motherTongue' => $student->mother_tongue,
            'contactNumber' => $student->contact_number,
            'email' => $student->email,
            'createdAt' => $student->created_at?->toIso8601String(),
            'updatedAt' => $student->updated_at?->toIso8601String(),
        ];
    }

    public function index()
    {
        $students = StudentPersonalInfo::with(['class', 'section'])->get()
            ->map(fn($student) => $this->formatResponse($student));

        return response()->json([
            'status' => true,
            'message' => 'Student list fetched successfully',
            'data' => $students,
        ]);
    }

    public function store(Request $request)
    {
        $input = $this->convertCamelToSnake($request->all());

        $validator = Validator::make($input, [
            'academic_year' => 'required|string|max:20',
            'admission_number' => 'required|string|max:50',
            'admission_date' => 'required|date',
            'roll_no' => 'required|string|max:20',
            'status' => 'required|string|in:' . implode(',', StudentStatusEnum::values()),
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'class_id' => 'required|integer|exists:classes,id',
            'section_id' => 'required|integer|exists:sections,id',
            'gender' => 'required|string|max:40',
            'date_of_birth' => 'required|date',
            'blood_group' => 'nullable|string|max:5',
            'house' => 'nullable|string|max:50',
            'mother_tongue' => 'nullable|string|max:50',
            'contact_number' => 'nullable|string|max:20',
            'email' => 'nullable|string|email|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $student = StudentPersonalInfo::create($validator->validated());

            return response()->json([
                'status' => true,
                'message' => 'Student personal info created successfully',
                'data' => $this->formatResponse($student->load(['class', 'section'])),
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        $student = StudentPersonalInfo::with(['class', 'section'])->find($id);

        if (!$student) {
            return response()->json([
                'status' => false,
                'message' => 'Student not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Student fetched successfully',
            'data' => $this->formatResponse($student),
        ]);
    }

    public function update(Request $request, $id)
    {
        $student = StudentPersonalInfo::find($id);

        if (!$student) {
            return response()->json([
                'status' => false,
                'message' => 'Student not found',
            ], 404);
        }

        $input = $this->convertCamelToSnake($request->all());

        $validator = Validator::make($input, [
            'academic_year' => 'nullable|string|max:20',
            'admission_number' => 'nullable|string|max:50',
            'admission_date' => 'nullable|date',
            'roll_no' => 'nullable|string|max:20',
            'status' => 'nullable|string|in:' . implode(',', StudentStatusEnum::values()),
            'first_name' => 'sometimes|required|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'class_id' => 'required|integer|exists:classes,id',
            'section_id' => 'required|integer|exists:sections,id',
            'gender' => 'nullable|string|max:40',
            'date_of_birth' => 'nullable|date',
            'blood_group' => 'nullable|string|max:5',
            'house' => 'nullable|string|max:50',
            'mother_tongue' => 'nullable|string|max:50',
            'contact_number' => 'nullable|string|max:20',
            'email' => 'nullable|string|email|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $student->update($validator->validated());

            return response()->json([
                'status' => true,
                'message' => 'Student personal info updated successfully',
                'data' => $this->formatResponse($student->fresh(['class', 'section'])),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        $student = StudentPersonalInfo::find($id);

        if (!$student) {
            return response()->json([
                'status' => false,
                'message' => 'Student not found',
            ], 404);
        }

        $student->delete();

        return response()->json([
            'status' => true,
            'message' => 'Student personal info deleted successfully',
        ]);
    }
}
