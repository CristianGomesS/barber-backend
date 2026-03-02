<?php

namespace App\Services;

use App\Repositories\Core\UserRepository;
use App\Services\BaseService;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserService extends BaseService
{
    public function __construct(UserRepository $repository)
    {
        parent::__construct($repository);
    }

    public function register(array $data)
    {
        // Define o papel padrão como 'customer' caso não exista
        $customerRole = Role::where('slug', 'customer')->first();
        if ($customerRole) {
            $data['role_id'] = $customerRole->id;
        }

        $user = $this->repository->store($data);
        
        // Carrega o usuário atualizado com as habilidades do role associado
        $user = $this->repository->findWhereFirst('id', $user->id);
        
        $abilities = $user->role ? $user->role->abilities()->pluck('slug')->toArray() : [];
        $deviceName = substr(request()->header('User-Agent', 'Unknown'), 0, 255);
        return $user->createToken($deviceName, $abilities)->plainTextToken;
    }

    public function login(Request $data)
    {
        $user = $this->repository->findWhereFirst('email', $data->email);
         if (! $user || ! Hash::check($data->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['As credenciais fornecidas estão incorretas.'],
            ]);
        }
        $user->tokens()->delete();

        $abilities = $user->role->abilities()->pluck('slug')->toArray();
        $deviceName = substr(request()->header('User-Agent', 'Unknown'), 0, 255);
        return $user->createToken($deviceName, $abilities)->plainTextToken;
    }
    public function logout($request): void
    {
        $request->user()->currentAccessToken()->delete();
    }
}
