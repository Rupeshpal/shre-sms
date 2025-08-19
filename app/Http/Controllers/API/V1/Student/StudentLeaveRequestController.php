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
            'approverName' =>optional($leave->approver)->name,
            'approverEamil' =>optional($leave->approver)->eamil,
            'decisionDate' => $leave->decision_date?->toIso8601String(),
            'createdAt' => $leave->created_at?->toIso8601String(),
            'updatedAt' => $leave->updated_at?->toIso8601String(),
        ];
    }

    public function index()
    {
        try {
            $leaves = StudentLeaveRequest::with(['student', 'class', 'section', 'academic'])
                ->get()
                ->map(fn($leave) => $this->formatResponse($leave));

            return response()->json([
                'status' => true,
                'message' => 'Leave requests fetched successfully',
                'data' => $leaves,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch leave requests',
                'error' => $e->getMessage(),
            ], 500);
        }
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
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $leave = StudentLeaveRequest::create($validator->validated());
            $leave->load(['student','class','section','academic']);

            return response()->json([
                'status' => true,
                'message' => 'Leave request created successfully',
                'data' => $this->formatResponse($leave),
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create leave request',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $leave = StudentLeaveRequest::with(['student', 'class', 'section', 'academic'])->findOrFail($id);

            return response()->json([
                'status' => true,
                'message' => 'Leave request fetched successfully',
                'data' => $this->formatResponse($leave),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Leave request not found',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $leave = StudentLeaveRequest::findOrFail($id);

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
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $leave->update($validator->validated());
            $leave->load(['student','class','section','academic']);

            return response()->json([
                'status' => true,
                'message' => 'Leave request updated successfully',
                'data' => $this->formatResponse($leave),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update leave request',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $leave = StudentLeaveRequest::findOrFail($id);
            $leave->delete();

            return response()->json([
                'status' => true,
                'message' => 'Leave request deleted successfully',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete leave request',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
