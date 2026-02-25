<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
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

    public function index(): JsonResponse
    {
        $services = $this->service->getServicesWithBarbers();
        return response()->json($services);
    }
   public function linkBarber(Request $request): JsonResponse
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'services' => 'required|array',
        'services.*.price' => 'required|numeric|min:0'
    ]);

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
