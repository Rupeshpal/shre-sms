<?php

namespace App\Http\Controllers\API\V1\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Teacher\TeacherSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Exception;

class TeacherSubjectController extends Controller
{
    // Convert request keys to snake_case
    private function convertCamelToSnake(array $input): array
    {
        return collect($input)->mapWithKeys(function ($value, $key) {
            return [Str::snake($key) => $value];
        })->toArray();
    }

    // Convert response keys to camelCase
    private function formatResponse($item)
    {
        return [
            'id' => $item->id,
            'teacher' => $item->teacher ? [
                'teacherId' => $item->teacher->id,
                'firstName' => $item->teacher->first_name,
                'lastName' => $item->teacher->last_name,
                'email' => $item->teacher->email,
            ] : null,
            'subject' => $item->subject_id?[
                'subjectId' => $item->subject->id,
                'subjectName' => $item->subject->name,
            ] : null,
            'createdAt' => $item->created_at?->toIso8601String(),
            'updatedAt' => $item->updated_at?->toIso8601String(),
        ];
    }

    public function index()
    {
        try {
            $data = TeacherSubject::all()->map(fn($item) => $this->formatResponse($item));

            return response()->json([
                'status' => true,
                'message' => 'Teacher subjects fetched successfully',
                'data' => $data,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $item = TeacherSubject::find($id);
            if (!$item) {
                return response()->json([
                    'status' => false,
                    'message' => 'Teacher subject not found'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Teacher subject fetched successfully',
                'data' => $this->formatResponse($item)
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error fetching data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $input = $this->convertCamelToSnake($request->all());

            $validator = Validator::make($input, [
                'teacher_id' => 'required|exists:teachers,id',
                'subject_id' => 'required|exists:subjects,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $created = TeacherSubject::create($input);

            return response()->json([
                'status' => true,
                'message' => 'Teacher subject assigned successfully',
                'data' => $this->formatResponse($created)
            ], 201);

        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Database error',
                'error' => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error assigning subject',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $item = TeacherSubject::find($id);
            if (!$item) {
                return response()->json([
                    'status' => false,
                    'message' => 'Teacher subject not found'
                ], 404);
            }

            $input = $this->convertCamelToSnake($request->all());

            $validator = Validator::make($input, [
                'teacher_id' => 'sometimes|required|exists:teachers,id',
                'subject_id' => 'sometimes|required|exists:subjects,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $item->update($input);

            return response()->json([
                'status' => true,
                'message' => 'Teacher subject updated successfully',
                'data' => $this->formatResponse($item)
            ]);

        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Database error',
                'error' => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error updating subject',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $item = TeacherSubject::find($id);
            if (!$item) {
                return response()->json([
                    'status' => false,
                    'message' => 'Teacher subject not found'
                ], 404);
            }

            $item->delete();

            return response()->json([
                'status' => true,
                'message' => 'Teacher subject deleted successfully'
            ]);

        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Database error',
                'error' => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error deleting subject',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
