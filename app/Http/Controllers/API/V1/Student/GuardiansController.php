<?php
namespace App\Http\Controllers\API\V1\Student;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\student\Guardian;

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
        $guardians = Guardian::all()->map(fn($g) => $this->formatResponse($g));
        return response()->json($guardians);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'studentId' => 'required|exists:student_personal_info,id',
            'relationType' => 'required|in:father,mother,local_guardian',
            'name' => 'required|string|max:100',
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

        $guardian = Guardian::create($guardianData);

        return response()->json($this->formatResponse($guardian), 201);
    }

    public function show(string $id)
    {
        $guardian = Guardian::findOrFail($id);
        return response()->json($this->formatResponse($guardian));
    }

    public function update(Request $request, string $id)
    {
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
    }

    public function destroy(string $id)
    {
        $guardian = Guardian::findOrFail($id);
        $guardian->delete();

        return response()->json(['message' => 'Guardian deleted successfully.']);
    }
}
