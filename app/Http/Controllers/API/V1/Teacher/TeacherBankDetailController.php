<?php

namespace App\Http\Controllers\Api\V1\Teacher;
use App\Http\Controllers\Controller;
use App\Models\Teacher\TeacherBankDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class TeacherBankDetailController extends Controller
{
    public function index()
    {
        $bankDetails = TeacherBankDetail::all();

        return response()->json([
            'data' => $bankDetails->map(fn ($item) => $this->formatResponse($item))
        ]);
    }

    public function show($id)
    {
        $bankDetail = TeacherBankDetail::find($id);

        if (!$bankDetail) {
            return response()->json(['message' => 'Bank detail not found'], 404);
        }

        return response()->json([
            'data' => $this->formatResponse($bankDetail)
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'teacherId'       => 'required|exists:teachers,id',
                'accountName'     => 'nullable|string|max:100',
                'accountNumber'   => 'nullable|string|max:50',
                'bankName'        => 'nullable|string|max:100',
                'branchName'      => 'nullable|string|max:100',
                'panNumber'       => 'nullable|string|max:50',
                'basicSalary'     => 'nullable|string|max:50',
                'contractType'    => 'nullable|string|max:50',
                'workLocation'    => 'nullable|string|max:100',
                'workShift'       => 'nullable|string|max:100',
                'dateOfLeaving'   => 'nullable|date',
                'qualification'   => 'nullable|string|max:150',
                'workExperience'  => 'nullable|string|max:50',
            ]);

            $data = [
                'teacher_id'       => $validated['teacherId'],
                'account_name'     => $validated['accountName'] ?? null,
                'account_number'   => $validated['accountNumber'] ?? null,
                'bank_name'        => $validated['bankName'] ?? null,
                'branch_name'      => $validated['branchName'] ?? null,
                'pan_number'       => $validated['panNumber'] ?? null,
                'basic_salary'     => $validated['basicSalary'] ?? null,
                'contract_type'    => $validated['contractType'] ?? null,
                'work_location'    => $validated['workLocation'] ?? null,
                'work_shift'       => $validated['workShift'] ?? null,
                'date_of_leaving'  => $validated['dateOfLeaving'] ?? null,
                'qualification'    => $validated['qualification'] ?? null,
                'work_experience'  => $validated['workExperience'] ?? null,
            ];

            $bankDetail = TeacherBankDetail::create($data);

            return response()->json([
                'message' => 'Bank detail created successfully',
                'data' => $this->formatResponse($bankDetail)
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating bank detail: ' . $e->getMessage());

            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $bankDetail = TeacherBankDetail::find($id);

        if (!$bankDetail) {
            return response()->json(['message' => 'Bank detail not found'], 404);
        }

        try {
            $validated = $request->validate([
                'teacherId'       => 'sometimes|required|exists:teachers,id',
                'accountName'     => 'nullable|string|max:100',
                'accountNumber'   => 'nullable|string|max:50',
                'bankName'        => 'nullable|string|max:100',
                'branchName'      => 'nullable|string|max:100',
                'panNumber'       => 'nullable|string|max:50',
                'basicSalary'     => 'nullable|string|max:50',
                'contractType'    => 'nullable|string|max:50',
                'workLocation'    => 'nullable|string|max:100',
                'workShift'       => 'nullable|string|max:100',
                'dateOfLeaving'   => 'nullable|date',
                'qualification'   => 'nullable|string|max:150',
                'workExperience'  => 'nullable|string|max:50',
            ]);

            $data = [
                'teacher_id'       => $validated['teacherId'] ?? $bankDetail->teacher_id,
                'account_name'     => $validated['accountName'] ?? $bankDetail->account_name,
                'account_number'   => $validated['accountNumber'] ?? $bankDetail->account_number,
                'bank_name'        => $validated['bankName'] ?? $bankDetail->bank_name,
                'branch_name'      => $validated['branchName'] ?? $bankDetail->branch_name,
                'pan_number'       => $validated['panNumber'] ?? $bankDetail->pan_number,
                'basic_salary'     => $validated['basicSalary'] ?? $bankDetail->basic_salary,
                'contract_type'    => $validated['contractType'] ?? $bankDetail->contract_type,
                'work_location'    => $validated['workLocation'] ?? $bankDetail->work_location,
                'work_shift'       => $validated['workShift'] ?? $bankDetail->work_shift,
                'date_of_leaving'  => $validated['dateOfLeaving'] ?? $bankDetail->date_of_leaving,
                'qualification'    => $validated['qualification'] ?? $bankDetail->qualification,
                'work_experience'  => $validated['workExperience'] ?? $bankDetail->work_experience,
            ];

            $bankDetail->update($data);

            return response()->json([
                'message' => 'Bank detail updated successfully',
                'data'    => $this->formatResponse($bankDetail)
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating bank detail: ' . $e->getMessage());

            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $bankDetail = TeacherBankDetail::find($id);

        if (!$bankDetail) {
            return response()->json(['message' => 'Bank detail not found'], 404);
        }

        $bankDetail->delete();

        return response()->json(['message' => 'Bank detail deleted successfully']);
    }


    private function formatResponse($bankDetail)
    {
        return [
            'id'             => $bankDetail->id,
            'teacherId'      => $bankDetail->teacher_id,
            'teacherName'   => optional($bankDetail->teacher)->first_name . ' ' . optional($bankDetail->teacher)->last_name,
            'accountName'    => $bankDetail->account_name,
            'accountNumber'  => $bankDetail->account_number,
            'bankName'       => $bankDetail->bank_name,
            'branchName'     => $bankDetail->branch_name,
            'panNumber'      => $bankDetail->pan_number,
            'basicSalary'    => $bankDetail->basic_salary,
            'contractType'   => $bankDetail->contract_type,
            'workLocation'   => $bankDetail->work_location,
            'workShift'      => $bankDetail->work_shift,
            'dateOfLeaving'  => $bankDetail->date_of_leaving,
            'qualification'  => $bankDetail->qualification,
            'workExperience' => $bankDetail->work_experience,
            'createdAt'      => optional($bankDetail->created_at)->toIso8601String(),
            'updatedAt'      => optional($bankDetail->updated_at)->toIso8601String(),
        ];
    }
}
