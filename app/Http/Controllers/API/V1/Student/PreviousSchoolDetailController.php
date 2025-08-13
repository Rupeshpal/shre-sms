<?php

namespace App\Http\Controllers\API\V1\Student;

use App\Http\Controllers\Controller;
use App\Models\Student\PreviousSchoolDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PreviousSchoolDetailController extends Controller
{
    private function convertCamelToSnake(array $input): array
    {
        return collect($input)
            ->mapWithKeys(fn($value, $key) => [Str::snake($key) => $value])
            ->toArray();
    }

    private function formatResponse(PreviousSchoolDetail $detail): array
    {
        return [
            'id' => $detail->id,
            'studentId' => $detail->student_id,
            'student' => $detail->student ? [
                'studentId' => $detail->student->id,
                'studentName' => $detail->student->first_name . ' ' . $detail->student->last_name,
                'studentEmail' => $detail->student->email,
            ] : null,
            'schoolName' => $detail->school_name,
            'location' => $detail->location,
            'affiliationBoard' => $detail->affiliation_board,
            'schoolContactNumber' => $detail->school_contact_number,
            'createdAt' => $detail->created_at?->toIso8601String(),
            'updatedAt' => $detail->updated_at?->toIso8601String(),
        ];
    }

    public function index()
    {
        $details = PreviousSchoolDetail::all()->map(fn($detail) => $this->formatResponse($detail));
        return response()->json($details);
    }

    public function store(Request $request)
    {
        $data = $this->convertCamelToSnake($request->all());

        $validated = validator($data, [
            'student_id'            => 'required|exists:student_personal_info,id',
            'school_name'           => 'nullable|string|max:100',
            'location'              => 'nullable|string|max:100',
            'affiliation_board'     => 'nullable|string|max:50',
            'school_contact_number' => 'nullable|string|max:20',
        ])->validate();

        $detail = PreviousSchoolDetail::create($validated);

        return response()->json([
            'message' => 'Previous school detail created successfully',
            'data'    => $this->formatResponse($detail)
        ], 201);
    }

    public function show($id)
    {
        $detail = PreviousSchoolDetail::find($id);
        if (!$detail) {
            return response()->json(['message' => 'Record not found'], 404);
        }
        return response()->json($this->formatResponse($detail));
    }

    public function update(Request $request, $id)
    {
        $detail = PreviousSchoolDetail::find($id);
        if (!$detail) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        $data = $this->convertCamelToSnake($request->all());

        $validated = validator($data, [
            'student_id'            => 'sometimes|required|exists:student_personal_info,id',
            'school_name'           => 'nullable|string|max:100',
            'location'              => 'nullable|string|max:100',
            'affiliation_board'     => 'nullable|string|max:50',
            'school_contact_number' => 'nullable|string|max:20',
        ])->validate();

        $detail->update($validated);

        return response()->json([
            'message' => 'Previous school detail updated successfully',
            'data'    => $this->formatResponse($detail)
        ]);
    }

    public function destroy($id)
    {
        $detail = PreviousSchoolDetail::find($id);
        if (!$detail) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        $detail->delete();

        return response()->json(['message' => 'Previous school detail deleted successfully']);
    }
}
