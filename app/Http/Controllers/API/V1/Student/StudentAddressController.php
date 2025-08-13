<?php

namespace App\Http\Controllers\Api\V1\Student;
use App\Http\Controllers\Controller;
use App\Models\Student\StudentAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StudentAddressController extends Controller
{
    private function convertCamelToSnake(array $input): array
    {
        return collect($input)
            ->mapWithKeys(fn($value, $key) => [Str::snake($key) => $value])
            ->toArray();
    }

    private function formatResponse(StudentAddress $address): array
    {
        return [
            'id'           => $address->id,
            'studentId'    => $address->student_id,
            'studentName' => $address->student->first_name . ' '. $address->student->last_name ?? null,
            'tempStreet'   => $address->temp_street,
            'tempCity'     => $address->temp_city,
            'tempState'    => $address->temp_state,
            'tempCountry'  => $address->temp_country,
            'permStreet'   => $address->perm_street,
            'permCity'     => $address->perm_city,
            'permState'    => $address->perm_state,
            'permCountry'  => $address->perm_country,
            'createdAt'    => $address->created_at?->toIso8601String(),
            'updatedAt'    => $address->updated_at?->toIso8601String(),
        ];
    }

    public function index()
    {
        $addresses = StudentAddress::all()->map(fn($address) => $this->formatResponse($address));
        return response()->json($addresses);
    }

    public function store(Request $request)
    {
        $data = $this->convertCamelToSnake($request->all());

        $validated = validator($data, [
            'student_id'   => 'required|exists:student_personal_info,id',
            'temp_street'  => 'nullable|string|max:255',
            'temp_city'    => 'nullable|string|max:100',
            'temp_state'   => 'nullable|string|max:100',
            'temp_country' => 'nullable|string|max:100',
            'perm_street'  => 'nullable|string|max:255',
            'perm_city'    => 'nullable|string|max:100',
            'perm_state'   => 'nullable|string|max:100',
            'perm_country' => 'nullable|string|max:100',
        ])->validate();

        $address = StudentAddress::create($validated);

        return response()->json([
            'message' => 'Student address created successfully',
            'data'    => $this->formatResponse($address)
        ], 201);
    }

    public function show($id)
    {
        $address = StudentAddress::find($id);
        if (!$address) {
            return response()->json(['message' => 'Address not found'], 404);
        }
        return response()->json($this->formatResponse($address));
    }

    public function update(Request $request, $id)
    {
        $address = StudentAddress::find($id);
        if (!$address) {
            return response()->json(['message' => 'Address not found'], 404);
        }

        $data = $this->convertCamelToSnake($request->all());

        $validated = validator($data, [
            'student_id'   => 'sometimes|required|exists:student_personal_info,id',
            'temp_street'  => 'nullable|string|max:255',
            'temp_city'    => 'nullable|string|max:100',
            'temp_state'   => 'nullable|string|max:100',
            'temp_country' => 'nullable|string|max:100',
            'perm_street'  => 'nullable|string|max:255',
            'perm_city'    => 'nullable|string|max:100',
            'perm_state'   => 'nullable|string|max:100',
            'perm_country' => 'nullable|string|max:100',
        ])->validate();

        $address->update($validated);

        return response()->json([
            'message' => 'Student address updated successfully',
            'data'    => $this->formatResponse($address)
        ]);
    }

    public function destroy($id)
    {
        $address = StudentAddress::find($id);
        if (!$address) {
            return response()->json(['message' => 'Address not found'], 404);
        }

        $address->delete();

        return response()->json(['message' => 'Student address deleted successfully']);
    }
}
