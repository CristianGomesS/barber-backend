<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\AppointmensStoreUpdateFormResquest;
use App\Http\Resources\AppointmentResource;
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

    public function beforeStore(AppointmensStoreUpdateFormResquest $request): JsonResponse
    {
        $request['customer_id'] = auth()->id();
        try {
            $appointment = $this->service->createAppointment($request->all());
            return response()->json([
                'message' => 'Agendamento solicitado com sucesso!',
                'data' => $appointment
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }
   
    /**
     * Retorna as consultas do usuário autenticado.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function myAppointments(): JsonResponse
    {
        $appointments = $this->service->myAppointments(auth()->id());
        return response()->json(AppointmentResource::collection($appointments));
    }

    /**
     * Retorna a agenda diária do funcionário autenticado.
     * chave data opcional caso nao coloque sera o dia atual
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function mySchedule(Request $request)
    {
        $employeeId = auth()->id();
        $date = $request->input('date');
        $appointments = $this->service->getEmployeeDailyAgenda($employeeId,$date);

        return response()->json($appointments); //;
    }
}
