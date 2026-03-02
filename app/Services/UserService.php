<?php

namespace App\Services;

use App\Repositories\Core\UserRepository;
use App\Services\BaseService;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class UserService extends BaseService
{
    public function __construct(UserRepository $repository)
    {
        parent::__construct($repository);
    }

    public function register(array $data)
    {
        $customerRole = Role::where('slug', 'customer')->first();
        if ($customerRole) {
            $data['role_id'] = $customerRole->id;
        }
        $user = $this->repository->store($data);
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
    public function store(array $data): void
    {
        $data['password'] = Str::random(10);
        $this->repository->store($data);
        $linkBase = request()->server('HTTP_ORIGIN') ?? 'URL NÃO ENCONTRADA';
        Mail::send('email.accountCreation',  [
            'code' => $data['password'],
            "link" => $linkBase,
            'name' => 'BarberShop',
            'title' => 'BarberShop - Estilo e Atitude',
            'logo'  => public_path('img/logotipoTeste.png')
        ], function ($message) use ($data) {
            $message->to($data['email']);
            $message->subject('Criação de Conta - BarberShop');
        });
    }
}
