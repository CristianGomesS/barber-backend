<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForceResetPasswordFormRequest;
use App\Http\Requests\ForgotPasswordFormRequest;
use App\Http\Requests\ResetPasswordFormRequest;
use App\Http\Requests\ValidTokenFormRequest;
use App\Services\ForgotPasswordService;
use Illuminate\Http\JsonResponse;

class ForgotPasswordController extends Controller
{
    private $service;

    function __construct(ForgotPasswordService $service)
    {
        $this->service = $service;
    }

  public function forgotPassword(ForgotPasswordFormRequest $request)
    {
        try {
            $this->service->sendResetLink($request->email);
            return response()->json(['message' => 'Token gerado com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function validToken(ValidTokenFormRequest $request): JsonResponse
    {
        $request->validated();
        $this->service->validToken($request->all());
        return response()->json(['message' => 'O seu código é válido!'], 200);
    }

    public function resetPassword(ResetPasswordFormRequest $request): JsonResponse
    {
       try {
            $this->service->resetPassword($request->all());
            return response()->json(['message' => 'Senha alterada com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
    public function forceResetPassword(ForceResetPasswordFormRequest $request): JsonResponse
    {
        $request->validated();
        $this->service->forceResetPassword($request->all());
        return response()->json(['message' => 'Senha redefinida com sucesso!'], 200);
    }

}
