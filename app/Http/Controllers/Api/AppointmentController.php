<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Services\AppointmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @property AppointmentService $service
 */
class AppointmentController extends BaseController
{
    public function __construct(AppointmentService $service)
    {
        parent::__construct($service);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'schadoled_at' => 'required|date|after:now',
        ]);

        $validated['customer_id'] = auth()->id();

        try {
            $appointment = $this->service->createAppointment($validated);
            return response()->json([
                'message' => 'Agendamento solicitado com sucesso!',
                'data' => $appointment
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
    public function myAppointments(): JsonResponse
    {
        $appointments = $this->service->myAppointments(auth()->id());
        return response()->json(AppointmentResource::collection($appointments));
    }
}
