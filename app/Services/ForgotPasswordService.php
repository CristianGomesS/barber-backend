<?php

namespace App\Services;

use App\Repositories\Core\PasswordResetRepository;
use App\Exceptions\CodeException;
use App\Repositories\Core\UserRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordService
{
    private $repository;
    private $userRepository;

    public function __construct(PasswordResetRepository $repository, UserRepository $userRepository)
    {
        $this->repository = $repository;
        $this->userRepository = $userRepository;
    }

    public function sendResetLink(string $email): void
    {
        $user = $this->userRepository->findWhereFirst('email', $email);
        if (!$user) throw new Exception("Usuário não encontrado.");
        $token = Str::random(5);
        $this->repository->createToken($email, $token);


        $linkBase = request()->server('HTTP_REFERER') ?? request()->server('HTTP_ORIGIN') ?? 'URL NÃO ENCONTRADA';
        Mail::send('email.forgetPassword', [
            'code' => $token,
            "link" => $linkBase,
            'name' => 'BarberShop',
            'title' => 'BarberShop - Estilo e Atitude',
            'logo'  => public_path('img/logotipoTeste.png')
        ], function ($message) use ($email) {
            $message->to($email);
            $message->subject('Resetar Senha - BarberShop');
        });
    }

    public function resetPassword(array $data)
    {
        $this->validToken($data);
        $user = $this->userRepository->findWhereFirst('email', $data['email']);
        $user->update(['password' => Hash::make($data['password'])]);
        $this->repository->deleteToken($data['email']);

        return true;
    }

    public function validToken(array $data)
    {
        $record = $this->repository->findToken($data['email'], $data['token']);
        if (!$record) throw new Exception("Token inválido ou expirado.");
        return true;
    }
    public function forceResetPassword(array $data)
    {
        $data['password'] = Str::random(10);
        $user = $this->userRepository->findWhereFirst('email', $data['email']);
        $user->update(['password' => Hash::make($data['password'])]);
        $linkBase = request()->server('HTTP_ORIGIN') ?? 'URL NÃO ENCONTRADA';
        Mail::send('email.forceResetPassword', [
            'code' => $data['password'],
            "link" => $linkBase,
            'name' => 'BarberShop',
            'title' => 'BarberShop - Estilo e Atitude',
            'logo'  => public_path('img/logotipoTeste.png')
        ], function ($message) use ($data) {
            $message->to($data['email']);
            $message->subject('Senha Resetada - BarberShop');
        });
    }
}
