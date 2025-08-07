<?php

namespace App\Http\Controllers\Api\V1\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Teacher\TeacherSocialLink;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Exception;

class TeacherSocialLinkController extends Controller
{
    public function index()
    {
        try {
            $links = TeacherSocialLink::all();
            return response()->json([
                'data' => $links->map(fn($link) => $this->formatResponse($link))
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $link = TeacherSocialLink::find($id);
            if (! $link) {
                return response()->json(['message' => 'Record not found'], 404);
            }
            return response()->json([
                'data' => $this->formatResponse($link)
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error fetching data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'teacherId' => 'required|exists:teachers,id',
                'facebook'  => 'nullable|url|max:255',
                'instagram' => 'nullable|url|max:255',
                'twitter'   => 'nullable|url|max:255',
                'linkedin'  => 'nullable|url|max:255',
                'youtube'   => 'nullable|url|max:255',
                'tiktok'    => 'nullable|url|max:255',
            ]);

            $link = TeacherSocialLink::create([
                'teacher_id' => $validated['teacherId'],
                'facebook'   => $validated['facebook'] ?? null,
                'instagram'  => $validated['instagram'] ?? null,
                'twitter'    => $validated['twitter'] ?? null,
                'linkedin'   => $validated['linkedin'] ?? null,
                'youtube'    => $validated['youtube'] ?? null,
                'tiktok'     => $validated['tiktok'] ?? null,
            ]);

            return response()->json([
                'message' => 'Social links created successfully',
                'data'    => $this->formatResponse($link)
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $e->errors()
            ], 422);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Database error',
                'error'   => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error saving data',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $link = TeacherSocialLink::find($id);
            if (! $link) {
                return response()->json(['message' => 'Record not found'], 404);
            }

            $validated = $request->validate([
                'teacherId' => 'sometimes|required|exists:teachers,id',
                'facebook'  => 'nullable|url|max:255',
                'instagram' => 'nullable|url|max:255',
                'twitter'   => 'nullable|url|max:255',
                'linkedin'  => 'nullable|url|max:255',
                'youtube'   => 'nullable|url|max:255',
                'tiktok'    => 'nullable|url|max:255',
            ]);

            $link->update([
                'teacher_id' => $validated['teacherId'] ?? $link->teacher_id,
                'facebook'   => $validated['facebook'] ?? $link->facebook,
                'instagram'  => $validated['instagram'] ?? $link->instagram,
                'twitter'    => $validated['twitter'] ?? $link->twitter,
                'linkedin'   => $validated['linkedin'] ?? $link->linkedin,
                'youtube'    => $validated['youtube'] ?? $link->youtube,
                'tiktok'     => $validated['tiktok'] ?? $link->tiktok,
            ]);

            return response()->json([
                'message' => 'Social links updated successfully',
                'data'    => $this->formatResponse($link)
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $e->errors()
            ], 422);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Database error',
                'error'   => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error updating data',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $link = TeacherSocialLink::find($id);
            if (! $link) {
                return response()->json(['message' => 'Record not found'], 404);
            }

            $link->delete();

            return response()->json(['message' => 'Social links deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error deleting data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function formatResponse($link)
    {
       return [
    'id' => $link->id,
    'teacher' => $link->teacher ? [
        'teacherId' => $link->teacher->id,
        'firstName' => $link->teacher->first_name,
        'lastName'  => $link->teacher->last_name,
        'email'     => $link->teacher->email,
    ] : null,
    'facebook'  => $link->facebook,
    'instagram' => $link->instagram,
    'twitter'   => $link->twitter,
    'linkedin'  => $link->linkedin,
    'youtube'   => $link->youtube,
    'tiktok'    => $link->tiktok,
    'createdAt' => optional($link->created_at)->toIso8601String(),
    'updatedAt' => optional($link->updated_at)->toIso8601String(),
];

    }
}