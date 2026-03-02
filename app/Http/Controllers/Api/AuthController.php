<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginFormRequest;
use App\Http\Requests\RegisterUserFormRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function register(RegisterUserFormRequest $request)
    {
        $token = $this->service->register($request->validated());
        return response()->json([
            'message' => 'Usuário registrado com sucesso!',
            'token' => $token
        ], 201);
    }

    public function login(LoginFormRequest $request)
    {
        $token = $this->service->login($request);
        return response()->json(['message' => 'Autenticado com sucesso!','token' => $token], 200);
    }

    public function logout(Request $request)
    {
        $this->service->logout($request);
        return response()->json([], 204);
    }
}