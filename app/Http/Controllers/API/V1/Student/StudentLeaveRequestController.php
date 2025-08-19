<?php

namespace App\Http\Controllers\API\V1\Student;

use App\Http\Controllers\Controller;
use App\Models\Student\StudentLeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Exception;

class StudentLeaveRequestController extends Controller
{
    private function convertCamelToSnake(array $input): array
    {
        return collect($input)->mapWithKeys(fn($value, $key) => [Str::snake($key) => $value])->toArray();
    }

    private function formatResponse(StudentLeaveRequest $leave): array
    {
        return [
            'id' => $leave->id,
            'studentId' => $leave->student_id,
            'studentName' => optional($leave->student)->first_name . ' ' . optional($leave->student)->last_name,
            'studentRollNo' => optional($leave->student)->roll_no,
            'studentAdmissionNo' => optional($leave->student)->admission_number,
            'leaveType' => $leave->leave_type,
            'academicYearId' => $leave->academic_year_id,
            'class' => [
                'classId' => $leave->class_id,
                'className' => optional($leave->class)->class_name
            ],
            'section' => [
                'sectionId' => $leave->section_id,
                'sectionName' => optional($leave->section)->section_name
            ],
            'leaveDate' => $leave->leave_date?->toIso8601String(),
            'endDate' => $leave->end_date?->toIso8601String(),
            'noOfDays' => $leave->no_of_days,
            'status' => $leave->status,
            'remarks' => $leave->remarks,
            'approverId' => $leave->approver_id,
            'decisionDate' => $leave->decision_date?->toIso8601String(),
            'createdAt' => $leave->created_at?->toIso8601String(),
            'updatedAt' => $leave->updated_at?->toIso8601String(),
        ];
    }

    public function index()
    {
        $leaves = StudentLeaveRequest::with(['student', 'class', 'section', 'academic'])
            ->get()
            ->map(fn($leave) => $this->formatResponse($leave));

        return response()->json([
            'status' => true,
            'message' => 'Leave requests fetched successfully',
            'data' => $leaves,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->convertCamelToSnake($request->all());

        $validator = Validator::make($data, [
            'student_id' => 'required|exists:student_personal_info,id',
            'leave_type' => 'required|in:sick,casual,earned,maternity,other',
            'leave_date' => 'required|date',
            'end_date' => 'nullable|date',
            'no_of_days' => 'nullable|integer',
            'status' => 'nullable|in:pending,approved,rejected',
            'remarks' => 'nullable|string',
            'approver_id' => 'nullable|exists:users,id',
            'decision_date' => 'nullable|date',
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'academic_year_id' => 'required|exists:academic_years,id',
        ])->validate();

        $leave = StudentLeaveRequest::create($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Leave request created successfully',
            'data' => $this->formatResponse($leave->load(['student','class','section','academic'])),
        ], 201);
    }

    public function show($id)
    {
        $leave = StudentLeaveRequest::with(['student', 'class', 'section', 'academic'])->find($id);

        if (!$leave) {
            return response()->json([
                'status' => false,
                'message' => 'Leave request not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Leave request fetched successfully',
            'data' => $this->formatResponse($leave),
        ]);
    }

    public function update(Request $request, $id)
    {
        $leave = StudentLeaveRequest::find($id);
        if (!$leave) {
            return response()->json([
                'status' => false,
                'message' => 'Leave request not found',
            ], 404);
        }

        $data = $this->convertCamelToSnake($request->all());

        $validator = Validator::make($data, [
            'student_id' => 'sometimes|required|exists:student_personal_info,id',
            'leave_type' => 'sometimes|required|in:sick,casual,earned,maternity,other',
            'leave_date' => 'sometimes|required|date',
            'end_date' => 'nullable|date',
            'no_of_days' => 'nullable|integer',
            'status' => 'nullable|in:pending,approved,rejected',
            'remarks' => 'nullable|string',
            'approver_id' => 'nullable|exists:users,id',
            'decision_date' => 'nullable|date',
            'class_id' => 'sometimes|required|exists:classes,id',
            'section_id' => 'sometimes|required|exists:sections,id',
            'academic_year_id' => 'sometimes|required|exists:academic_years,id',
        ])->validate();

        $leave->update($validator->validated());

        return response()->json([
            'status' => true,
            'message' => 'Leave request updated successfully',
            'data' => $this->formatResponse($leave->fresh(['student','class','section','academic'])),
        ]);
    }

    public function destroy($id)
    {
        $leave = StudentLeaveRequest::find($id);
        if (!$leave) {
            return response()->json([
                'status' => false,
                'message' => 'Leave request not found',
            ], 404);
        }

        $leave->delete();

        return response()->json([
            'status' => true,
            'message' => 'Leave request deleted successfully',
        ]);
    }
}
