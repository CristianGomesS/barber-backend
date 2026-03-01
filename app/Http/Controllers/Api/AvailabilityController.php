<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\AvailabilityFormResquest;
use App\Http\Requests\AvailabilityStoreFormResquest;
use App\Services\AvailabilityService;

/**
 * @property AvailabilityService $service
 */

class AvailabilityController extends BaseController
{
    public function __construct(AvailabilityService $service)
    {
        parent::__construct($service);
    }

   /**
 * Obtém os horários disponíveis para um funcionário específico.
 *
 * @param AvailabilityFormResquest $request Objeto contendo as informações da requisição.
 * 
 * @param int $employeeId O ID do funcionário para o qual os horários serão obtidos.
 *
 * @return \Illuminate\Http\JsonResponse Uma resposta JSON contendo os horários disponíveis.
 */
    public function getSlots(AvailabilityFormResquest $request, $employeeId)
    {
        $slots = $this->service->getAvailableSlots(
            (int) $employeeId,
            $request->date,
            (int) $request->service_id
        );
        return response()->json(['data' => $slots]);
    }
    public function BeforeStore(AvailabilityStoreFormResquest $request)
    {
        $this->service->storeBulkAvailability($request->all());

        return response()->json(['message' => 'Disponibilidade salva com sucesso!']);
    }
}