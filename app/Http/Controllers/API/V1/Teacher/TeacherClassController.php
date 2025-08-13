<?php

namespace App\Http\Controllers\Api\V1\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Teacher\TeacherClass;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Database\QueryException;

class TeacherClassController extends Controller
{
    public function index()
    {
        try {
            $classes = TeacherClass::all()->map(fn ($item) => $this->formatResponse($item));
            return response()->json(['data' => $classes], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to fetch data', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $item = TeacherClass::find($id);
            if (! $item) {
                return response()->json(['message' => 'Teacher class not found'], 404);
            }
            return response()->json(['data' => $this->formatResponse($item)], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error fetching data', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                '*.teacherId' => 'required|exists:teachers,id',
                '*.classId'     => 'required|integer| exists:classes,id',
                '*.sectionId'   => 'required|integer| exists:sections,id',
            ]);

            $createdRecords = [];

            foreach ($validated as $item) {
                $data = [
                    'teacher_id' => $item['teacherId'],
                    'class'      => $item['classId'],
                    'section'    => $item['sectionId'],
                ];

                $created = TeacherClass::create($data);
                $createdRecords[] = $this->formatResponse($created);
            }

            return response()->json([
                'message' => 'Teacher classes created successfully',
                'data'    => $createdRecords,
            ], 201);

        } catch (QueryException $e) {
            return response()->json(['message' => 'Database error', 'error' => $e->getMessage()], 500);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error creating records', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $validated = $request->validate([
                '*.id'        => 'required|exists:teacher_classes,id',
                '*.teacherId' => 'sometimes|required|exists:teachers,id',
                '*.classId'     => 'sometimes|required|integer|max:10 | exists:classes,id',
                '*.sectionId'   => 'sometimes|required|integer|max:10| exists:sections,id',
            ]);

            $updatedRecords = [];

            foreach ($validated as $item) {
                $record = TeacherClass::find($item['id']);

                if (! $record) {
                    continue;
                }

                $record->update([
                    'teacher_id' => $item['teacherId'] ?? $record->teacher_id,
                    'class'      => $item['classId'] ?? $record->class,
                    'section'    => $item['sectionId'] ?? $record->section,
                ]);

                $updatedRecords[] = $this->formatResponse($record);
            }

            return response()->json([
                'message' => 'Teacher class records updated successfully',
                'data'    => $updatedRecords,
            ], 200);

        } catch (QueryException $e) {
            return response()->json(['message' => 'Database error', 'error' => $e->getMessage()], 500);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error updating records', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $item = TeacherClass::find($id);
            if (! $item) {
                return response()->json(['message' => 'Teacher class not found'], 404);
            }

            $item->delete();

            return response()->json(['message' => 'Teacher class deleted successfully'], 200);

        } catch (QueryException $e) {
            return response()->json(['message' => 'Database error', 'error' => $e->getMessage()], 500);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error deleting record', 'error' => $e->getMessage()], 500);
        }
    }


    private function formatResponse($item)
    {
        return [
            'id'          => $item->id,
            'teacherId'   => $item->teacher_id,
            'teacherName' => optional($item->teacher)->first_name . ' ' . optional($item->teacher)->last_name,
            'classId'       => $item->class,
            'className'   => optional($item->classRelation)->class_name,
            'sectionId'     => $item->section,
            // 'sectionName' => optional($item->section)->section_name,
            'createdAt'   => optional($item->created_at)->toIso8601String(),
            'updatedAt'   => optional($item->updated_at)->toIso8601String(),
        ];
    }
}
