<?php

namespace App\Http\Controllers\Api\V1\Student;

use App\Http\Controllers\Controller;
use App\Models\Student\StudentAddress;
use Illuminate\Http\Request;

class StudentAddressController extends Controller
{
    public function index()
    {
        return response()->json(StudentAddress::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id'    => 'required|exists:student_personal_info,id',
            'temp_street'   => 'nullable|string|max:255',
            'temp_city'     => 'nullable|string|max:100',
            'temp_state'    => 'nullable|string|max:100',
            'temp_country'  => 'nullable|string|max:100',
            'perm_street'   => 'nullable|string|max:255',
            'perm_city'     => 'nullable|string|max:100',
            'perm_state'    => 'nullable|string|max:100',
            'perm_country'  => 'nullable|string|max:100',
        ]);

        $address = StudentAddress::create($validated);

        return response()->json([
            'message' => 'Student address created successfully',
            'data'    => $address
        ], 201);
    }

    public function show($id)
    {
        $address = StudentAddress::find($id);
        if (! $address) {
            return response()->json(['message' => 'Address not found'], 404);
        }
        return response()->json($address);
    }

    public function update(Request $request, $id)
    {
        $address = StudentAddress::find($id);
        if (! $address) {
            return response()->json(['message' => 'Address not found'], 404);
        }

        $validated = $request->validate([
            'student_id'    => 'sometimes|required|exists:student_personal_info,id',
            'temp_street'   => 'nullable|string|max:255',
            'temp_city'     => 'nullable|string|max:100',
            'temp_state'    => 'nullable|string|max:100',
            'temp_country'  => 'nullable|string|max:100',
            'perm_street'   => 'nullable|string|max:255',
            'perm_city'     => 'nullable|string|max:100',
            'perm_state'    => 'nullable|string|max:100',
            'perm_country'  => 'nullable|string|max:100',
        ]);

        $address->update($validated);

        return response()->json([
            'message' => 'Student address updated successfully',
            'data'    => $address
        ]);
    }

    public function destroy($id)
    {
        $address = StudentAddress::find($id);
        if (! $address) {
            return response()->json(['message' => 'Address not found'], 404);
        }

        $address->delete();

        return response()->json(['message' => 'Student address deleted successfully']);
    }
}
