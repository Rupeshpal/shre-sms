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

            return response()->json([
                'status' => true,
                'message' => 'Transport records fetched successfully',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching transports:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch records',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $this->validateRequest($request);

            $transport = StudentTransport::create($this->transformRequest($validated));

            return response()->json([
                'status' => true,
                'message' => 'Transport record created successfully',
                'data' => $this->transformResponse($transport)
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating StudentTransport:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while creating record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $transport = StudentTransport::with(['student', 'route', 'vehicle', 'monthlyFair', 'pickupPoint'])->findOrFail($id);

            return response()->json([
                'status' => true,
                'message' => 'Transport record fetched successfully',
                'data' => $this->transformResponse($transport)
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching transport:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Transport record not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $transport = StudentTransport::findOrFail($id);

            $validated = $this->validateRequest($request);

            $transport->update($this->transformRequest($validated));

            return response()->json([
                'status' => true,
                'message' => 'Transport record updated successfully',
                'data' => $this->transformResponse($transport)
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating StudentTransport:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while updating record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $transport = StudentTransport::findOrFail($id);

            $transport->delete();

            return response()->json([
                'status' => true,
                'message' => 'Transport record deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting StudentTransport:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to delete record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function validateRequest(Request $request): array
    {
        return $request->validate([
            'studentId'      => 'required|exists:student_personal_info,id',
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
            'id'            => (int) $transport->id,
            'studentId'     => (int) $transport->student_id,
            'teacherName'   => optional($transport->student)->first_name . ' ' . optional($transport->student)->last_name,
            'routeId'       => (int) $transport->route_id,
            'routeName'     => optional($transport->route)->route_name,
            'vehicleId'     => (int) $transport->vehicle_id,
            'vehicleNumber' => optional($transport->vehicle)->vehicle_number,
            'monthlyFairId' => (int) $transport->monthly_fair_id,
            'monthlyFair'   => optional($transport->monthlyFair)->fair_amount,
            'pickupPointId' => (int) $transport->pickup_point_id,
            'pickupPoint'   => optional($transport->pickupPoint)->point_name,
            'createdAt'     => $transport->created_at?->toIso8601String(),
            'updatedAt'     => $transport->updated_at?->toIso8601String(),
        ];
    }
}
