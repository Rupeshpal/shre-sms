<?php

namespace App\Http\Controllers\Api\V1\Section;

use App\Http\Controllers\Controller;
use App\Models\Section\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index()
    {
        return response()->json(Section::all());
    }

    public function show($id)
    {
        $section = Section::find($id);
        if (! $section) {
            return response()->json(['message' => 'Section not found'], 404);
        }
        return response()->json($section);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id'            => 'required|string|unique:sections',
            'section_name'  => 'required|string|max:255',
            'status'        => 'nullable|in:0,1',
        ]);

        $section = Section::create($validated);

        return response()->json($section, 201);
    }

    public function update(Request $request, $id)
    {
        $section = Section::find($id);
        if (! $section) {
            return response()->json(['message' => 'Section not found'], 404);
        }

        $validated = $request->validate([
            'section_name'  => 'sometimes|required|string|max:255',
            'status'        => 'nullable|in:0,1',
        ]);

        $section->update($validated);

        return response()->json($section);
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
