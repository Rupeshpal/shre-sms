<?php

namespace App\Http\Controllers\API\V1\Classes;

use App\Http\Controllers\Controller;
use App\Models\Classes\Classes;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class ClassController extends Controller
{
    public function index()
    {
        try {
            $classes = Classes::all();

            $response = $classes->map(function ($class) {
                return $this->formatClass($class);
            });

            return response()->json([
                'status' => true,
                'message' => 'Class list fetched successfully',
                'data' => $response,
            ]);
        } catch (Exception $e) {
            return $this->handleError($e, 'Failed to fetch class list');
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'className' => 'required|string',
                'capacity' => 'required|integer',
                'noOfStudents' => 'required|integer',
                'noOfSubjects' => 'required|integer',
                'roomNo' => 'nullable|integer',
                'crName' => 'nullable|string',
                'classTeacher' => 'nullable|string',
                'classStatus' => 'required|boolean',
            ]);

            $class = Classes::create([
                'class_name' => $request->className,
                'capacity' => $request->capacity,
                'no_of_students' => $request->noOfStudents,
                'no_of_subjects' => $request->noOfSubjects,
                'room_no' => $request->roomNo,
                'cr_name' => $request->crName,
                'class_teacher' => $request->classTeacher,
                'class_status' => $request->classStatus,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Class created successfully',
                'data' => $this->formatClass($class),
            ], 201);
        } catch (Exception $e) {
            return $this->handleError($e, 'Failed to create class');
        }
    }

    public function show($id)
    {
        try {
            $class = Classes::findOrFail($id);

            return response()->json([
                'status' => true,
                'message' => 'Class details fetched successfully',
                'data' => $this->formatClass($class),
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Class not found',
            ], 404);
        } catch (Exception $e) {
            return $this->handleError($e, 'Failed to fetch class details');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $class = Classes::findOrFail($id);

            $request->validate([
                'className' => 'required|string',
                'capacity' => 'required|integer',
                'noOfStudents' => 'required|integer',
                'noOfSubjects' => 'required|integer',
                'roomNo' => 'nullable|integer',
                'crName' => 'nullable|string',
                'classTeacher' => 'nullable|string',
                'classStatus' => 'required|boolean',
            ]);

            $class->update([
                'class_name' => $request->className,
                'capacity' => $request->capacity,
                'no_of_students' => $request->noOfStudents,
                'no_of_subjects' => $request->noOfSubjects,
                'room_no' => $request->roomNo,
                'cr_name' => $request->crName,
                'class_teacher' => $request->classTeacher,
                'class_status' => $request->classStatus,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Class updated successfully',
                'data' => $this->formatClass($class),
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Class not found',
            ], 404);
        } catch (Exception $e) {
            return $this->handleError($e, 'Failed to update class');
        }
    }

    public function destroy($id)
    {
        try {
            $class = Classes::findOrFail($id);

            $class->delete();

            return response()->json([
                'status' => true,
                'message' => 'Class deleted successfully',
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Class not found',
            ], 404);
        } catch (Exception $e) {
            return $this->handleError($e, 'Failed to delete class');
        }
    }

    /**
     * Format the class object to camelCase keys for JSON responses.
     */
    private function formatClass(Classes $class): array
    {
        return [
            'id' => $class->id,
            'className' => $class->class_name,
            'capacity' => $class->capacity,
            'noOfStudents' => $class->no_of_students,
            'noOfSubjects' => $class->no_of_subjects,
            'roomNo' => $class->room_no,
            'crName' => $class->cr_name,
            'classTeacher' => $class->class_teacher,
            'classStatus' => (bool) $class->class_status,
        ];
    }

    /**
     * Handle errors and return a JSON response.
     */
    private function handleError(Exception $e, string $customMessage, int $status = 500)
    {
        return response()->json([
            'status' => false,
            'message' => $customMessage,
            'error' => $e->getMessage(),
        ], $status);
    }
}
