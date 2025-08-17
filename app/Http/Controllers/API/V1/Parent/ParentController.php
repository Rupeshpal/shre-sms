<?php

namespace App\Http\Controllers\API\V1\Parent;

use App\Http\Controllers\Controller;
use App\Models\Parents\Parents;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Support\Facades\Storage;

class ParentController extends Controller
{
    // Convert request from camelCase to snake_case
    private function convertCamelToSnake(array $input): array
    {
        return collect($input)
            ->mapWithKeys(fn($value, $key) => [Str::snake($key) => $value])
            ->toArray();
    }

    // Format response to camelCase
    private function formatResponse(Parents $parent): array
    {
        return [
            'id'                     => $parent->id,
            'parentName'             => $parent->parent_name,
            'gender'                 => $parent->gender,
            'nationality'            => $parent->nationality,
            'occupation'             => $parent->occupation,
            'primaryMobileNumber'    => $parent->primary_mobile_number,
            'alternateContactNumber' => $parent->alternate_contact_number,
            'studentId'              => $parent->student_id,
            'studentName'            => $parent->student->first_name . ' ' . $parent->student->last_name,
            'emailAddress'           => $parent->email_address,
            'temporaryAddress'       => $parent->temporary_address,
            'permanentAddress'       => $parent->permanent_address,
            'addedDate'              => $parent->added_date?->toIso8601String(),
            'image'                  => $parent->image ? asset('storage/' . $parent->image) : null,
            'createdAt'              => $parent->created_at? \Carbon\Carbon::parse($parent->created_at)->toIso8601String() : null,
            'updatedAt'              => $parent->updated_at? \Carbon\Carbon::parse($parent->updated_at)->toIso8601String() : null,
        ];
    }

    public function index()
    {
        $parents = Parents::all()->map(fn($p) => $this->formatResponse($p));
        return response()->json($parents);
    }

    public function store(Request $request)
    {
        try {
            $data = $this->convertCamelToSnake($request->all());

            $validated = validator($data, [
                'parent_name'              => 'required|string',
                'gender'                   => 'required|in:Male,Female,Other',
                'nationality'              => 'nullable|string',
                'occupation'               => 'nullable|string',
                'primary_mobile_number'    => 'required|string',
                'alternate_contact_number' => 'nullable|string',
                'email_address'            => 'nullable|email',
                'temporary_address'        => 'nullable|string',
                'permanent_address'        => 'nullable|string',
                'student_id'               => 'nullable|integer',
                'added_date'               => 'nullable|date',
                'image'                    => 'nullable|file|mimes:jpg,png,jpeg|max:2048',
            ])->validate();

            // Handle image upload
            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('parents', 'public');
            }

            $parent = Parents::create($validated);

            return response()->json([
                'message' => 'Parent created successfully',
                'data'    => $this->formatResponse($parent),
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error creating parent',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $parent = Parents::findOrFail($id);
            return response()->json($this->formatResponse($parent));
        } catch (ModelNotFoundException) {
            return response()->json(['message' => 'Parent not found'], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error fetching parent',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $parent = Parents::findOrFail($id);
            $data = $this->convertCamelToSnake($request->all());

            $validated = validator($data, [
                'parent_name'              => 'sometimes|required|string',
                'gender'                   => 'sometimes|required|in:Male,Female,Other',
                'nationality'              => 'nullable|string',
                'occupation'               => 'nullable|string',
                'primary_mobile_number'    => 'sometimes|required|string',
                'alternate_contact_number' => 'nullable|string',
                'email_address'            => 'nullable|email',
                'temporary_address'        => 'nullable|string',
                'permanent_address'        => 'nullable|string',
                'added_date'               => 'nullable|date',
                'student_id'               => 'nullable|integer',
                'image'                    => 'nullable|file|mimes:jpg,png,jpeg|max:2048',
            ])->validate();

            // Handle image upload
            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('parents', 'public');
            }

            $parent->update($validated);

            return response()->json([
                'message' => 'Parent updated successfully',
                'data'    => $this->formatResponse($parent),
            ]);

        } catch (ModelNotFoundException) {
            return response()->json(['message' => 'Parent not found'], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error updating parent',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $parent = Parents::findOrFail($id);
            $parent->delete();
            return response()->json(['message' => 'Parent deleted successfully']);
        } catch (ModelNotFoundException) {
            return response()->json(['message' => 'Parent not found'], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error deleting parent',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
