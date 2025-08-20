<?php

namespace App\Http\Controllers\API\V1\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Teacher\Teacher;
use App\Models\Teacher\TeacherAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::with('assignments')->get();

        return response()->json([
            'data' => $teachers->map(fn ($teacher) => $this->formatResponse($teacher))
        ]);
    }

    public function show($id)
    {
        $teacher = Teacher::with('assignments')->find($id);
        if (!$teacher) {
            return response()->json(['message' => 'Teacher not found'], 404);
        }

        return response()->json([
            'data' => $this->formatResponse($teacher)
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'teacherCode'      => 'nullable|string|max:20',
                'firstName'        => 'required|string|max:100',
                'lastName'         => 'required|string|max:100',
                'email'            => 'nullable|string|email|max:150|unique:teachers,email',
                'primaryContact'   => 'nullable|string|max:20',
                'gender'           => 'nullable|in:Male,Female,Other',
                'bloodGroup'       => 'nullable|string|max:5',
                'dateOfBirth'      => 'nullable|date',
                'dateOfJoining'    => 'nullable|date',
                'maritalStatus'    => 'nullable|in:Single,Married,Other',
                'qualification'    => 'nullable|string|max:150',
                'workExperience'   => 'nullable|string|max:50',
                'fatherName'       => 'nullable|string|max:100',
                'motherName'       => 'nullable|string|max:100',
                'house'            => 'nullable|string|max:50',
                'motherTongue'     => 'nullable|string|max:50',
                'status'           => 'nullable|boolean',

                // assignments
                'assignments'                  => 'nullable|array',
                'assignments.*.subject_id'     => 'required|integer',
                'assignments.*.class_id'       => 'required|integer',
                'assignments.*.section_id'     => 'required|integer',
            ]);

            $teacherCode = $validated['teacherCode'] ?? $this->generateTeacherCode();

            $data = [
                'teacher_code'    => $teacherCode,
                'first_name'      => $validated['firstName'],
                'last_name'       => $validated['lastName'],
                'email'           => $validated['email'] ?? null,
                'primary_contact' => $validated['primaryContact'] ?? null,
                'gender'          => $validated['gender'] ?? null,
                'blood_group'     => $validated['bloodGroup'] ?? null,
                'date_of_birth'   => $validated['dateOfBirth'] ?? null,
                'date_of_joining' => $validated['dateOfJoining'] ?? null,
                'marital_status'  => $validated['maritalStatus'] ?? null,
                'qualification'   => $validated['qualification'] ?? null,
                'work_experience' => $validated['workExperience'] ?? null,
                'father_name'     => $validated['fatherName'] ?? null,
                'mother_name'     => $validated['motherName'] ?? null,
                'house'           => $validated['house'] ?? null,
                'mother_tongue'   => $validated['motherTongue'] ?? null,
                'status'          => $validated['status'] ?? true,
            ];

            $teacher = Teacher::create($data);

            // save assignments
            if (!empty($validated['assignments'])) {
                foreach ($validated['assignments'] as $assignment) {
                    $teacher->assignments()->create($assignment);
                }
            }

            return response()->json([
                'status'=>true,
                'message' => 'Teacher created successfully',
                'data'    => $this->formatResponse($teacher->load('assignments')),
            ], 201);

        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (QueryException $e) {
            Log::error('DB Error: ' . $e->getMessage());
            return response()->json(['message' => 'Database error occurred.'], 500);
        } catch (\Exception $e) {
            Log::error('Unexpected Error: ' . $e->getMessage());
            return response()->json(['message' => 'Something went wrong.'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $teacher = Teacher::find($id);
        if (!$teacher) {
            return response()->json(['message' => 'Teacher not found'], 404);
        }

        try {
            $validated = $request->validate([
                'teacherCode'      => 'nullable|string|max:20',
                'firstName'        => 'sometimes|required|string|max:100',
                'lastName'         => 'sometimes|required|string|max:100',
                'email'            => 'nullable|string|email|max:150|unique:teachers,email,' . $id,
                'primaryContact'   => 'nullable|string|max:20',
                'gender'           => 'nullable|in:Male,Female,Other',
                'bloodGroup'       => 'nullable|string|max:5',
                'dateOfBirth'      => 'nullable|date',
                'dateOfJoining'    => 'nullable|date',
                'maritalStatus'    => 'nullable|in:Single,Married,Other',
                'qualification'    => 'nullable|string|max:150',
                'workExperience'   => 'nullable|string|max:50',
                'fatherName'       => 'nullable|string|max:100',
                'motherName'       => 'nullable|string|max:100',
                'house'            => 'nullable|string|max:50',
                'motherTongue'     => 'nullable|string|max:50',
                'status'           => 'nullable|boolean',

                // assignments
                'assignments'                  => 'nullable|array',
                'assignments.*.subject_id'     => 'required|integer',
                'assignments.*.class_id'       => 'required|integer',
                'assignments.*.section_id'     => 'required|integer',
            ]);

            $data = [
                'teacher_code'    => $validated['teacherCode'] ?? $teacher->teacher_code,
                'first_name'      => $validated['firstName'] ?? $teacher->first_name,
                'last_name'       => $validated['lastName'] ?? $teacher->last_name,
                'email'           => $validated['email'] ?? $teacher->email,
                'primary_contact' => $validated['primaryContact'] ?? $teacher->primary_contact,
                'gender'          => $validated['gender'] ?? $teacher->gender,
                'blood_group'     => $validated['bloodGroup'] ?? $teacher->blood_group,
                'date_of_birth'   => $validated['dateOfBirth'] ?? $teacher->date_of_birth,
                'date_of_joining' => $validated['dateOfJoining'] ?? $teacher->date_of_joining,
                'marital_status'  => $validated['maritalStatus'] ?? $teacher->marital_status,
                'qualification'   => $validated['qualification'] ?? $teacher->qualification,
                'work_experience' => $validated['workExperience'] ?? $teacher->work_experience,
                'father_name'     => $validated['fatherName'] ?? $teacher->father_name,
                'mother_name'     => $validated['motherName'] ?? $teacher->mother_name,
                'house'           => $validated['house'] ?? $teacher->house,
                'mother_tongue'   => $validated['motherTongue'] ?? $teacher->mother_tongue,
                'status'          => $validated['status'] ?? $teacher->status,
            ];

            $teacher->update($data);

            // replace assignments
            if ($request->has('assignments')) {
                $teacher->assignments()->delete();
                foreach ($validated['assignments'] as $assignment) {
                    $teacher->assignments()->create($assignment);
                }
            }

            return response()->json([
                'status' =>true,
                'message' => 'Teacher updated successfully',
                'data'    => $this->formatResponse($teacher->load('assignments')),
            ]);

        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Update Error: ' . $e->getMessage());
            return response()->json(['message' => 'Update failed.'], 500);
        }
    }

    public function destroy($id)
    {
        $teacher = Teacher::find($id);
        if (!$teacher) {
            return response()->json(['message' => 'Teacher not found'], 404);
        }

        $teacher->delete();

        return response()->json([
            'status'=>true,
            'message' => 'Teacher deleted successfully'
        ]);
    }

    protected function formatResponse($teacher)
    {
        return [
            'id'              => $teacher->id,
            'teacherCode'     => $teacher->teacher_code,
            'firstName'       => $teacher->first_name,
            'lastName'        => $teacher->last_name,
            'email'           => $teacher->email,
            'primaryContact'  => $teacher->primary_contact,
            'gender'          => $teacher->gender,
            'bloodGroup'      => $teacher->blood_group,
            'dateOfBirth'     => $teacher->date_of_birth,
            'dateOfJoining'   => $teacher->date_of_joining,
            'maritalStatus'   => $teacher->marital_status,
            'qualification'   => $teacher->qualification,
            'workExperience'  => $teacher->work_experience,
            'fatherName'      => $teacher->father_name,
            'motherName'      => $teacher->mother_name,
            'house'           => $teacher->house,
            'motherTongue'    => $teacher->mother_tongue,
            'status'          => $teacher->status,
            'assignments'     => $teacher->assignments->map(function ($a) {
                return [
                    'subject_id' => $a->subject_id,
                    'class_id'   => $a->class_id,
                    'section_id' => $a->section_id,
                ];
            }),
            'createdAt'       => optional($teacher->created_at)->toIso8601String(),
            'updatedAt'       => optional($teacher->updated_at)->toIso8601String(),
        ];
    }

    protected function generateTeacherCode()
    {
        $prefix = 'TC';
        $lastId = Teacher::max('id') ?? 0;
        $code = $prefix . str_pad($lastId + 1, 5, '0', STR_PAD_LEFT);
        return $code;
    }
}
