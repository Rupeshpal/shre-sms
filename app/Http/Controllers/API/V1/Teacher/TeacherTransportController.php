<?php

namespace App\Http\Controllers\API\V1\Teacher;
use App\Http\Controllers\Controller;
use App\Models\Teacher\TeacherTransport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class TeacherTransportController extends Controller
{
    public function index()
    {
        $transports = TeacherTransport::with('teacher')->get();

        $data = $transports->map(function ($transport) {
            return $this->transformResponse($transport);
        });

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'teacherId'      => 'required|exists:teachers,id',
                'routeId'        => 'required|exists:routes,id',
                'vehicleId'      => 'required|exists:vehicles,id',
                'monthlyFairId'  => 'required|exists:monthly_fairs,id',
                'pickupPointId'  => 'required|exists:pickup_points,id',
            ]);

            $data = $this->transformRequest($validated);

            $transport = TeacherTransport::create($data);

            return response()->json([
                'status'  => true,
                'message' => 'Teacher transport record created successfully',
                'data'    => $this->transformResponse($transport)
            ], 201);
        } catch (ValidationException $e) {
            return response()->json(['status' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['status' => false, 'message' => 'Something went wrong'], 500);
        }
    }

    public function show($id)
    {
        $transport = TeacherTransport::with('teacher')->find($id);
        if (! $transport) {
            return response()->json(['status' => false, 'message' => 'Not found'], 404);
        }

        return response()->json(['status' => true, 'data' => $this->transformResponse($transport)]);
    }

    public function update(Request $request, $id)
    {
        $transport = TeacherTransport::find($id);
        if (! $transport) {
            return response()->json(['status' => false, 'message' => 'Not found'], 404);
        }

        try {
            $validated = $request->validate([
                'teacherId'      => 'required|exists:teachers,id',
                'routeId'        => 'required|exists:routes,id',
                'vehicleId'      => 'required|exists:vehicles,id',
                'monthlyFairId'  => 'required|exists:monthly_fairs,id',
                'pickupPointId'  => 'required|exists:pickup_points,id',
            ]);

            $data = $this->transformRequest($validated);

            $transport->update($data);

            return response()->json([
                'status'  => true,
                'message' => 'Teacher transport record updated successfully',
                'data'    => $this->transformResponse($transport)
            ]);
        } catch (ValidationException $e) {
            return response()->json(['status' => false, 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['status' => false, 'message' => 'Something went wrong'], 500);
        }
    }

    public function destroy($id)
    {
        $transport = TeacherTransport::find($id);
        if (! $transport) {
            return response()->json(['status' => false, 'message' => 'Not found'], 404);
        }

        $transport->delete();

        return response()->json(['status' => true, 'message' => 'Teacher transport record deleted successfully']);
    }

    /**
     * Convert camelCase request → snake_case DB fields
     */
    private function transformRequest(array $data): array
    {
        return [
            'teacher_id'      => $data['teacherId'],
            'route_id'        => $data['routeId'],
            'vehicle_id'      => $data['vehicleId'],
            'monthly_fair_id' => $data['monthlyFairId'],
            'pickup_point_id' => $data['pickupPointId'],
        ];
    }

    private function transformResponse(TeacherTransport $transport): array
    {
        return [
            'id'            => $transport->id,
            'teacherId'     => $transport->teacher_id,
            'teacherName'   => optional($transport->teacher)->first_name . ' ' . optional($transport->teacher)->last_name,
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
