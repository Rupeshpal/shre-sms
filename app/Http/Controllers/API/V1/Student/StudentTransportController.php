<?php

namespace App\Http\Controllers\API\V1\Student;

use App\Http\Controllers\Controller;
use App\Models\Student\StudentTransport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class StudentTransportController extends Controller
{
    public function index()
    {
        try {
            $transports = StudentTransport::with(['student', 'route', 'vehicle', 'monthlyFair', 'pickupPoint'])->get();

            $data = $transports->map(fn($transport) => $this->transformResponse($transport));

            return response()->json(['status' => true, 'data' => $data]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['status' => false, 'message' => 'Failed to fetch records'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $this->validateRequest($request);

            $transport = StudentTransport::create($this->transformRequest($validated));

            return response()->json([
                'status'  => true,
                'message' => 'Teacher transport record created successfully',
                'data'    => $this->transformResponse($transport)
            ], 201);
        } catch (ValidationException $e) {
            return response()->json(['status' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['status' => false, 'message' => 'Something went wrong while creating record'], 500);
        }
    }

    public function show($id)
    {
        $transport = StudentTransport::with(['student', 'route', 'vehicle', 'monthlyFair', 'pickupPoint'])->find($id);

        if (!$transport) {
            return response()->json(['status' => false, 'message' => 'Transport record not found'], 404);
        }

        return response()->json(['status' => true, 'data' => $this->transformResponse($transport)]);
    }

    public function update(Request $request, $id)
    {
        $transport = StudentTransport::find($id);

        if (!$transport) {
            return response()->json(['status' => false, 'message' => 'Transport record not found'], 404);
        }

        try {
            $validated = $this->validateRequest($request);

            $transport->update($this->transformRequest($validated));

            return response()->json([
                'status'  => true,
                'message' => 'Teacher transport record updated successfully',
                'data'    => $this->transformResponse($transport)
            ]);
        } catch (ValidationException $e) {
            return response()->json(['status' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['status' => false, 'message' => 'Something went wrong while updating record'], 500);
        }
    }

    public function destroy($id)
    {
        $transport = StudentTransport::find($id);

        if (!$transport) {
            return response()->json(['status' => false, 'message' => 'Transport record not found'], 404);
        }

        try {
            $transport->delete();

            return response()->json(['status' => true, 'message' => 'Teacher transport record deleted successfully']);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['status' => false, 'message' => 'Failed to delete record'], 500);
        }
    }

    private function validateRequest(Request $request): array
    {
        return $request->validate([
            'studentId'      => 'required|exists:teachers,id',
            'routeId'        => 'required|exists:routes,id',
            'vehicleId'      => 'required|exists:vehicles,id',
            'monthlyFairId'  => 'required|exists:monthly_fairs,id',
            'pickupPointId'  => 'required|exists:pickup_points,id',
        ]);
    }

    private function transformRequest(array $data): array
    {
        return [
            'student_id'      => $data['studentId'],
            'route_id'        => $data['routeId'],
            'vehicle_id'      => $data['vehicleId'],
            'monthly_fair_id' => $data['monthlyFairId'],
            'pickup_point_id' => $data['pickupPointId'],
        ];
    }

    private function transformResponse(StudentTransport $transport): array
    {
        return [
            'id'            => $transport->id,
            'studentId'     => $transport->student_id,
            'teacherName'   => optional($transport->student)->first_name . ' ' . optional($transport->student)->last_name,
            'routeId'       => $transport->route_id,
            'routeName'     => optional($transport->route)->route_name,
            'vehicleId'     => $transport->vehicle_id,
            'vehicleNumber' => optional($transport->vehicle)->vehicle_number,
            'monthlyFairId' => $transport->monthly_fair_id,
            'monthlyFair'   => optional($transport->monthlyFair)->fair_amount,
            'pickupPointId' => $transport->pickup_point_id,
            'pickupPoint'   => optional($transport->pickupPoint)->point_name,
            'createdAt'     => $transport->created_at?->toIso8601String(),
            'updatedAt'     => $transport->updated_at?->toIso8601String(),
        ];
    }
}
