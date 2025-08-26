<?php

namespace App\Http\Controllers\API\V1\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Student\Guardian;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class GuardiansController extends Controller
{
    private function convertCamelToSnake(array $input): array
    {
        return collect($input)
            ->mapWithKeys(fn($value, $key) => [Str::snake($key) => $value])
            ->toArray();
    }

    private function formatResponse(Guardian $guardian): array
    {
        return [
            'id' => $guardian->id,
            'studentId' => $guardian->student_id,
            'studentName' => $guardian->student->first_name . ' ' . $guardian->student->last_name ?? null,
            'relationType' => $guardian->relation_type,
            'name' => $guardian->name,
            'email' => $guardian->email,
            'phoneNumber' => $guardian->phone_number,
            'occupation' => $guardian->occupation,
            'temporaryAddress' => $guardian->temporary_address,
            'permanentAddress' => $guardian->permanent_address,
            'nationality' => $guardian->nationality,
            'monthlyIncome' => $guardian->monthly_income,
            'photoPath' => $guardian->photo_path,
            'createdAt' => $guardian->created_at?->toIso8601String(),
            'updatedAt' => $guardian->updated_at?->toIso8601String(),
        ];
    }

    public function index()
    {
        try {
            $guardians = Guardian::all()->map(fn($g) => $this->formatResponse($g));
            return response()->json($guardians);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to fetch guardians', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store multiple guardians in one request
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'studentId' => 'required|exists:student_personal_info,id',
                'guardians' => 'required|array|min:1',
                'guardians.*.relationType' => 'required|in:father,mother,local_guardian',
                'guardians.*.name' => 'required|string|max:100',
                'guardians.*.email' => 'nullable|email|max:150',
                'guardians.*.phoneNumber' => 'nullable|string|max:20',
                'guardians.*.occupation' => 'nullable|string|max:100',
                'guardians.*.temporaryAddress' => 'nullable|string|max:255',
                'guardians.*.permanentAddress' => 'nullable|string|max:255',
                'guardians.*.nationality' => 'nullable|string|max:100',
                'guardians.*.monthlyIncome' => 'nullable|integer',
                'guardians.*.photo' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            ]);

            $createdGuardians = [];

            foreach ($validated['guardians'] as $guardianInput) {
                $guardianData = $this->convertCamelToSnake($guardianInput);
                $guardianData['student_id'] = $validated['studentId'];

                // Handle photo upload if exists
                if (isset($guardianInput['photo']) && $guardianInput['photo'] instanceof \Illuminate\Http\UploadedFile) {
                    $guardianData['photo_path'] = $guardianInput['photo']->store('guardians', 'public');
                }

                $guardian = Guardian::create($guardianData);
                $createdGuardians[] = $this->formatResponse($guardian);
            }

            return response()->json($createdGuardians, 201);

        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to create guardians', 'message' => $e->getMessage()], 500);
        }
    }

    public function show(string $id)
    {
        try {
            $guardian = Guardian::findOrFail($id);
            return response()->json($this->formatResponse($guardian));
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Guardian not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to fetch guardian', 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $guardian = Guardian::findOrFail($id);

            $validated = $request->validate([
                'relationType' => 'sometimes|required|in:father,mother,local_guardian',
                'name' => 'sometimes|required|string|max:100',
                'email' => 'nullable|email|max:150',
                'phoneNumber' => 'nullable|string|max:20',
                'occupation' => 'nullable|string|max:100',
                'temporaryAddress' => 'nullable|string|max:255',
                'permanentAddress' => 'nullable|string|max:255',
                'nationality' => 'nullable|string|max:100',
                'monthlyIncome' => 'nullable|integer',
                'photo' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            ]);

            $guardianData = $this->convertCamelToSnake($validated);

            if ($request->hasFile('photo')) {
                $guardianData['photo_path'] = $request->file('photo')->store('guardians', 'public');
            }

            $guardian->update($guardianData);

            return response()->json($this->formatResponse($guardian));

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Guardian not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update guardian', 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $guardian = Guardian::findOrFail($id);
            $guardian->delete();

            return response()->json(['message' => 'Guardian deleted successfully.']);

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Guardian not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to delete guardian', 'message' => $e->getMessage()], 500);
        }
    }
}
