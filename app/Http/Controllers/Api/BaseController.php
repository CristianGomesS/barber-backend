<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

abstract class BaseController extends Controller
{
    protected $service;

    public function __construct(BaseService $service)
    {
        $this->service = $service;
    }

    public function index(): JsonResponse
    {
        $data = $this->service->getAll();
        return response()->json($data);
    }

    public function show(int $id): JsonResponse
    {
        $item = $this->service->findById($id);
        return response()->json($item);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->service->store($request->all());
        return response()->json($data, 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $data = $this->service->update($id, $request->all());
        return response()->json($data);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->destroy($id);
        return response()->json([], 204);
    }
}
