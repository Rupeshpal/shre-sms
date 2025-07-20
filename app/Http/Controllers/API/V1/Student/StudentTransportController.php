<?php

namespace App\Http\Controllers\Api\V1\Student;

use App\Http\Controllers\Controller;
use App\Models\Student\StudentTransport;
use Illuminate\Http\Request;

class StudentTransportController extends Controller
{
    public function index()
    {
        return response()->json(StudentTransport::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id'     => 'required|exists:student_personal_info,id',
            'route'          => 'nullable|string|max:100',
            'vehicle_number' => 'nullable|string|max:50',
            'monthly_fare'   => 'nullable|numeric',
            'pickup_point'   => 'nullable|string|max:100',
        ]);

        $transport = StudentTransport::create($validated);

        return response()->json([
            'message' => 'Transport record created successfully',
            'data'    => $transport
        ], 201);
    }

    public function show($id)
    {
        $transport = StudentTransport::find($id);
        if (! $transport) {
            return response()->json(['message' => 'Record not found'], 404);
        }
        return response()->json($transport);
    }

    public function update(Request $request, $id)
    {
        $transport = StudentTransport::find($id);
        if (! $transport) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        $validated = $request->validate([
            'student_id'     => 'sometimes|required|exists:student_personal_info,id',
            'route'          => 'nullable|string|max:100',
            'vehicle_number' => 'nullable|string|max:50',
            'monthly_fare'   => 'nullable|numeric',
            'pickup_point'   => 'nullable|string|max:100',
        ]);

        $transport->update($validated);

        return response()->json([
            'message' => 'Transport record updated successfully',
            'data'    => $transport
        ]);
    }

    public function destroy($id)
    {
        $transport = StudentTransport::find($id);
        if (! $transport) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        $transport->delete();

        return response()->json(['message' => 'Transport record deleted successfully']);
    }
}
