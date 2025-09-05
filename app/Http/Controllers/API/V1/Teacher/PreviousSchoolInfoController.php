<?php

namespace App\Http\Controllers\API\V1\Teacher;
use App\Http\Controllers\Controller;
use App\Models\Teacher\PreviousTeacherInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PreviousTeacherInfoController extends Controller
{
    private function convertCamelToSnake(array $input): array
    {
        return collect($input)->mapWithKeys(function ($value, $key) {
            return [Str::snake($key) => $value];
        })->toArray();
    }

    private function formatResponse(PreviousTeacherInfo $info): array
    {
        return [
            'id' => $info->id,
            'teacherId' => $info->teacher_id,
            'schoolName' => $info->school_name,
            'location' => $info->location,
            'affiliationBoard' => $info->affiliation_board,
            'schoolContactNumber' => $info->school_contact_number,
            'createdAt' => $info->created_at?->toIso8601String(),
            'updatedAt' => $info->updated_at?->toIso8601String(),
        ];
    }

    public function index()
    {
        
        $infos = PreviousTeacherInfo::with('student')->get();

        $data = $infos->map(fn($info) => $this->formatResponse($info));

        return response()->json([
            'status' => true,
            'message' => 'Previous school records fetched successfully',
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $input = $this->convertCamelToSnake($request->all());

        $validated = validator($input, [
            'teacher_id' => 'required|exists:teachers,id',
            'school_name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'affiliation_board' => 'nullable|string|max:255',
            'school_contact_number' => 'nullable|string|max:20',
        ])->validate();

        $info = PreviousTeacherInfo::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Previous school record created successfully',
            'data' => $this->formatResponse($info)
        ], 201);
    }

    public function show($id)
    {
        $info = PreviousTeacherInfo::with('teacher')->find($id);

        if (!$info) {
            return response()->json([
                'status' => false,
                'message' => 'Record not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Record fetched successfully',
            'data' => $this->formatResponse($info)
        ]);
    }

    public function update(Request $request, $id)
    {
        $info = PreviousTeacherInfo::find($id);

        if (!$info) {
            return response()->json([
                'status' => false,
                'message' => 'Record not found'
            ], 404);
        }

        $input = $this->convertCamelToSnake($request->all());

        $validated = validator($input, [
            'teacher_id' => 'nullable|exists:teachers,id',
            'school_name' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'affiliation_board' => 'nullable|string|max:255',
            'school_contact_number' => 'nullable|string|max:20',
        ])->validate();

        $info->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Record updated successfully',
            'data' => $this->formatResponse($info)
        ]);
    }

    public function destroy($id)
    {
        $info = PreviousTeacherInfo::find($id);

        if (!$info) {
            return response()->json([
                'status' => false,
                'message' => 'Record not found'
            ], 404);
        }

        $info->delete();

        return response()->json([
            'status' => true,
            'message' => 'Record deleted successfully'
        ]);
    }
}
