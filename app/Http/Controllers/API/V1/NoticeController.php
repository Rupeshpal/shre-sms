<?php
namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use App\Models\Program\Notice;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function index()
    {
        return response()->json(Notice::all());
    }

    public function show($id)
    {
        $notice = Notice::find($id);
        if (! $notice) {
            return response()->json(['message' => 'Notice not found'], 404);
        }
        return response()->json($notice);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'                  => 'required|string|max:255',
            'message'                => 'required|string',
            'added_id'               => 'required|integer|exists:users,id',
            'attachment'             => 'nullable|string|max:255',
            'notice_for_students'    => 'boolean',
            'notice_for_teachers'    => 'boolean',
            'notice_for_parents'     => 'boolean',
            'notice_for_everyone'    => 'boolean',
        ]);

        $notice = Notice::create($validated);

        return response()->json(
            [
                'message' => 'Notice created successfully',
                'notice'  => $notice
            ], 201
        );
    }

    public function update(Request $request, $id)
    {
        $notice = Notice::find($id);
        if (! $notice) {
            return response()->json(['message' => 'Notice not found'], 404);
        }

        $validated = $request->validate([
            'title'                  => 'sometimes|required|string|max:255',
            'message'                => 'sometimes|required|string',
            'added_id'               => 'sometimes|required|integer|exists:users,id',
            'attachment'             => 'nullable|string|max:255',
            'notice_for_students'    => 'boolean',
            'notice_for_teachers'    => 'boolean',
            'notice_for_parents'     => 'boolean',
            'notice_for_everyone'    => 'boolean',
        ]);

        $notice->update($validated);

        return response()->json($notice);
    }

    public function destroy($id)
    {
        $notice = Notice::find($id);
        if (! $notice) {
            return response()->json(['message' => 'Notice not found'], 404);
        }

        $notice->delete();

        return response()->json(['message' => 'Notice deleted successfully']);
    }
}