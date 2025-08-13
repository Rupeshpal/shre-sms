<?php

namespace App\Http\Controllers\API\V1\Section;

use App\Http\Controllers\Controller;
use App\Models\Section\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SectionController extends Controller
{
    // Convert camelCase request to snake_case
    private function convertCamelToSnake(array $input): array
    {
        return collect($input)
            ->mapWithKeys(fn($value, $key) => [Str::snake($key) => $value])
            ->toArray();
    }

    // Format response to camelCase
    private function formatResponse(Section $section): array
    {
        return [
            'id'          => $section->id,
            'sectionName' => $section->section_name,
            'status'      => $section->status,
            'createdAt'   => $section->created_at?->toIso8601String(),
            'updatedAt'   => $section->updated_at?->toIso8601String(),
        ];
    }

    public function index()
    {
        $sections = Section::all()->map(fn($s) => $this->formatResponse($s));
        return response()->json($sections);
    }

    public function show($id)
    {
        $section = Section::find($id);
        if (! $section) {
            return response()->json(['message' => 'Section not found'], 404);
        }
        return response()->json($this->formatResponse($section));
    }

    public function store(Request $request)
    {
        $data = $this->convertCamelToSnake($request->all());

        $validated = validator($data, [
            'section_name' => 'required|string|max:255',
            'status'       => 'nullable|boolean',
        ])->validate();

        $section = Section::create($validated);

        return response()->json($this->formatResponse($section), 201);
    }

    public function update(Request $request, $id)
    {
        $section = Section::find($id);
        if (! $section) {
            return response()->json(['message' => 'Section not found'], 404);
        }

        $data = $this->convertCamelToSnake($request->all());

        $validated = validator($data, [
            'section_name' => 'sometimes|required|string|max:255',
            'status'       => 'nullable|boolean',
        ])->validate();

        $section->update($validated);

        return response()->json($this->formatResponse($section));
    }

    public function destroy($id)
    {
        $section = Section::find($id);
        if (! $section) {
            return response()->json(['message' => 'Section not found'], 404);
        }

        $section->delete();
        return response()->json(['message' => 'Section deleted successfully']);
    }
}
