<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    public function index()
    {
        return response()->json(AcademicYear::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'academic_year'        => 'nullable|string|max:20',
            'start_date'           => 'required|date',
            'end_date'             => 'required|date',
            'semester_count'       => 'required|integer',
            'exam_count'           => 'required|integer',
            'status'               => 'nullable|boolean',
            'current_academic_year'=> 'nullable|boolean',
        ]);

        $year = AcademicYear::create($validated);

        return response()->json([
            'message' => 'Academic year created successfully',
            'data'    => $year
        ], 201);
    }

    public function show($id)
    {
        $year = AcademicYear::find($id);
        if (! $year) {
            return response()->json(['message' => 'Academic year not found'], 404);
        }
        return response()->json($year);
    }

    public function update(Request $request, $id)
    {
        $year = AcademicYear::find($id);
        if (! $year) {
            return response()->json(['message' => 'Academic year not found'], 404);
        }

        $validated = $request->validate([
            'academic_year'        => 'nullable|string|max:20',
            'start_date'           => 'nullable|date',
            'end_date'             => 'nullable|date',
            'semester_count'       => 'nullable|integer',
            'exam_count'           => 'nullable|integer',
            'status'               => 'nullable|boolean',
            'current_academic_year'=> 'nullable|boolean',
        ]);

        $year->update($validated);

        return response()->json([
            'message' => 'Academic year updated successfully',
            'data'    => $year
        ]);
    }

    public function destroy($id)
    {
        $year = AcademicYear::find($id);
        if (! $year) {
            return response()->json(['message' => 'Academic year not found'], 404);
        }

        $year->delete();

        return response()->json(['message' => 'Academic year deleted successfully']);
    }
}
