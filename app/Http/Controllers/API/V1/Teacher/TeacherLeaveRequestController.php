<?php

namespace App\Http\Controllers\Api\V1\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Teacher\TeacherLeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class TeacherLeaveRequestController extends Controller
{
    public function index()
    {
        try {
            $leaves = TeacherLeaveRequest::all();
            return response()->json([
                'data' => $leaves->map(fn($leave) => $this->formatResponse($leave))
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error fetching leave requests',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $leave = TeacherLeaveRequest::findOrFail($id);
            return response()->json([
                'data' => $this->formatResponse($leave)
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Leave request not found'], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error fetching leave request',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'teacherId'   => 'required|exists:teachers,id',
                'leaveType'   => 'required|in:Medical,Maternity,Casual,Sick,Other',
                'leaveDate'   => 'required|date',
                'endDate'     => 'nullable|date',
                'noOfDays'    => 'nullable|integer',
                'approverId'  => 'nullable|exists:users,id',
                'status'      => 'nullable|in:Pending,Approved,Rejected',
                'remarks'     => 'nullable|string',
            ]);

            $leave = TeacherLeaveRequest::create([
                'teacher_id'   => $validated['teacherId'],
                'leave_type'   => $validated['leaveType'],
                'leave_date'   => $validated['leaveDate'],
                'end_date'     => $validated['endDate'] ?? null,
                'no_of_days'   => $validated['noOfDays'] ?? null,
                'approver_id'  => $validated['approverId'] ?? null,
                'status'       => $validated['status'] ?? 'Pending',
                'remarks'      => $validated['remarks'] ?? null,
            ]);

            return response()->json([
                'message' => 'Leave request created successfully',
                'data'    => $this->formatResponse($leave)
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error creating leave request',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $leave = TeacherLeaveRequest::findOrFail($id);

            $validated = $request->validate([
                'teacherId'   => 'sometimes|required|exists:teachers,id',
                'leaveType'   => 'sometimes|required|in:Medical,Maternity,Casual,Sick,Other',
                'leaveDate'   => 'sometimes|required|date',
                'endDate'     => 'nullable|date',
                'noOfDays'    => 'nullable|integer',
                'approverId'  => 'nullable|exists:users,id',
                'status'      => 'nullable|in:Pending,Approved,Rejected',
                'remarks'     => 'nullable|string',
            ]);

            $leave->update([
                'teacher_id'   => $validated['teacherId'] ?? $leave->teacher_id,
                'leave_type'   => $validated['leaveType'] ?? $leave->leave_type,
                'leave_date'   => $validated['leaveDate'] ?? $leave->leave_date,
                'end_date'     => $validated['endDate'] ?? $leave->end_date,
                'no_of_days'   => $validated['noOfDays'] ?? $leave->no_of_days,
                'approver_id'  => $validated['approverId'] ?? $leave->approver_id,
                'status'       => $validated['status'] ?? $leave->status,
                'remarks'      => $validated['remarks'] ?? $leave->remarks,
            ]);

            return response()->json([
                'message' => 'Leave request updated successfully',
                'data'    => $this->formatResponse($leave)
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Leave request not found'], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error updating leave request',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $leave = TeacherLeaveRequest::findOrFail($id);
            $leave->delete();

            return response()->json(['message' => 'Leave request deleted successfully']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Leave request not found'], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error deleting leave request',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    private function formatResponse($leave)
    {
        return [
            'id'          => $leave->id,
            'teacherId'   => $leave->teacher_id,
            'teacherName' => optional($leave->teacher)->first_name . ' ' . optional($leave->teacher)->last_name,
            'leaveType'   => $leave->leave_type,
            'leaveDate'   => $leave->leave_date,
            'endDate'     => $leave->end_date,
            'noOfDays'    => $leave->no_of_days,
            'approverId'  => $leave->approver_id,
            'approverName' => optional($leave->approver)->name,
            'status'      => $leave->status,
            'remarks'     => $leave->remarks,
            'createdAt'   => optional($leave->created_at)->toIso8601String(),
            'updatedAt'   => optional($leave->updated_at)->toIso8601String(),
        ];
    }
}
