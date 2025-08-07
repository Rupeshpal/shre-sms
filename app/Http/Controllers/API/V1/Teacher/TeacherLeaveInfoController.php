<?php

namespace App\Http\Controllers\Api\V1\Teacher;
use App\Http\Controllers\Controller;
use App\Models\Teacher\TeacherLeaveInfo;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Exception;

class TeacherLeaveInfoController extends Controller
{
    public function index()
    {
        try {
            $leaveInfos = TeacherLeaveInfo::all();

            return response()->json([
                'data' => $leaveInfos->map(fn ($info) => $this->formatResponse($info)),
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to fetch leave info', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $leaveInfo = TeacherLeaveInfo::find($id);
            if (! $leaveInfo) {
                return response()->json(['message' => 'Leave info not found'], 404);
            }

            return response()->json([
                'data' => $this->formatResponse($leaveInfo),
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error fetching leave info', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'teacherId'       => 'required|exists:teachers,id',
                'medicalLeaves'   => 'nullable|integer',
                'maternityLeaves' => 'nullable|integer',
                'casualLeaves'    => 'nullable|integer',
                'sickLeaves'      => 'nullable|integer',
            ]);

            $data = [
                'teacher_id'        => $validated['teacherId'],
                'medical_leaves'    => $validated['medicalLeaves'] ?? null,
                'maternity_leaves'  => $validated['maternityLeaves'] ?? null,
                'casual_leaves'     => $validated['casualLeaves'] ?? null,
                'sick_leaves'       => $validated['sickLeaves'] ?? null,
            ];

            $leaveInfo = TeacherLeaveInfo::create($data);

            return response()->json([
                'message' => 'Leave info created successfully',
                'data'    => $this->formatResponse($leaveInfo),
            ], 201);

        } catch (QueryException $e) {
            return response()->json(['message' => 'Database error', 'error' => $e->getMessage()], 500);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error creating leave info', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $leaveInfo = TeacherLeaveInfo::find($id);
            if (! $leaveInfo) {
                return response()->json(['message' => 'Leave info not found'], 404);
            }

            $validated = $request->validate([
                'teacherId'       => 'sometimes|required|exists:teachers,id',
                'medicalLeaves'   => 'nullable|integer',
                'maternityLeaves' => 'nullable|integer',
                'casualLeaves'    => 'nullable|integer',
                'sickLeaves'      => 'nullable|integer',
            ]);

            $data = [];
            if (isset($validated['teacherId'])) {
                $data['teacher_id'] = $validated['teacherId'];
            }
            if (array_key_exists('medicalLeaves', $validated)) {
                $data['medical_leaves'] = $validated['medicalLeaves'];
            }
            if (array_key_exists('maternityLeaves', $validated)) {
                $data['maternity_leaves'] = $validated['maternityLeaves'];
            }
            if (array_key_exists('casualLeaves', $validated)) {
                $data['casual_leaves'] = $validated['casualLeaves'];
            }
            if (array_key_exists('sickLeaves', $validated)) {
                $data['sick_leaves'] = $validated['sickLeaves'];
            }

            $leaveInfo->update($data);

            return response()->json([
                'message' => 'Leave info updated successfully',
                'data'    => $this->formatResponse($leaveInfo),
            ]);

        } catch (QueryException $e) {
            return response()->json(['message' => 'Database error', 'error' => $e->getMessage()], 500);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error updating leave info', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $leaveInfo = TeacherLeaveInfo::find($id);
            if (! $leaveInfo) {
                return response()->json(['message' => 'Leave info not found'], 404);
            }

            $leaveInfo->delete();

            return response()->json(['message' => 'Leave info deleted successfully']);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error deleting leave info', 'error' => $e->getMessage()], 500);
        }
    }

    private function formatResponse($info)
    {
        return [
            'id'              => $info->id,
            'teacherName' => optional($info->teacher)->first_name . ' ' . optional($info->teacher)->last_name,
            'medicalLeaves'   => $info->medical_leaves,
            'maternityLeaves' => $info->maternity_leaves,
            'casualLeaves'    => $info->casual_leaves,
            'sickLeaves'      => $info->sick_leaves,
            'createdAt'       => optional($info->created_at)->toIso8601String(),
            'updatedAt'       => optional($info->updated_at)->toIso8601String(),
        ];
    }
}
