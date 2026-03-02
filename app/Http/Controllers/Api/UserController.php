<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\UserStoreFormRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends BaseController
{
    public function __construct(UserService $service)
    {
        parent::__construct($service);
    }

    public function beforeStore(UserStoreFormRequest $request): JsonResponse
    {
        $user = $this->service->store($request->all());
        return response()->json($user, 201);
    }
}