<?php

namespace App\Http\Controllers\Api\V1\Parent;

use App\Http\Controllers\Controller;
use App\Models\Parents\Parents;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class ParentController extends Controller
{
    public function index()
    {
        return response()->json(Parents::all());
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'parent_name'              => 'required|string',
                'gender'                   => 'required|in:Male,Female,Other',
                'nationality'              => 'nullable|string',
                'occupation'               => 'nullable|string',
                'primary_mobile_number'    => 'required|string',
                'alternate_contact_number' => 'nullable|string',
                'email_address'            => 'nullable|email',
                'temporary_address'        => 'nullable|string',
                'permanent_address'        => 'nullable|string',
                'added_date'               => 'nullable|date',
                'image'                    => 'nullable|string',
            ]);

            $parent = Parents::create($validated);

            return response()->json([
                'message' => 'Parent created successfully',
                'data'    => $parent
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
            return response()->json($parent);
        } catch (ModelNotFoundException $e) {
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

            $validated = $request->validate([
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
                'image'                    => 'nullable|string',
            ]);

            $parent->update($validated);

            return response()->json([
                'message' => 'Parent updated successfully',
                'data'    => $parent
            ]);
        } catch (ModelNotFoundException $e) {
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
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Parent not found'], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error deleting parent',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}