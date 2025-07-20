<?php

namespace App\Http\Controllers\Api\V1\Student;

use App\Http\Controllers\Controller;
use App\Models\Student\StudentLeaveRequest;
use Illuminate\Http\Request;

class StudentLeaveRequestController extends Controller
{
    public function index()
    {
        return response()->json(StudentLeaveRequest::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'       => 'required|exists:users,id',
            'leave_type'    => 'required|in:sick,casual,earned,maternity,other',
            'leave_date'    => 'required|date',
            'end_date'      => 'nullable|date',
            'no_of_days'    => 'nullable|integer',
            'status'        => 'nullable|in:pending,approved,rejected',
            'remarks'       => 'nullable|string',
            'approver_id'   => 'nullable|exists:users,id',
            'decision_date' => 'nullable|date',
        ]);

        $leave = StudentLeaveRequest::create($validated);

        return response()->json([
            'message' => 'Leave request created successfully',
            'data'    => $leave
        ], 201);
    }

    public function show($id)
    {
        $leave = StudentLeaveRequest::find($id);
        if (! $leave) {
            return response()->json(['message' => 'Leave request not found'], 404);
        }
        return response()->json($leave);
    }

    public function update(Request $request, $id)
    {
        $leave = StudentLeaveRequest::find($id);
        if (! $leave) {
            return response()->json(['message' => 'Leave request not found'], 404);
        }

        $validated = $request->validate([
            'user_id'       => 'sometimes|required|exists:users,id',
            'leave_type'    => 'sometimes|required|in:sick,casual,earned,maternity,other',
            'leave_date'    => 'sometimes|required|date',
            'end_date'      => 'nullable|date',
            'no_of_days'    => 'nullable|integer',
            'status'        => 'nullable|in:pending,approved,rejected',
            'remarks'       => 'nullable|string',
            'approver_id'   => 'nullable|exists:users,id',
            'decision_date' => 'nullable|date',
        ]);

        $leave->update($validated);

        return response()->json([
            'message' => 'Leave request updated successfully',
            'data'    => $leave
        ]);
    }

    public function destroy($id)
    {
        $leave = StudentLeaveRequest::find($id);
        if (! $leave) {
            return response()->json(['message' => 'Leave request not found'], 404);
        }

        $leave->delete();

        return response()->json(['message' => 'Leave request deleted successfully']);
    }
}
