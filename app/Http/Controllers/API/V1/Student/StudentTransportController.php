<?php

namespace App\Http\Controllers\API\V1\Student;

use App\Http\Controllers\Controller;
use App\Models\Student\StudentTransport;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StudentTransportController extends Controller
{
    private function convertCamelToSnake(array $input): array
    {
        return collect($input)
            ->mapWithKeys(fn($value, $key) => [Str::snake($key) => $value])
            ->toArray();
    }

    private function formatResponse(StudentTransport $transport): array
    {
        return [
            'id'            => $transport->id,
            'studentId'     => $transport->student_id,
            'studentName'   => $transport->student?->first_name . ' ' . $transport->student?->last_name ?? null,
            'route'         => $transport->route,
            'vehicleNumber' => $transport->vehicle_number,
            'monthlyFare'   => $transport->monthly_fare,
            'pickupPoint'   => $transport->pickup_point,
            'createdAt'     => $transport->created_at?->toIso8601String(),
            'updatedAt'     => $transport->updated_at?->toIso8601String(),
        ];
    }

    public function index()
    {
        $transports = StudentTransport::all()->map(fn($transport) => $this->formatResponse($transport));
        return response()->json($transports);
    }

    public function store(Request $request)
    {
        $data = $this->convertCamelToSnake($request->all());

        $validated = validator($data, [
            'student_id'     => 'required|exists:student_personal_info,id',
            'route'          => 'nullable|string|max:100',
            'vehicle_number' => 'nullable|string|max:50',
            'monthly_fare'   => 'nullable|numeric',
            'pickup_point'   => 'nullable|string|max:100',
        ])->validate();

        $transport = StudentTransport::create($validated);

        return response()->json([
            'message' => 'Transport record created successfully',
            'data'    => $this->formatResponse($transport)
        ], 201);
    }

    public function show($id)
    {
        $transport = StudentTransport::find($id);
        if (! $transport) {
            return response()->json(['message' => 'Record not found'], 404);
        }
        return response()->json($this->formatResponse($transport));
    }

    public function update(Request $request, $id)
    {
        $transport = StudentTransport::find($id);
        if (! $transport) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        $data = $this->convertCamelToSnake($request->all());

        $validated = validator($data, [
            'student_id'     => 'sometimes|required|exists:student_personal_info,id',
            'route'          => 'nullable|string|max:100',
            'vehicle_number' => 'nullable|string|max:50',
            'monthly_fare'   => 'nullable|numeric',
            'pickup_point'   => 'nullable|string|max:100',
        ])->validate();

        $transport->update($validated);

        return response()->json([
            'message' => 'Transport record updated successfully',
            'data'    => $this->formatResponse($transport)
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
