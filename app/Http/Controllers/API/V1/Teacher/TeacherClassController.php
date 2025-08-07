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
                '*.class'     => 'required|string|max:10',
                '*.section'   => 'required|string|max:10',
            ]);

            $createdRecords = [];

            foreach ($validated as $item) {
                $data = [
                    'teacher_id' => $item['teacherId'],
                    'class'      => $item['class'],
                    'section'    => $item['section'],
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
                '*.class'     => 'sometimes|required|string|max:10',
                '*.section'   => 'sometimes|required|string|max:10',
            ]);

            $updatedRecords = [];

            foreach ($validated as $item) {
                $record = TeacherClass::find($item['id']);

                if (! $record) {
                    continue; // Shouldn't happen due to validation, but safe fallback
                }

                $record->update([
                    'teacher_id' => $item['teacherId'] ?? $record->teacher_id,
                    'class'      => $item['class'] ?? $record->class,
                    'section'    => $item['section'] ?? $record->section,
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

    /**
     * Format model attributes to camelCase for API response.
     */
    private function formatResponse($item)
    {
        return [
            'id'          => $item->id,
            'teacherId'   => $item->teacher_id,
            'teacherName' => optional($item->teacher)->first_name . ' ' . optional($item->teacher)->last_name,
            'class'       => $item->class,
            'section'     => $item->section,
            'createdAt'   => optional($item->created_at)->toIso8601String(),
            'updatedAt'   => optional($item->updated_at)->toIso8601String(),
        ];
    }
}
