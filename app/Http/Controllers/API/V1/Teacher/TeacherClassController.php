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
            return response()->json(TeacherClass::all(), 200);
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
                'class'      => 'required|string|max:10',
                'section'    => 'required|string|max:10',
            ]);

            $created = TeacherClass::create($validated);

            return response()->json([
                'message' => 'Teacher class created successfully',
                'data' => $created
            ], 201);

        } catch (QueryException $e) {
            return response()->json(['message' => 'Database error', 'error' => $e->getMessage()], 500);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error creating record', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $item = TeacherClass::find($id);
            if (! $item) {
                return response()->json(['message' => 'Teacher class not found'], 404);
            }

            $validated = $request->validate([
                'teacher_id' => 'sometimes|required|exists:teachers,id',
                'class'      => 'sometimes|required|string|max:10',
                'section'    => 'sometimes|required|string|max:10',
            ]);

            $item->update($validated);

            return response()->json([
                'message' => 'Teacher class updated successfully',
                'data' => $item
            ], 200);

        } catch (QueryException $e) {
            return response()->json(['message' => 'Database error', 'error' => $e->getMessage()], 500);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error updating record', 'error' => $e->getMessage()], 500);
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
}
