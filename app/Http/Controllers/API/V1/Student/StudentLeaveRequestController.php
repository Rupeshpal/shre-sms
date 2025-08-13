<?php

namespace App\Http\Controllers\API\V1\Student;

use App\Http\Controllers\Controller;
use App\Models\Student\StudentLeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StudentLeaveRequestController extends Controller
{
    private function convertCamelToSnake(array $input): array
    {
        return collect($input)
            ->mapWithKeys(fn($value, $key) => [Str::snake($key) => $value])
            ->toArray();
    }

    private function formatResponse(StudentLeaveRequest $leave): array
    {

        return [
            'id' => $leave->id,
            'userId' => $leave->user_id,
            'leaveType' => $leave->leave_type,
            'leaveDate' => $leave->leave_date ? \Carbon\Carbon::parse($leave->leave_date)->toIso8601String() : null,
            'endDate' => $leave->end_date ? \Carbon\Carbon::parse($leave->end_date)->toIso8601String() : null,
            'noOfDays' => $leave->no_of_days,
            'status' => $leave->status,
            'remarks' => $leave->remarks,
            'approverId' => $leave->approver_id,
            'decisionDate' => $leave->decision_date ? \Carbon\Carbon::parse($leave->decision_date)->toIso8601String() : null,
            'createdAt' => $leave->created_at ? \Carbon\Carbon::parse($leave->created_at)->toIso8601String() : null,
            'updatedAt' => $leave->updated_at ? \Carbon\Carbon::parse($leave->updated_at)->toIso8601String() : null,
        ];


    }

    public function index()
    {
        $leaves = StudentLeaveRequest::all()->map(fn($leave) => $this->formatResponse($leave));
        return response()->json($leaves);
    }

    public function store(Request $request)
    {
        $data = $this->convertCamelToSnake($request->all());

        $validated = validator($data, [
            'user_id' => 'required|exists:users,id',
            'leave_type' => 'required|in:sick,casual,earned,maternity,other',
            'leave_date' => 'required|date',
            'end_date' => 'nullable|date',
            'no_of_days' => 'nullable|integer',
            'status' => 'nullable|in:pending,approved,rejected',
            'remarks' => 'nullable|string',
            'approver_id' => 'nullable|exists:users,id',
            'decision_date' => 'nullable|date',
        ])->validate();

        $leave = StudentLeaveRequest::create($validated);

        return response()->json([
            'message' => 'Leave request created successfully',
            'data' => $this->formatResponse($leave),
        ], 201);
    }

    public function show($id)
    {
        $leave = StudentLeaveRequest::find($id);
        if (!$leave) {
            return response()->json(['message' => 'Leave request not found'], 404);
        }
        return response()->json($this->formatResponse($leave));
    }

    public function update(Request $request, $id)
    {
        $leave = StudentLeaveRequest::find($id);
        if (!$leave) {
            return response()->json(['message' => 'Leave request not found'], 404);
        }

        $data = $this->convertCamelToSnake($request->all());

        $validated = validator($data, [
            'user_id' => 'sometimes|required|exists:users,id',
            'leave_type' => 'sometimes|required|in:sick,casual,earned,maternity,other',
            'leave_date' => 'sometimes|required|date',
            'end_date' => 'nullable|date',
            'no_of_days' => 'nullable|integer',
            'status' => 'nullable|in:pending,approved,rejected',
            'remarks' => 'nullable|string',
            'approver_id' => 'nullable|exists:users,id',
            'decision_date' => 'nullable|date',
        ])->validate();

        $leave->update($validated);

        return response()->json([
            'message' => 'Leave request updated successfully',
            'data' => $this->formatResponse($leave),
        ]);
    }

    public function destroy($id)
    {
        $leave = StudentLeaveRequest::find($id);
        if (!$leave) {
            return response()->json(['message' => 'Leave request not found'], 404);
        }

        $leave->delete();

        return response()->json(['message' => 'Leave request deleted successfully']);
    }
}
