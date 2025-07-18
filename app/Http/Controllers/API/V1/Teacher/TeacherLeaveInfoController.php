<?php

namespace App\Http\Controllers\Api\V1\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Teacher\TeacherLeaveInfo;
use Illuminate\Http\Request;

class TeacherLeaveInfoController extends Controller
{
    public function index()
    {
        return response()->json(TeacherLeaveInfo::all());
    }

    public function show($id)
    {
        $leaveInfo = TeacherLeaveInfo::find($id);
        if (! $leaveInfo) {
            return response()->json(['message' => 'Leave info not found'], 404);
        }
        return response()->json($leaveInfo);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'teacher_id'        => 'required|exists:teachers,id',
            'medical_leaves'    => 'nullable|integer',
            'maternity_leaves'  => 'nullable|integer',
            'casual_leaves'     => 'nullable|integer',
            'sick_leaves'       => 'nullable|integer',
        ]);

        $leaveInfo = TeacherLeaveInfo::create($validated);

        return response()->json([
            'message' => 'Leave info created successfully',
            'data'    => $leaveInfo
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $leaveInfo = TeacherLeaveInfo::find($id);
        if (! $leaveInfo) {
            return response()->json(['message' => 'Leave info not found'], 404);
        }

        $validated = $request->validate([
            'teacher_id'        => 'sometimes|required|exists:teachers,id',
            'medical_leaves'    => 'nullable|integer',
            'maternity_leaves'  => 'nullable|integer',
            'casual_leaves'     => 'nullable|integer',
            'sick_leaves'       => 'nullable|integer',
        ]);

        $leaveInfo->update($validated);

        return response()->json([
            'message' => 'Leave info updated successfully',
            'data'    => $leaveInfo
        ]);
    }

    public function destroy($id)
    {
        $leaveInfo = TeacherLeaveInfo::find($id);
        if (! $leaveInfo) {
            return response()->json(['message' => 'Leave info not found'], 404);
        }

        $leaveInfo->delete();

        return response()->json(['message' => 'Leave info deleted successfully']);
    }
}
