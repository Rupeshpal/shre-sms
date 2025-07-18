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
            return response()->json($leaves);
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
            return response()->json($leave);
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
                'teacher_id'   => 'required|exists:teachers,id',
                'leave_type'   => 'required|in:Medical,Maternity,Casual,Sick,Other',
                'leave_date'   => 'required|date',
                'end_date'     => 'nullable|date',
                'no_of_days'   => 'nullable|integer',
                'approver_id'  => 'nullable|exists:users,id',
                'status'       => 'nullable|in:Pending,Approved,Rejected',
                'remarks'      => 'nullable|string',
            ]);

            $leave = TeacherLeaveRequest::create($validated);

            return response()->json([
                'message' => 'Leave request created successfully',
                'data'    => $leave
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
                'teacher_id'   => 'sometimes|required|exists:teachers,id',
                'leave_type'   => 'sometimes|required|in:Medical,Maternity,Casual,Sick,Other',
                'leave_date'   => 'sometimes|required|date',
                'end_date'     => 'nullable|date',
                'no_of_days'   => 'nullable|integer',
                'approver_id'  => 'nullable|exists:users,id',
                'status'       => 'nullable|in:Pending,Approved,Rejected',
                'remarks'      => 'nullable|string',
            ]);

            $leave->update($validated);

            return response()->json([
                'message' => 'Leave request updated successfully',
                'data'    => $leave
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
}
