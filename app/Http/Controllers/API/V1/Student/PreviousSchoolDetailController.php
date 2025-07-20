<?php

namespace App\Http\Controllers\Api\V1\Student;
use App\Http\Controllers\Controller;
use App\Models\Student\PreviousSchoolDetail;
use Illuminate\Http\Request;

class PreviousSchoolDetailController extends Controller
{
    public function index()
    {
        return response()->json(PreviousSchoolDetail::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id'            => 'required|exists:student_personal_info,id',
            'school_name'           => 'nullable|string|max:100',
            'location'              => 'nullable|string|max:100',
            'affiliation_board'     => 'nullable|string|max:50',
            'school_contact_number' => 'nullable|string|max:20',
        ]);

        $detail = PreviousSchoolDetail::create($validated);

        return response()->json([
            'message' => 'Previous school detail created successfully',
            'data'    => $detail
        ], 201);
    }

    public function show($id)
    {
        $detail = PreviousSchoolDetail::find($id);
        if (! $detail) {
            return response()->json(['message' => 'Record not found'], 404);
        }
        return response()->json($detail);
    }

    public function update(Request $request, $id)
    {
        $detail = PreviousSchoolDetail::find($id);
        if (! $detail) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        $validated = $request->validate([
            'student_id'            => 'sometimes|required|exists:student_personal_info,id',
            'school_name'           => 'nullable|string|max:100',
            'location'              => 'nullable|string|max:100',
            'affiliation_board'     => 'nullable|string|max:50',
            'school_contact_number' => 'nullable|string|max:20',
        ]);

        $detail->update($validated);

        return response()->json([
            'message' => 'Previous school detail updated successfully',
            'data'    => $detail
        ]);
    }

    public function destroy($id)
    {
        $detail = PreviousSchoolDetail::find($id);
        if (! $detail) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        $detail->delete();

        return response()->json(['message' => 'Previous school detail deleted successfully']);
    }
}
