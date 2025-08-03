<?php

namespace App\Http\Controllers\Api\V1\Student;
use App\Http\Controllers\Controller;
use App\Models\Student\StudentDocument;
use Illuminate\Http\Request;

class StudentDocumentController extends Controller
{
    public function index()
    {
        return response()->json(StudentDocument::all());
    }

  public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'student_id'            => 'required|exists:student_personal_info,id',
            'transfer_certificate'  => 'nullable|file|mimes:jpg,png,pdf|max:2048',
            'birth_certificate'     => 'nullable|file|mimes:jpg,png,pdf|max:2048',
            'character_certificate' => 'nullable|file|mimes:jpg,png,pdf|max:2048',
            'transcripts'           => 'nullable|file|mimes:jpg,png,pdf|max:2048',
            'medical_condition'     => 'nullable|in:Good,Bad,Others',
            'allergies'             => 'nullable|string',
            'medication'            => 'nullable|string',
            'medical_document'      => 'nullable|file|mimes:jpg,png,pdf|max:2048',
        ]);

        if (
            !$request->hasFile('transfer_certificate')
            && !$request->hasFile('birth_certificate')
            && !$request->hasFile('character_certificate')
            && !$request->hasFile('transcripts')
            && !$request->hasFile('medical_document')
            && empty($validated['medical_condition'])
            && empty($validated['allergies'])
            && empty($validated['medication'])
        ) {
            return response()->json([
                'message' => 'No data submitted. Please upload at least one document or provide medical details.'
            ], 422);
        }

        $paths = [];
        foreach (['transfer_certificate', 'birth_certificate', 'character_certificate', 'transcripts', 'medical_document'] as $fileField) {
            if ($request->hasFile($fileField)) {
                $paths[$fileField] = $request->file($fileField)->store('student_documents', 'public');
            }
        }

        $data = array_merge($validated, $paths);
        $document = StudentDocument::create($data);

        return response()->json([
            'message' => 'Student documents saved successfully',
            'data'    => $document
        ], 201);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'message' => 'Validation failed',
            'errors'  => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Unexpected error',
            'error'   => $e->getMessage()
        ], 500);
    }
}


    public function show($id)
    {
        $document = StudentDocument::find($id);
        if (! $document) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json($document);
    }

    public function update(Request $request, $id)
    {
        $document = StudentDocument::find($id);
        if (! $document) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $validated = $request->validate([
            'student_id'            => 'sometimes|required|exists:student_personal_info,id',
            'transfer_certificate'  => 'nullable|file|mimes:jpg,png,pdf|max:2048',
            'birth_certificate'     => 'nullable|file|mimes:jpg,png,pdf|max:2048',
            'character_certificate' => 'nullable|file|mimes:jpg,png,pdf|max:2048',
            'transcripts'           => 'nullable|file|mimes:jpg,png,pdf|max:2048',
            'medical_condition'     => 'nullable|in:Good,Bad,Others',
            'allergies'             => 'nullable|string',
            'medication'            => 'nullable|string',
            'medical_document'      => 'nullable|file|mimes:jpg,png,pdf|max:2048',
        ]);

        foreach (['transfer_certificate', 'birth_certificate', 'character_certificate', 'transcripts', 'medical_document'] as $fileField) {
            if ($request->hasFile($fileField)) {
                $validated[$fileField] = $request->file($fileField)->store('student_documents', 'public');
            }
        }

        $document->update($validated);

        return response()->json(['message' => 'Updated successfully', 'data' => $document]);
    }

    public function destroy($id)
    {
        $document = StudentDocument::find($id);
        if (! $document) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $document->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
