<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\LinkBarberFormResquest;
use App\Services\ServiceService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 *@property ServiceService $service
 */
class ServiceController extends BaseController
{
    public function __construct(ServiceService $service)
    {
        parent::__construct($service);
    }

    /**
     * Obtém todos os serviços com seus respectivos barbeiros.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $services = $this->service->getServicesWithBarbers();
        return response()->json($services);
    }
    /**
     * Vincula um barbeiro aos serviços com seus respectivos preços
     * 
     * @param Request $request Objeto contendo as informações da requisição.
     * 
     * @return \Illuminate\Http\JsonResponse Uma resposta JSON contendo o status da requisição.
     * 
     * @throws \Exception Lança um erro se a requisição falhar.
     */
    public function linkBarber(LinkBarberFormResquest $request): JsonResponse
    {
        try {
            $this->service->syncBarberServices(
                $request->user_id,
                $request->services
            );

            return response()->json(['message' => 'Serviços vinculados com sucesso!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Ação não permitida',
                'message' => $e->getMessage()
            ], 403);
        }
    }
}
