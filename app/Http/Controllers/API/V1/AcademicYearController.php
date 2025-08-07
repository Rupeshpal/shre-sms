<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Exception;

class AcademicYearController extends Controller
{

    private function convertCamelToSnake(array $input): array
    {
        return collect($input)->mapWithKeys(function ($value, $key) {
            return [Str::snake($key) => $value];
        })->toArray();
    }

    private function formatResponse(AcademicYear $year)
    {
        return [
            'id' => $year->id,
            'academicYear' => $year->academic_year,
            'startDate' => $year->start_date,
            'endDate' => $year->end_date,
            'semesterCount' => $year->semester_count,
            'examCount' => $year->exam_count,
            'status' => $year->status==1? true : false,
            'currentAcademicYear' => $year->current_academic_year==1 ? true : false,
            'createdAt' => $year->created_at?->toIso8601String(),
            'updatedAt' => $year->updated_at?->toIso8601String(),
        ];
    }

    public function index()
    {
        try {
            $data = AcademicYear::all()->map(fn($year) => $this->formatResponse($year));

            return response()->json([
                'status' => true,
                'message' => 'Academic years fetched successfully',
                'data' => $data,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $input = $this->convertCamelToSnake($request->all());

            $validator = Validator::make($input, [
                'academic_year' => 'nullable|string|max:20',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'semester_count' => 'required|integer',
                'exam_count' => 'required|integer',
                'status' => 'nullable|boolean',
                'current_academic_year' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $year = AcademicYear::create($input);

            return response()->json([
                'status' => true,
                'message' => 'Academic year created successfully',
                'data' => $this->formatResponse($year),
            ], 201);
        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Database error',
                'error' => $e->getMessage(),
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error creating academic year',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        $year = AcademicYear::find($id);

        if (! $year) {
            return response()->json([
                'status' => false,
                'message' => 'Academic year not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Academic year fetched successfully',
            'data' => $this->formatResponse($year),
        ]);
    }

    public function update(Request $request, $id)
    {
        $year = AcademicYear::find($id);

        if (! $year) {
            return response()->json([
                'status' => false,
                'message' => 'Academic year not found',
            ], 404);
        }

        try {
            $input = $this->convertCamelToSnake($request->all());

            $validator = Validator::make($input, [
                'academic_year' => 'nullable|string|max:20',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date',
                'semester_count' => 'nullable|integer',
                'exam_count' => 'nullable|integer',
                'status' => 'nullable|boolean',
                'current_academic_year' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $year->update($input);

            return response()->json([
                'status' => true,
                'message' => 'Academic year updated successfully',
                'data' => $this->formatResponse($year),
            ]);
        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Database error',
                'error' => $e->getMessage(),
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error updating academic year',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        $year = AcademicYear::find($id);

        if (! $year) {
            return response()->json([
                'status' => false,
                'message' => 'Academic year not found',
            ], 404);
        }
        $year->delete();
        return response()->json([
            'status' => true,
            'message' => 'Academic year deleted successfully',
        ]);
    }
}
