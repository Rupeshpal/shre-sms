<?php
namespace App\Http\Controllers\Api\V1\Teacher;
use App\Http\Controllers\Controller;
use App\Models\Teacher\TeacherSocialLink;
use Illuminate\Http\Request;
use Exception;

class TeacherSocialLinkController extends Controller
{
    public function index()
    {
        try {
            return response()->json(TeacherSocialLink::all(), 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to fetch data', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $link = TeacherSocialLink::find($id);
            if (! $link) {
                return response()->json(['message' => 'Record not found'], 404);
            }
            return response()->json($link, 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error fetching data', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'teacher_id' => 'required|exists:teachers,id',
                'facebook'   => 'nullable|url|max:255',
                'instagram'  => 'nullable|url|max:255',
                'twitter'    => 'nullable|url|max:255',
                'linkedin'   => 'nullable|url|max:255',
                'youtube'    => 'nullable|url|max:255',
                'tiktok'     => 'nullable|url|max:255',
            ]);

            $link = TeacherSocialLink::create($validated);

            return response()->json([
                'message' => 'Social links created successfully',
                'data' => $link
            ], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error saving data', 'error' => $e->getMessage()], 500);
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
                'teacher_id' => 'sometimes|required|exists:teachers,id',
                'facebook'   => 'nullable|url|max:255',
                'instagram'  => 'nullable|url|max:255',
                'twitter'    => 'nullable|url|max:255',
                'linkedin'   => 'nullable|url|max:255',
                'youtube'    => 'nullable|url|max:255',
                'tiktok'     => 'nullable|url|max:255',
            ]);

            $link->update($validated);

            return response()->json([
                'message' => 'Social links updated successfully',
                'data' => $link
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error updating data', 'error' => $e->getMessage()], 500);
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
            return response()->json(['message' => 'Error deleting data', 'error' => $e->getMessage()], 500);
        }
    }
}
