<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Program\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NoticeController extends Controller
{
    private function convertCamelToSnake(array $input): array
    {
        return collect($input)
            ->mapWithKeys(fn($value, $key) => [Str::snake($key) => $value])
            ->toArray();
    }

    private function formatResponse(Notice $notice): array
    {
        return [
            'id'                 => $notice->id,
            'title'              => $notice->title,
            'message'            => $notice->message,
            'addedBy'            => $notice->addedBy ? [
                'addedId'        => $notice->addedBy->id,
                'addedName'      => $notice->addedBy->name ?: $notice->addedBy->email,
            ] : null,
            'attachment'         => $notice->attachment,
            'noticeForStudents'  => (bool) $notice->notice_for_students,
            'noticeForTeachers'  => (bool) $notice->notice_for_teachers,
            'noticeForParents'   => (bool) $notice->notice_for_parents,
            'noticeForEveryone'  => (bool) $notice->notice_for_everyone,
            'createdAt'          => $notice->created_at?->toIso8601String(),
            'updatedAt'          => $notice->updated_at?->toIso8601String(),
        ];
    }

    public function index()
    {
        $notices = Notice::all()->map(fn($notice) => $this->formatResponse($notice));
        return response()->json($notices);
    }

    public function show($id)
    {
        $notice = Notice::find($id);
        if (! $notice) {
            return response()->json(['message' => 'Notice not found'], 404);
        }
        return response()->json($this->formatResponse($notice));
    }

    public function store(Request $request)
    {
        $data = $this->convertCamelToSnake($request->all());

        $validated = validator($data, [
            'title'                => 'required|string|max:255',
            'message'              => 'required|string',
            'added_id'             => 'required|integer|exists:users,id',
            'attachment'           => 'nullable|string|max:255',
            'notice_for_students'  => 'boolean',
            'notice_for_teachers'  => 'boolean',
            'notice_for_parents'   => 'boolean',
            'notice_for_everyone'  => 'boolean',
        ])->validate();

        $notice = Notice::create($validated);

        return response()->json([
            'message' => 'Notice created successfully',
            'notice'  => $this->formatResponse($notice),
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $notice = Notice::find($id);
        if (! $notice) {
            return response()->json(['message' => 'Notice not found'], 404);
        }

        $data = $this->convertCamelToSnake($request->all());

        $validated = validator($data, [
            'title'                => 'sometimes|required|string|max:255',
            'message'              => 'sometimes|required|string',
            'added_id'             => 'sometimes|required|integer|exists:users,id',
            'attachment'           => 'nullable|string|max:255',
            'notice_for_students'  => 'boolean',
            'notice_for_teachers'  => 'boolean',
            'notice_for_parents'   => 'boolean',
            'notice_for_everyone'  => 'boolean',
        ])->validate();

        $notice->update($validated);

        return response()->json($this->formatResponse($notice));
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
