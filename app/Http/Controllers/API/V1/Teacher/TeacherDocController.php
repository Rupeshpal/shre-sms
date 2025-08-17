<?php

namespace App\Http\Controllers\API\V1\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Teacher\TeacherDoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class TeacherDocController extends Controller
{
    private function convertCamelToSnake(array $input): array
    {
        return collect($input)
            ->mapWithKeys(fn($value, $key) =>
                [strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $key)) => $value]
            )
            ->toArray();
    }

    private function formatResponse(TeacherDoc $teacherDetail): array
    {
        return [
            'id'                    => $teacherDetail->id,
            'teacherId'             => $teacherDetail->teacher_id,
            'teacherName'           => optional($teacherDetail->teacher)->first_name . ' ' . optional($teacherDetail->teacher)->last_name,
            'joiningLetter'         => $teacherDetail->joining_letter ? asset('storage/' . $teacherDetail->joining_letter) : null,
            'experienceCertificate' => $teacherDetail->experience_certificate ? asset('storage/' . $teacherDetail->experience_certificate) : null,
            'characterCertificate'  => $teacherDetail->character_certificate ? asset('storage/' . $teacherDetail->character_certificate) : null,
            'mainSheets'            => $teacherDetail->main_sheets ? asset('storage/' . $teacherDetail->main_sheets) : null,
            'medicalConditionFile'  => $teacherDetail->medical_condition_file ? asset('storage/' . $teacherDetail->medical_condition_file) : null,
            'medicalStatus'         => $teacherDetail->medical_status,
            'allergies'             => $teacherDetail->allergies,
            'medication'            => $teacherDetail->medication,
            'createdAt'             => $teacherDetail->created_at?->toIso8601String(),
            'updatedAt'             => $teacherDetail->updated_at?->toIso8601String(),
        ];
    }

    public function index()
    {
        $details = TeacherDoc::all()->map(fn($d) => $this->formatResponse($d));
        return response()->json(['status' => true, 'data' => $details]);
    }

    public function store(Request $request)
    {
        try {
            // exclude file fields before validation
            $data = $this->convertCamelToSnake(
                $request->except([
                    'joiningLetter',
                    'experienceCertificate',
                    'characterCertificate',
                    'mainSheets',
                    'medicalConditionFile'
                ])
            );

            $validated = validator($data, [
                'teacher_id'             => 'required|integer',
                'medical_status'         => 'required|string|in:Good,Bad,Others',
                'allergies'              => 'nullable|string',
                'medication'             => 'nullable|string',
            ])->validate();

            // Handle file uploads
            foreach ([
                'joining_letter' => 'joiningLetter',
                'experience_certificate' => 'experienceCertificate',
                'character_certificate' => 'characterCertificate',
                'main_sheets' => 'mainSheets',
                'medical_condition_file' => 'medicalConditionFile',
            ] as $snake => $camel) {
                if ($request->hasFile($camel)) {
                    $validated[$snake] = $request->file($camel)->store('teacher_documents', 'public');
                }
            }

            $teacherDetail = TeacherDoc::create($validated);

            return response()->json([
                'status'  => true,
                'message' => 'Teacher details created successfully',
                'data'    => $this->formatResponse($teacherDetail)
            ], 201);

        } catch (ValidationException $e) {
            return response()->json(['status' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['status' => false, 'message' => 'Something went wrong'], 500);
        }
    }

    public function show($id)
    {
        $teacherDetail = TeacherDoc::find($id);
        if (!$teacherDetail) {
            return response()->json(['status' => false, 'message' => 'Not found'], 404);
        }
        return response()->json(['status' => true, 'data' => $this->formatResponse($teacherDetail)]);
    }

    public function update(Request $request, $id)
    {
        $teacherDetail = TeacherDoc::find($id);
        if (!$teacherDetail) {
            return response()->json(['status' => false, 'message' => 'Not found'], 404);
        }

        try {
            // exclude file fields before validation
            $data = $this->convertCamelToSnake(
                $request->except([
                    'joiningLetter',
                    'experienceCertificate',
                    'characterCertificate',
                    'mainSheets',
                    'medicalConditionFile'
                ])
            );

            $validated = validator($data, [
                'teacher_id'             => 'required|integer',
                'medical_status'         => 'required|string|in:Good,Bad,Others',
                'allergies'              => 'nullable|string',
                'medication'             => 'nullable|string',
            ])->validate();

            // Handle file uploads
            foreach ([
                'joining_letter' => 'joiningLetter',
                'experience_certificate' => 'experienceCertificate',
                'character_certificate' => 'characterCertificate',
                'main_sheets' => 'mainSheets',
                'medical_condition_file' => 'medicalConditionFile',
            ] as $snake => $camel) {
                if ($request->hasFile($camel)) {
                    $validated[$snake] = $request->file($camel)->store('teacher_documents', 'public');
                }
            }

            $teacherDetail->update($validated);

            return response()->json([
                'status'  => true,
                'message' => 'Teacher details updated successfully',
                'data'    => $this->formatResponse($teacherDetail)
            ]);

        } catch (ValidationException $e) {
            return response()->json(['status' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['status' => false, 'message' => 'Something went wrong'], 500);
        }
    }

    public function destroy($id)
    {
        $teacherDetail = TeacherDoc::find($id);
        if (!$teacherDetail) {
            return response()->json(['status' => false, 'message' => 'Not found'], 404);
        }

        $teacherDetail->delete();
        return response()->json(['status' => true, 'message' => 'Teacher details deleted successfully']);
    }
}
