<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Program\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class NoticeController extends Controller
{
    /**
     * Convert camelCase request to snake_case for DB
     */
    private function convertCamelToSnake(array $input): array
    {
        return collect($input)
            ->mapWithKeys(fn($value, $key) => [Str::snake($key) => $value])
            ->toArray();
    }

    /**
     * Format response in camelCase
     */
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
            'attachment'         => $notice->attachment ? asset('storage/' . $notice->attachment) : null,
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
        try {
            $notices = Notice::all()->map(fn($notice) => $this->formatResponse($notice));
            return response()->json(['status' => true, 'data' => $notices]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['status' => false, 'message' => 'Failed to fetch notices'], 500);
        }
    }

    public function show($id)
    {
        try {
            $notice = Notice::find($id);
            if (!$notice) {
                return response()->json(['status' => false, 'message' => 'Notice not found'], 404);
            }
            return response()->json(['status' => true, 'data' => $this->formatResponse($notice)]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['status' => false, 'message' => 'Failed to fetch notice'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $this->convertCamelToSnake($request->all());

            // Convert boolean string values to actual boolean
            foreach (['notice_for_students','notice_for_teachers','notice_for_parents','notice_for_everyone'] as $field) {
                if (isset($data[$field])) {
                    $data[$field] = filter_var($data[$field], FILTER_VALIDATE_BOOLEAN);
                }
            }

            $validated = validator($data, [
                'title'                => 'required|string|max:255',
                'message'              => 'required|string',
                'added_id'             => 'required|integer|exists:users,id',
                'attachment'           => 'nullable|file|mimes:jpg,png,pdf,doc,docx|max:5120',
                'notice_for_students'  => 'boolean',
                'notice_for_teachers'  => 'boolean',
                'notice_for_parents'   => 'boolean',
                'notice_for_everyone'  => 'boolean',
            ])->validate();

            // Handle file upload if exists
            if ($request->hasFile('attachment')) {
                $validated['attachment'] = $request->file('attachment')->store('notices', 'public');
            }

            $notice = Notice::create($validated);

            return response()->json([
                'status'  => true,
                'message' => 'Notice created successfully',
                'data'    => $this->formatResponse($notice),
            ], 201);

        } catch (ValidationException $e) {
            return response()->json(['status' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['status' => false, 'message' => 'Failed to create notice'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $notice = Notice::find($id);
            if (!$notice) {
                return response()->json(['status' => false, 'message' => 'Notice not found'], 404);
            }

            $data = $this->convertCamelToSnake($request->all());

            // Convert boolean string values to actual boolean
            foreach (['notice_for_students','notice_for_teachers','notice_for_parents','notice_for_everyone'] as $field) {
                if (isset($data[$field])) {
                    $data[$field] = filter_var($data[$field], FILTER_VALIDATE_BOOLEAN);
                }
            }

            $validated = validator($data, [
                'title'                => 'sometimes|required|string|max:255',
                'message'              => 'sometimes|required|string',
                'added_id'             => 'sometimes|required|integer|exists:users,id',
                'attachment'           => 'nullable|file|mimes:jpg,png,pdf,doc,docx|max:5120',
                'notice_for_students'  => 'boolean',
                'notice_for_teachers'  => 'boolean',
                'notice_for_parents'   => 'boolean',
                'notice_for_everyone'  => 'boolean',
            ])->validate();

            // Handle file upload and replace existing
            if ($request->hasFile('attachment')) {
                if ($notice->attachment && Storage::disk('public')->exists($notice->attachment)) {
                    Storage::disk('public')->delete($notice->attachment);
                }
                $validated['attachment'] = $request->file('attachment')->store('notices', 'public');
            }

            $notice->update($validated);

            return response()->json([
                'status'  => true,
                'message' => 'Notice updated successfully',
                'data'    => $this->formatResponse($notice),
            ]);

        } catch (ValidationException $e) {
            return response()->json(['status' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['status' => false, 'message' => 'Failed to update notice'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $notice = Notice::find($id);
            if (!$notice) {
                return response()->json(['status' => false, 'message' => 'Notice not found'], 404);
            }

            if ($notice->attachment && Storage::disk('public')->exists($notice->attachment)) {
                Storage::disk('public')->delete($notice->attachment);
            }

            $notice->delete();

            return response()->json(['status' => true, 'message' => 'Notice deleted successfully']);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['status' => false, 'message' => 'Failed to delete notice'], 500);
        }
    }
}
