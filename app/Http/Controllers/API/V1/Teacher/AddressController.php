<?php

namespace App\Http\Controllers\API\V1\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Teacher\TeacherAddress;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Exception;

class AddressController extends Controller
{
    /**
     * Map camelCase request keys to snake_case database fields
     */
    private function mapRequest(array $data): array
    {
        return [
            'teacher_id'          => $data['teacherId']          ?? null,
            'permanent_street'    => $data['permanentStreet']    ?? null,
            'permanent_city'      => $data['permanentCity']      ?? null,
            'permanent_state'     => $data['permanentState']     ?? null,
            'permanent_country'   => $data['permanentCountry']   ?? null,
            'is_temp_same_as_perm'=> $data['isTempSameAsPerm']   ?? false,
            'temporary_street'    => $data['temporaryStreet']    ?? null,
            'temporary_city'      => $data['temporaryCity']      ?? null,
            'temporary_state'     => $data['temporaryState']     ?? null,
            'temporary_country'   => $data['temporaryCountry']   ?? null,
        ];
    }

    /**
     * Format response into camelCase
     */
    private function formatResponse(TeacherAddress $address): array
    {
        return [
            'id'                 => $address->id,
            'teacherId'          => $address->teacher_id,
            'teacherName'        => optional($address->teacher)->first_name . ' ' . optional($address->teacher)->last_name,
            'permanentStreet'    => $address->permanent_street,
            'permanentCity'      => $address->permanent_city,
            'permanentState'     => $address->permanent_state,
            'permanentCountry'   => $address->permanent_country,
            'isTempSameAsPerm'   => (bool) $address->is_temp_same_as_perm,
            'temporaryStreet'    => $address->temporary_street,
            'temporaryCity'      => $address->temporary_city,
            'temporaryState'     => $address->temporary_state,
            'temporaryCountry'   => $address->temporary_country,
            'createdAt'          => optional($address->created_at)->toIso8601String(),
            'updatedAt'          => optional($address->updated_at)->toIso8601String(),
        ];
    }

    public function index()
    {
        try {
            $addresses = TeacherAddress::all()->map(fn($a) => $this->formatResponse($a));
            return response()->json($addresses);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to fetch addresses', 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'teacherId'          => 'required|exists:teachers,id',
                'permanentStreet'    => 'nullable|string|max:255',
                'permanentCity'      => 'nullable|string|max:255',
                'permanentState'     => 'nullable|string|max:255',
                'permanentCountry'   => 'nullable|string|max:255',
                'isTempSameAsPerm'   => 'boolean',
                'temporaryStreet'    => 'nullable|string|max:255',
                'temporaryCity'      => 'nullable|string|max:255',
                'temporaryState'     => 'nullable|string|max:255',
                'temporaryCountry'   => 'nullable|string|max:255',
            ]);

            $data = $this->mapRequest($validated);

            if (!empty($data['is_temp_same_as_perm'])) {
                $data['temporary_street']  = $data['permanent_street'];
                $data['temporary_city']    = $data['permanent_city'];
                $data['temporary_state']   = $data['permanent_state'];
                $data['temporary_country'] = $data['permanent_country'];
            }

            $address = TeacherAddress::create($data);

            return response()->json($this->formatResponse($address), 201);

        } catch (ValidationException $e) {
            return response()->json(['error' => 'Validation failed', 'messages' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to create address', 'message' => $e->getMessage()], 500);
        }
    }

    public function show(string $id)
    {
        try {
            $address = TeacherAddress::findOrFail($id);
            return response()->json($this->formatResponse($address));
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Address not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to fetch address', 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                'teacherId'          => 'required|exists:teachers,id',
                'permanentStreet'    => 'nullable|string|max:255',
                'permanentCity'      => 'nullable|string|max:255',
                'permanentState'     => 'nullable|string|max:255',
                'permanentCountry'   => 'nullable|string|max:255',
                'isTempSameAsPerm'   => 'boolean',
                'temporaryStreet'    => 'nullable|string|max:255',
                'temporaryCity'      => 'nullable|string|max:255',
                'temporaryState'     => 'nullable|string|max:255',
                'temporaryCountry'   => 'nullable|string|max:255',
            ]);

            $address = TeacherAddress::findOrFail($id);
            $data = $this->mapRequest($validated);

            if (!empty($data['is_temp_same_as_perm'])) {
                $data['temporary_street']  = $data['permanent_street'];
                $data['temporary_city']    = $data['permanent_city'];
                $data['temporary_state']   = $data['permanent_state'];
                $data['temporary_country'] = $data['permanent_country'];
            }

            $address->update($data);

            return response()->json($this->formatResponse($address), 200);

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Address not found'], 404);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Validation failed', 'messages' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update address', 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $address = TeacherAddress::findOrFail($id);
            $address->delete();

            return response()->json(['message' => 'Address deleted successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Address not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to delete address', 'message' => $e->getMessage()], 500);
        }
    }
}
