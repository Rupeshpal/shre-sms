<?php

namespace App\Http\Controllers\Api\V1\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Teacher\TeacherSubject;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Database\QueryException;

class TeacherSubjectController extends Controller
{
    public function index()
    {
        try {
            return response()->json(TeacherSubject::all(), 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to fetch data', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $item = TeacherSubject::find($id);
            if (! $item) {
                return response()->json(['message' => 'Teacher subject not found'], 404);
            }
            return response()->json($item, 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error fetching data', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'teacher_id' => 'required|exists:teachers,id',
                'subject_id' => 'required|exists:subjects,id',
            ]);

            $created = TeacherSubject::create($validated);

            return response()->json([
                'message' => 'Teacher subject assigned successfully',
                'data' => $created
            ], 201);

        } catch (QueryException $e) {
            return response()->json(['message' => 'Database error', 'error' => $e->getMessage()], 500);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error assigning subject', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $item = TeacherSubject::find($id);
            if (! $item) {
                return response()->json(['message' => 'Teacher subject not found'], 404);
            }

            $validated = $request->validate([
                'teacher_id' => 'sometimes|required|exists:teachers,id',
                'subject_id' => 'sometimes|required|exists:subjects,id',
            ]);

            $item->update($validated);

            return response()->json([
                'message' => 'Teacher subject updated successfully',
                'data' => $item
            ], 200);

        } catch (QueryException $e) {
            return response()->json(['message' => 'Database error', 'error' => $e->getMessage()], 500);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error updating subject', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $item = TeacherSubject::find($id);
            if (! $item) {
                return response()->json(['message' => 'Teacher subject not found'], 404);
            }

            $item->delete();

            return response()->json(['message' => 'Teacher subject deleted successfully'], 200);

        } catch (QueryException $e) {
            return response()->json(['message' => 'Database error', 'error' => $e->getMessage()], 500);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error deleting subject', 'error' => $e->getMessage()], 500);
        }
    }
}
