<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginFormRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    private $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
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