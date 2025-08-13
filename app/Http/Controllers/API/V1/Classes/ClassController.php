<?php

namespace App\Http\Controllers\API\V1\Classes;

use App\Http\Controllers\Controller;
use App\Models\Classes\Classes;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $classes = Classes::all();

        $response = $classes->map(function ($class) {
            return $this->formatClass($class);
        });

        return response()->json([
            'status' => true,
            'message' => 'Class list fetched successfully',
            'data' => $response,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'className' => 'required|string',
            'capacity' => 'required|integer',
            'noOfStudents' => 'required|integer',
            'noOfSubjects' => 'required|integer',
            'crName' => 'nullable|string',
            'classTeacher' => 'nullable|string',
            'classStatus' => 'required|boolean',
        ]);

        $class = Classes::create([
            'class_name' => $request->className,
            'capacity' => $request->capacity,
            'no_of_students' => $request->noOfStudents,
            'no_of_subjects' => $request->noOfSubjects,
            'cr_name' => $request->crName,
            'class_teacher' => $request->classTeacher,
            'class_status' => $request->classStatus,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Class created successfully',
            'data' => $this->formatClass($class),
        ], 201);
    }

    public function show($id)
    {
        $class = Classes::find($id);

        if (!$class) {
            return response()->json([
                'status' => false,
                'message' => 'Class not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Class details fetched successfully',
            'data' => $this->formatClass($class),
        ]);
    }

    public function update(Request $request, $id)
    {
        $class = Classes::find($id);

        if (!$class) {
            return response()->json([
                'status' => false,
                'message' => 'Class not found',
            ], 404);
        }

        $request->validate([
            'className' => 'required|string',
            'capacity' => 'required|integer',
            'noOfStudents' => 'required|integer',
            'noOfSubjects' => 'required|integer',
            'crName' => 'nullable|string',
            'classTeacher' => 'nullable|string',
            'classStatus' => 'required|boolean',
        ]);

        $class->update([
            'class_name' => $request->className,
            'capacity' => $request->capacity,
            'no_of_students' => $request->noOfStudents,
            'no_of_subjects' => $request->noOfSubjects,
            'cr_name' => $request->crName,
            'class_teacher' => $request->classTeacher,
            'class_status' => $request->classStatus,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Class updated successfully',
            'data' => $this->formatClass($class),
        ]);
    }

    public function destroy($id)
    {
        $class = Classes::find($id);

        if (!$class) {
            return response()->json([
                'status' => false,
                'message' => 'Class not found',
            ], 404);
        }

        $class->delete();

        return response()->json([
            'status' => true,
            'message' => 'Class deleted successfully',
        ]);
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
            'crName' => $class->cr_name,
            'classTeacher' => $class->class_teacher,
            'classStatus' => $class->class_status,
        ];
    }
}
